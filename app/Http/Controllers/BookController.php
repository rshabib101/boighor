<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\BookReview;
use App\Models\BookDownload;
use App\Models\ReadingHistory;
use App\Models\Advertisement;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['author', 'category'])->active();

        if ($request->filled('sort')) {
            match($request->sort) {
                'new'      => $query->latest(),
                'popular'  => $query->orderBy('view_count', 'desc'),
                'download' => $query->orderBy('download_count', 'desc'),
                'rating'   => $query->orderBy('rating', 'desc'),
                default    => $query->latest(),
            };
        } else {
            $query->latest();
        }

        if ($request->filled('format')) {
            $query->where('file_format', strtoupper($request->format));
        }

        $books = $query->paginate(24)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(string $slug)
    {
        $book = Book::with(['author', 'category', 'publisher', 'reviews.user'])
            ->active()->where('slug', $slug)->firstOrFail();

        // Increment view count
        $book->increment('view_count');

        // Save to reading history
        $this->saveToHistory($book);

        // Related books
        $relatedBooks = Book::with('author')
            ->active()
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->limit(6)->get();

        $sidebarAd = Advertisement::getForPosition('sidebar');
        $inContentAd = Advertisement::getForPosition('in_content');

        $isFavorited = false;
        $isBookmarked = false;
        $userReview = null;

        if (auth()->check()) {
            $isFavorited = auth()->user()->favorites()->where('book_id', $book->id)->exists();
            $isBookmarked = auth()->user()->bookmarks()->where('book_id', $book->id)->exists();
            $userReview = BookReview::where('book_id', $book->id)->where('user_id', auth()->id())->first();
        }

        return view('books.show', compact(
            'book', 'relatedBooks', 'sidebarAd', 'inContentAd',
            'isFavorited', 'isBookmarked', 'userReview'
        ));
    }

    public function download(Request $request, string $slug)
    {
        $book = Book::where('slug', $slug)->active()->firstOrFail();
        $requestedFormat = strtolower($request->get('format', ''));
        if ($requestedFormat === 'epub') {
            $format = 'epub';
            $filePath = $book->epub_path;
        } else {
            $format = 'pdf';
            $filePath = $book->pdf_path ?: $book->epub_path;
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'ফাইলটি পাওয়া যাচ্ছে না।');
        }

        // Log download
        BookDownload::create([
            'book_id' => $book->id,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'format' => $format,
        ]);
        $book->increment('download_count');

        // Give reward points to logged-in users
        if (auth()->check()) {
            RewardService::giveDownloadBonus(auth()->user(), $book);
        }

        return Storage::disk('public')->download($filePath, $book->title . '.' . $format);
    }

    public function read(string $slug)
    {
        $book = Book::where('slug', $slug)->active()->firstOrFail();
        if (!$book->pdf_path || !Storage::disk('public')->exists($book->pdf_path)) {
            return back()->with('error', 'এই বইটির পড়ার সুবিধা নেই বা ফাইলটি পাওয়া যায়নি।');
        }
        $pdfUrl = Storage::disk('public')->url($book->pdf_path);
        return view('books.read', compact('book', 'pdfUrl'));
    }

    public function storeReview(Request $request, string $slug)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'লগইন করুন'], 401);
        }

        $request->validate(['rating' => 'required|integer|min:1|max:5', 'review' => 'nullable|string|max:1000']);
        $book = Book::where('slug', $slug)->firstOrFail();

        BookReview::updateOrCreate(
            ['book_id' => $book->id, 'user_id' => auth()->id()],
            ['rating' => $request->rating, 'review' => $request->review]
        );

        $book->updateRating();

        return response()->json(['success' => true, 'message' => 'রিভিউ সংরক্ষণ করা হয়েছে।']);
    }

    public function toggleFavorite(Request $request, string $slug)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'লগইন করুন'], 401);
        }

        $book = Book::where('slug', $slug)->firstOrFail();
        $existing = auth()->user()->favorites()->where('book_id', $book->id)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['success' => true, 'favorited' => false]);
        } else {
            auth()->user()->favorites()->create(['book_id' => $book->id]);
            return response()->json(['success' => true, 'favorited' => true]);
        }
    }

    public function toggleBookmark(Request $request, string $slug)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'লগইন করুন'], 401);
        }

        $book = Book::where('slug', $slug)->firstOrFail();
        $existing = auth()->user()->bookmarks()->where('book_id', $book->id)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['success' => true, 'bookmarked' => false]);
        } else {
            auth()->user()->bookmarks()->create(['book_id' => $book->id, 'page_number' => 1]);
            return response()->json(['success' => true, 'bookmarked' => true]);
        }
    }

    private function saveToHistory(Book $book): void
    {
        ReadingHistory::updateOrCreate(
            [
                'book_id' => $book->id,
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
            ],
            ['updated_at' => now()]
        );
    }
}

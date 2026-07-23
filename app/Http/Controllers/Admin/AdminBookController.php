<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminBookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['author', 'category', 'publisher']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $books = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $authors = Author::where('is_active', true)->get();
        $publishers = Publisher::where('is_active', true)->get();
        return view('admin.books.create', compact('categories', 'authors', 'publishers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'description'      => 'nullable|string',
            'cover_image'      => 'nullable|image|max:5120',
            'pdf_file'         => 'nullable|mimes:pdf|max:51200',
            'epub_file'        => 'nullable|mimes:epub|max:51200',
            'language'         => 'nullable|string',
            'pages'            => 'nullable|integer|min:1',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        $data = $request->except(['cover_image', 'pdf_file', 'epub_file']);
        $data['slug'] = $this->generateUniqueSlug($request->title);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $data['pdf_path'] = $file->store('books/pdf', 'public');
            $data['file_size_mb'] = round($file->getSize() / 1048576, 2);
            $data['file_format'] = 'PDF';
        }
        if ($request->hasFile('epub_file')) {
            $file = $request->file('epub_file');
            $data['epub_path'] = $file->store('books/epub', 'public');
            $data['file_format'] = isset($data['pdf_path']) ? 'BOTH' : 'EPUB';
        }

        $data['is_featured']  = $request->boolean('is_featured');
        $data['is_sponsored'] = $request->boolean('is_sponsored');
        $data['is_active']    = $request->boolean('is_active', true);

        Book::create($data);
        return redirect()->route('admin.books.index')->with('success', 'বইটি সফলভাবে যোগ করা হয়েছে।');
    }

    public function edit(Book $book)
    {
        $categories = Category::where('is_active', true)->get();
        $authors = Author::where('is_active', true)->get();
        $publishers = Publisher::where('is_active', true)->get();
        return view('admin.books.edit', compact('book', 'categories', 'authors', 'publishers'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:5120',
            'pdf_file'    => 'nullable|mimes:pdf|max:51200',
            'epub_file'   => 'nullable|mimes:epub|max:51200',
        ]);

        $data = $request->except(['cover_image', 'pdf_file', 'epub_file']);
        $data['is_featured']  = $request->boolean('is_featured');
        $data['is_sponsored'] = $request->boolean('is_sponsored');
        $data['is_active']    = $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }
        if ($request->hasFile('pdf_file')) {
            if ($book->pdf_path) Storage::disk('public')->delete($book->pdf_path);
            $file = $request->file('pdf_file');
            $data['pdf_path'] = $file->store('books/pdf', 'public');
            $data['file_size_mb'] = round($file->getSize() / 1048576, 2);
        }
        if ($request->hasFile('epub_file')) {
            if ($book->epub_path) Storage::disk('public')->delete($book->epub_path);
            $data['epub_path'] = $request->file('epub_file')->store('books/epub', 'public');
        }

        $book->update($data);
        return redirect()->route('admin.books.index')->with('success', 'বই আপডেট হয়েছে।');
    }

    public function destroy(Book $book)
    {
        if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);
        if ($book->pdf_path) Storage::disk('public')->delete($book->pdf_path);
        if ($book->epub_path) Storage::disk('public')->delete($book->epub_path);
        $book->delete();
        return back()->with('success', 'বইটি মুছে ফেলা হয়েছে।');
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Book::where('slug', 'like', $slug . '%')->count();
        return $count > 0 ? $slug . '-' . ($count + 1) : $slug;
    }
}

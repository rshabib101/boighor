<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $newBooks = Book::with(['author', 'category'])->newBooks(12)->get();
        $popularBooks = Book::with(['author', 'category'])->popular(12)->get();
        $mostDownloaded = Book::with(['author', 'category'])->mostDownloaded(12)->get();
        $featuredBooks = Book::with(['author', 'category'])->featured()->active()->limit(5)->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $newAuthors = Author::where('is_active', true)->latest()->limit(8)->get();

        // Category books (6 each for homepage)
        $categoryBooks = [];
        foreach ($categories->take(6) as $category) {
            $categoryBooks[$category->id] = Book::with('author')
                ->where('category_id', $category->id)
                ->active()->latest()->limit(6)->get();
        }

        $headerAd = Advertisement::getForPosition('header');
        $sidebarAd = Advertisement::getForPosition('sidebar');

        return view('home.index', compact(
            'newBooks', 'popularBooks', 'mostDownloaded', 'featuredBooks',
            'categories', 'newAuthors', 'categoryBooks', 'headerAd', 'sidebarAd'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $books = Book::with(['author', 'category'])
            ->active()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhereHas('author', fn($a) => $a->where('name', 'like', "%{$query}%"))
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$query}%"));
            })
            ->paginate(24)
            ->withQueryString();

        return view('books.search', compact('books', 'query'));
    }
}

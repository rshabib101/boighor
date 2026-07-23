<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Book;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['books' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)->orderBy('sort_order')->get();
        return view('categories.index', compact('categories'));
    }

    public function show(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $query = Book::with(['author'])->active()->where('category_id', $category->id);

        if ($request->filled('sort')) {
            match($request->sort) {
                'popular'  => $query->orderBy('view_count', 'desc'),
                'download' => $query->orderBy('download_count', 'desc'),
                'rating'   => $query->orderBy('rating', 'desc'),
                default    => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $books = $query->paginate(24)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('categories.show', compact('category', 'books', 'categories'));
    }
}

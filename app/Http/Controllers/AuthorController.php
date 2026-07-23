<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::withCount(['books' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)->latest()->paginate(24);
        return view('authors.index', compact('authors'));
    }

    public function show(string $slug)
    {
        $author = Author::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $books = Book::with('category')->where('author_id', $author->id)->active()->latest()->paginate(18);
        return view('authors.show', compact('author', 'books'));
    }
}

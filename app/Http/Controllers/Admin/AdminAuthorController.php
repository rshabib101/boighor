<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminAuthorController extends Controller
{
    public function index()
    {
        $authors = Author::withCount('books')->latest()->paginate(20);
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:200', 'image' => 'nullable|image|max:2048']);
        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(4);
        $data['is_active'] = $request->boolean('is_active', true);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('authors', 'public');
        }
        Author::create($data);
        return redirect()->route('admin.authors.index')->with('success', 'লেখক যোগ করা হয়েছে।');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate(['name' => 'required|string|max:200']);
        $data = $request->except('image');
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            if ($author->image) Storage::disk('public')->delete($author->image);
            $data['image'] = $request->file('image')->store('authors', 'public');
        }
        $author->update($data);
        return redirect()->route('admin.authors.index')->with('success', 'লেখক আপডেট হয়েছে।');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return back()->with('success', 'লেখক মুছে ফেলা হয়েছে।');
    }
}

@extends('layouts.app')
@section('title', $category->name . ' বই - বইঘর')
@section('content')
<div class="container" style="padding-top:32px;padding-bottom:50px">
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px">
        <div class="cat-icon" style="background:{{ $category->color }}20;color:{{ $category->color }};width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem">
            <i class="{{ $category->icon }}"></i>
        </div>
        <div>
            <h1 style="font-size:1.6rem;font-weight:700">{{ $category->name }}</h1>
            <p style="color:var(--text-3)">{{ $books->total() }} টি বই পাওয়া গেছে</p>
        </div>
    </div>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;flex-wrap:wrap">
        @foreach($categories as $cat)
        <a href="{{ route('categories.show', $cat->slug) }}"
           class="badge-pill {{ $cat->id === $category->id ? 'badge-primary' : '' }}"
           style="{{ $cat->id !== $category->id ? 'background:var(--bg-2);color:var(--text-2);' : '' }}padding:6px 14px">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>
    <form method="GET" style="margin-bottom:20px;display:flex;gap:8px">
        <select name="sort" class="form-select" style="width:auto" onchange="this.form.submit()">
            <option value="">নতুন বই</option>
            <option value="popular" {{ request('sort')==='popular'?'selected':'' }}>জনপ্রিয়</option>
            <option value="download" {{ request('sort')==='download'?'selected':'' }}>ডাউনলোড</option>
            <option value="rating" {{ request('sort')==='rating'?'selected':'' }}>রেটিং</option>
        </select>
    </form>
    @if($books->count())
    <div class="books-grid books-grid-wide">
        @foreach($books as $book)
            @include('partials.book-card', ['book' => $book])
        @endforeach
    </div>
    <div class="pagination-wrap">{{ $books->links('partials.pagination') }}</div>
    @else
    <div class="empty-state"><i class="{{ $category->icon }}"></i><h3>এই ক্যাটাগরিতে এখনো কোনো বই নেই</h3></div>
    @endif
</div>
@endsection

@extends('layouts.app')
@section('title', $author->name . ' - বইঘর')
@section('content')
<div class="container" style="padding-top:32px;padding-bottom:50px">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:32px;margin-bottom:32px;display:flex;gap:28px;align-items:center;flex-wrap:wrap">
        <div style="width:120px;height:120px;border-radius:50%;overflow:hidden;border:4px solid var(--primary);flex-shrink:0;background:var(--bg-2)">
            <img src="{{ $author->image_url }}" alt="{{ $author->name }}" style="width:100%;height:100%;object-fit:cover">
        </div>
        <div>
            <h1 style="font-size:1.8rem;font-weight:700">{{ $author->name }}</h1>
            @if($author->nationality)<p style="color:var(--text-3)"><i class="fas fa-globe"></i> {{ $author->nationality }}</p>@endif
            @if($author->bio)<p style="color:var(--text-2);margin-top:10px;line-height:1.7">{{ $author->bio }}</p>@endif
            <div style="margin-top:12px">
                <span class="badge-pill badge-primary"><i class="fas fa-book"></i> {{ $books->total() }} টি বই</span>
            </div>
        </div>
    </div>
    <div class="books-grid books-grid-wide">
        @foreach($books as $book)
            @include('partials.book-card', ['book' => $book])
        @endforeach
    </div>
    <div class="pagination-wrap">{{ $books->links('partials.pagination') }}</div>
</div>
@endsection

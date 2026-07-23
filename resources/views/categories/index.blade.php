@extends('layouts.app')
@section('title', 'সব ক্যাটাগরি - বইঘর')
@section('content')
<div class="container" style="padding-top:32px;padding-bottom:50px">
    <h1 class="section-title mb-4">সব ক্যাটাগরি</h1>
    <div class="categories-grid" style="grid-template-columns:repeat(auto-fill,minmax(160px,1fr))">
        @foreach($categories as $cat)
        <a href="{{ route('categories.show', $cat->slug) }}" class="category-card" style="--cat-color:{{ $cat->color }}">
            <div class="cat-icon" style="background:{{ $cat->color }}20;color:{{ $cat->color }}"><i class="{{ $cat->icon }}"></i></div>
            <div class="cat-name">{{ $cat->name }}</div>
            <div class="cat-count">{{ $cat->books_count }} টি বই</div>
        </a>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'লেখক তালিকা - বইঘর')
@section('content')
<div class="container" style="padding-top:32px;padding-bottom:50px">
    <h1 class="section-title mb-4">সব লেখক</h1>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:20px">
        @foreach($authors as $author)
        <a href="{{ route('authors.show', $author->slug) }}" style="text-align:center;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:24px 16px;transition:var(--transition);display:block">
            <div style="width:90px;height:90px;border-radius:50%;overflow:hidden;margin:0 auto 14px;border:3px solid var(--primary);background:var(--bg-2)">
                <img src="{{ $author->image_url }}" alt="{{ $author->name }}" style="width:100%;height:100%;object-fit:cover">
            </div>
            <div style="font-weight:700;font-size:.95rem">{{ $author->name }}</div>
            <div style="font-size:.8rem;color:var(--text-3);margin-top:4px">{{ $author->books_count }} টি বই</div>
        </a>
        @endforeach
    </div>
    <div class="pagination-wrap">{{ $authors->links('partials.pagination') }}</div>
</div>
@endsection

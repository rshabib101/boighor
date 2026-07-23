@extends('layouts.app')
@section('title', 'পড়ুন: ' . $book->title)
@section('content')
<div style="background:var(--bg-2);border-bottom:1px solid var(--border);padding:10px 0">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
        <div style="display:flex;align-items:center;gap:10px">
            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-arrow-left"></i> ফিরে যান</a>
            <span style="font-weight:600;font-size:.95rem">{{ $book->title }}</span>
        </div>
        <div style="display:flex;gap:8px">
            <button id="readingModeBtn" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> পড়ার মোড</button>
            <a href="{{ route('books.download', $book->slug) }}?format=pdf" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> ডাউনলোড</a>
        </div>
    </div>
</div>
<div style="height:calc(100vh - 60px);background:#525659">
    <iframe src="{{ $pdfUrl }}#toolbar=1"
            style="width:100%;height:100%;border:none"
            title="{{ $book->title }}">
    </iframe>
</div>
@endsection

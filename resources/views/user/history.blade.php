@extends('layouts.app')
@section('title', 'ডাউনলোড হিস্ট্রি - বইঘর')
@section('content')
<div class="container"><div class="user-page-layout">
    @include('partials.user-sidebar')
    <div>
        <div class="form-card">
            <h2 style="font-size:1.1rem;margin-bottom:20px">ডাউনলোড হিস্ট্রি</h2>
            @forelse($downloads as $dl)
            <div style="display:flex;gap:14px;align-items:center;padding:12px 0;border-bottom:1px solid var(--border)">
                <img src="{{ $dl->book?->cover_url }}" alt="" style="width:50px;height:70px;object-fit:cover;border-radius:6px;flex-shrink:0">
                <div style="flex:1">
                    <a href="{{ route('books.show', $dl->book?->slug) }}" style="font-weight:600;font-size:.95rem">{{ $dl->book?->title }}</a>
                    <div style="font-size:.8rem;color:var(--text-3)"><i class="fas fa-user-edit"></i> {{ $dl->book?->author?->name }}</div>
                    <div style="font-size:.78rem;color:var(--text-4)">{{ $dl->created_at->format('d M Y, h:i A') }} · {{ strtoupper($dl->format) }}</div>
                </div>
                <a href="{{ route('books.download', $dl->book?->slug) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a>
            </div>
            @empty
            <div class="empty-state"><i class="fas fa-download"></i><h3>কোনো ডাউনলোড নেই</h3><p>বই ডাউনলোড করলে এখানে দেখাবে।</p></div>
            @endforelse
            <div class="pagination-wrap">{{ $downloads->links('partials.pagination') }}</div>
        </div>
    </div>
</div></div>
@endsection

@extends('layouts.app')
@section('title', 'বুকমার্ক - বইঘর')
@section('content')
<div class="container"><div class="user-page-layout">
    @include('partials.user-sidebar')
    <div>
        <div class="form-card">
            <h2 style="font-size:1.1rem;margin-bottom:20px">বুকমার্ক করা বই</h2>
            @forelse($bookmarks as $bm)
            @if($bm->book)
            <div style="display:flex;gap:14px;align-items:center;padding:12px 0;border-bottom:1px solid var(--border)">
                <img src="{{ $bm->book->cover_url }}" alt="" style="width:50px;height:70px;object-fit:cover;border-radius:6px">
                <div style="flex:1">
                    <a href="{{ route('books.show', $bm->book->slug) }}" style="font-weight:600">{{ $bm->book->title }}</a>
                    <div style="font-size:.8rem;color:var(--text-3)">পৃষ্ঠা {{ $bm->page_number }}</div>
                </div>
                <a href="{{ route('books.read', $bm->book->slug) }}" class="btn btn-sm btn-primary"><i class="fas fa-book-reader"></i> পড়ুন</a>
            </div>
            @endif
            @empty
            <div class="empty-state"><i class="fas fa-bookmark"></i><h3>কোনো বুকমার্ক নেই</h3></div>
            @endforelse
        </div>
    </div>
</div></div>
@endsection

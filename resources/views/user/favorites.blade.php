@extends('layouts.app')
@section('title', 'ফেভারিট - বইঘর')
@section('content')
<div class="container"><div class="user-page-layout">
    @include('partials.user-sidebar')
    <div>
        <div class="form-card">
            <h2 style="font-size:1.1rem;margin-bottom:20px">ফেভারিট বই</h2>
            @forelse($favorites as $fav)
            @if($fav->book)
            <div style="display:flex;gap:14px;align-items:center;padding:12px 0;border-bottom:1px solid var(--border)">
                <img src="{{ $fav->book->cover_url }}" alt="" style="width:50px;height:70px;object-fit:cover;border-radius:6px">
                <div style="flex:1">
                    <a href="{{ route('books.show', $fav->book->slug) }}" style="font-weight:600">{{ $fav->book->title }}</a>
                    <div style="font-size:.8rem;color:var(--text-3)">{{ $fav->book->author?->name }}</div>
                    <div style="font-size:.78rem;color:var(--text-4)">{{ $fav->book->category?->name }}</div>
                </div>
                <div style="display:flex;gap:6px">
                    <a href="{{ route('books.download', $fav->book->slug) }}" class="btn btn-sm btn-primary"><i class="fas fa-download"></i></a>
                    <button class="btn btn-sm btn-outline-primary js-favorite" data-slug="{{ $fav->book->slug }}"><i class="fas fa-heart"></i></button>
                </div>
            </div>
            @endif
            @empty
            <div class="empty-state"><i class="fas fa-heart"></i><h3>কোনো ফেভারিট নেই</h3><p>বইয়ে ❤️ চাপলে এখানে যোগ হবে।</p></div>
            @endforelse
            <div class="pagination-wrap">{{ $favorites->links('partials.pagination') }}</div>
        </div>
    </div>
</div></div>
@endsection

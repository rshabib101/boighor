@extends('layouts.app')
@section('title', '"' . $query . '" - সার্চ ফলাফল - বইঘর')
@section('content')
<div class="container" style="padding-top:32px;padding-bottom:50px">
    <div class="section-header">
        <h1 class="section-title">"{{ $query }}" - সার্চ ফলাফল</h1>
        <span style="color:var(--text-3)">{{ $books->total() }} টি বই পাওয়া গেছে</span>
    </div>
    @if($books->count())
    <div class="books-grid books-grid-wide">
        @foreach($books as $book)
            @include('partials.book-card', ['book' => $book])
        @endforeach
    </div>
    <div class="pagination-wrap">{{ $books->links('partials.pagination') }}</div>
    @else
    <div class="empty-state">
        <i class="fas fa-search"></i>
        <h3>"{{ $query }}" - কোনো বই পাওয়া যায়নি</h3>
        <p>অন্য কীওয়ার্ড দিয়ে খোঁজ করুন</p>
        <a href="{{ route('books.index') }}" class="btn btn-primary mt-3">সব বই দেখুন</a>
    </div>
    @endif
</div>
@endsection

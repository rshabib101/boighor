@extends('layouts.app')
@section('title', 'সব বই - বইঘর')
@section('content')
<div class="container" style="padding-top:32px;padding-bottom:50px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
        <h1 class="section-title">সব বই</h1>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
                <select name="sort" class="form-select" style="width:auto" onchange="this.form.submit()">
                    <option value="new" {{ request('sort','new')==='new'?'selected':'' }}>নতুন বই</option>
                    <option value="popular" {{ request('sort')==='popular'?'selected':'' }}>জনপ্রিয়</option>
                    <option value="download" {{ request('sort')==='download'?'selected':'' }}>ডাউনলোড</option>
                    <option value="rating" {{ request('sort')==='rating'?'selected':'' }}>রেটিং</option>
                </select>
                <select name="format" class="form-select" style="width:auto" onchange="this.form.submit()">
                    <option value="">সব ফরম্যাট</option>
                    <option value="pdf" {{ request('format')==='pdf'?'selected':'' }}>PDF</option>
                    <option value="epub" {{ request('format')==='epub'?'selected':'' }}>EPUB</option>
                </select>
            </form>
        </div>
    </div>
    @if($books->count())
    <div class="books-grid books-grid-wide">
        @foreach($books as $book)
            @include('partials.book-card', ['book' => $book])
        @endforeach
    </div>
    <div class="pagination-wrap">
        {{ $books->links('partials.pagination') }}
    </div>
    @else
    <div class="empty-state"><i class="fas fa-book-open"></i><h3>কোনো বই পাওয়া যায়নি</h3></div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'বইঘর - বাংলা বইয়ের সেরা ঠিকানা')

@section('content')

{{-- Hero Banner --}}
<section class="hero-section">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            @forelse($featuredBooks as $book)
            <div class="swiper-slide">
                <div style="position:absolute;inset:0;background:url('{{ $book->cover_url }}') center/cover no-repeat;opacity:.15;filter:blur(20px);"></div>
                <div class="container">
                    <div class="hero-slide-content">
                        <div class="hero-text">
                            <div class="hero-tag"><i class="fas fa-star"></i> ফিচারড বই</div>
                            <h1 class="hero-title">{{ $book->title }}</h1>
                            <p class="hero-author"><i class="fas fa-user-edit"></i> {{ $book->author?->name ?? 'অজানা লেখক' }}</p>
                            <div class="hero-actions">
                                <a href="{{ route('books.show', $book->slug) }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-book-open"></i> বইটি দেখুন
                                </a>
                                <a href="{{ route('books.download', $book->slug) }}" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.4);">
                                    <i class="fas fa-download"></i> ডাউনলোড
                                </a>
                            </div>
                        </div>
                        <div class="hero-book-cover">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" loading="eager">
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <div class="container">
                    <div class="hero-slide-content">
                        <div class="hero-text">
                            <div class="hero-tag"><i class="fas fa-book"></i> বইঘরে আপনাকে স্বাগতম</div>
                            <h1 class="hero-title">বাংলা বইয়ের সেরা সংগ্রহ</h1>
                            <p class="hero-author">হাজারো বাংলা বই বিনামূল্যে পড়ুন ও ডাউনলোড করুন</p>
                            <div class="hero-actions">
                                <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg"><i class="fas fa-search"></i> বই খুঁজুন</a>
                                <a href="{{ route('register') }}" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.4);"><i class="fas fa-gift"></i> ৫০ পয়েন্ট নিন</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

{{-- Header Ad --}}
@if($headerAd)
<div class="container mt-3">
    <div class="text-center">{!! $headerAd->ad_code !!}</div>
</div>
@endif

{{-- New Books --}}
<section class="section books-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">নতুন বই</h2>
            <a href="{{ route('books.index') }}?sort=new" class="section-link">সব দেখুন <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="swiper books-swiper">
            <div class="swiper-wrapper">
                @foreach($newBooks as $book)
                <div class="swiper-slide">
                    @include('partials.book-card', ['book' => $book])
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Popular Books --}}
<section class="section books-section" style="background:var(--bg-2);padding-top:40px;padding-bottom:40px;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">জনপ্রিয় বই</h2>
            <a href="{{ route('books.index') }}?sort=popular" class="section-link">সব দেখুন <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="swiper books-swiper">
            <div class="swiper-wrapper">
                @foreach($popularBooks as $book)
                <div class="swiper-slide">
                    @include('partials.book-card', ['book' => $book])
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Reward Banner --}}
@guest
<section class="section-sm" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
    <div class="container text-center" style="color:white;">
        <h2 style="font-size:1.5rem;margin-bottom:8px;"><i class="fas fa-gift"></i> রেজিস্টার করুন, পয়েন্ট পান!</h2>
        <p style="margin-bottom:16px;opacity:.9;">রেজিস্ট্রেশনে পান ৫০ পয়েন্ট, দৈনিক লগইনে পান ১০ পয়েন্ট। পয়েন্ট দিয়ে bKash/Nagad-এ টাকা তুলুন!</p>
        <a href="{{ route('register') }}" class="btn" style="background:white;color:#d97706;font-weight:700;">এখনই রেজিস্টার করুন</a>
    </div>
</section>
@endguest

{{-- Most Downloaded --}}
<section class="section books-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">সর্বাধিক ডাউনলোড</h2>
            <a href="{{ route('books.index') }}?sort=download" class="section-link">সব দেখুন <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="swiper books-swiper">
            <div class="swiper-wrapper">
                @foreach($mostDownloaded as $book)
                <div class="swiper-slide">
                    @include('partials.book-card', ['book' => $book])
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Categories --}}
<section class="section" style="background:var(--bg-2);padding-top:40px;padding-bottom:40px;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">ক্যাটাগরি</h2>
            <a href="{{ route('categories.index') }}" class="section-link">সব ক্যাটাগরি <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="categories-grid">
            @foreach($categories as $cat)
            <a href="{{ route('categories.show', $cat->slug) }}" class="category-card" style="--cat-color:{{ $cat->color }}">
                <div class="cat-icon" style="background:{{ $cat->color }}20;color:{{ $cat->color }}">
                    <i class="{{ $cat->icon }}"></i>
                </div>
                <div class="cat-name">{{ $cat->name }}</div>
                <div class="cat-count">{{ $cat->books_count ?? $cat->books()->count() }} বই</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- New Authors --}}
@if($newAuthors->count())
<section class="section books-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">নতুন লেখক</h2>
            <a href="{{ route('authors.index') }}" class="section-link">সব দেখুন <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="swiper books-swiper">
            <div class="swiper-wrapper">
                @foreach($newAuthors as $author)
                <div class="swiper-slide" style="width:160px">
                    <a href="{{ route('authors.show', $author->slug) }}" style="display:block;text-align:center;padding:16px;">
                        <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin:0 auto 10px;border:3px solid var(--primary);background:var(--bg-2);">
                            <img src="{{ $author->image_url }}" alt="{{ $author->name }}" style="width:100%;height:100%;object-fit:cover">
                        </div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--text)">{{ $author->name }}</div>
                        <div style="font-size:.78rem;color:var(--text-3)">{{ $author->books()->count() }} বই</div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- Category-wise Books --}}
@foreach($categories->take(6) as $cat)
@if(isset($categoryBooks[$cat->id]) && $categoryBooks[$cat->id]->count())
<section class="section books-section" style="{{ $loop->even ? 'background:var(--bg-2);' : '' }}padding-top:35px;padding-bottom:35px;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span style="color:{{ $cat->color }}"><i class="{{ $cat->icon }}"></i></span>
                {{ $cat->name }} বই
            </h2>
            <a href="{{ route('categories.show', $cat->slug) }}" class="section-link">আরো দেখুন <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="books-grid">
            @foreach($categoryBooks[$cat->id] as $book)
                @include('partials.book-card', ['book' => $book])
            @endforeach
        </div>
    </div>
</section>
@endif
@endforeach

@endsection

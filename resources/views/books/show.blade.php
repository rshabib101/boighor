@extends('layouts.app')

@section('title', $book->meta_title ?? $book->title . ' PDF - বইঘর')
@section('meta_description', $book->meta_description ?? 'বইঘরে ' . $book->title . ' বাংলা PDF বিনামূল্যে ডাউনলোড করুন।')
@section('og_image', $book->cover_url)

@section('content')
<div class="container book-detail-section">
    <div class="book-detail-grid">

        {{-- Left: Cover + Actions --}}
        <div class="book-cover-wrap">
            <div class="book-cover-main">
                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
            </div>

            {{-- QR Code --}}
            <div class="book-actions">
                @if($book->pdf_path)
                <a href="{{ route('books.read', $book->slug) }}" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-book-reader"></i> অনলাইনে পড়ুন
                </a>
                <a href="{{ route('books.download', $book->slug) }}?format=pdf" class="btn btn-primary btn-block">
                    <i class="fas fa-download"></i> PDF ডাউনলোড করুন
                </a>
                @else
                <div style="background:var(--bg-2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px;text-align:center;color:var(--text-3);font-size:.88rem;margin-bottom:10px">
                    <i class="fas fa-info-circle"></i> শীঘ্রই PDF ফাইল যুক্ত করা হবে
                </div>
                @endif
                @if($book->epub_path)
                <a href="{{ route('books.download', $book->slug) }}?format=epub" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-file-alt"></i> EPUB ডাউনলোড
                </a>
                @endif
                @if($book->buy_link)
                <a href="{{ $book->buy_link }}" target="_blank" class="btn btn-block" style="background:var(--success);color:white">
                    <i class="fas fa-shopping-cart"></i> বইটি কিনুন
                </a>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div style="display:flex;gap:8px;margin-top:12px">
                <button class="btn btn-sm js-favorite {{ $isFavorited ? 'btn-danger' : 'btn-outline-primary' }}" data-slug="{{ $book->slug }}" style="flex:1">
                    <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart"></i>
                    {{ $isFavorited ? 'ফেভারিট' : 'ফেভারিটে যোগ করুন' }}
                </button>
                <button class="btn btn-sm js-bookmark {{ $isBookmarked ? 'btn-primary' : 'btn-outline-primary' }}" data-slug="{{ $book->slug }}" style="flex:1">
                    <i class="{{ $isBookmarked ? 'fas' : 'far' }} fa-bookmark"></i>
                </button>
                <button class="btn btn-sm btn-outline-primary js-qr-code">
                    <i class="fas fa-qrcode"></i>
                </button>
            </div>

            {{-- Share --}}
            <div class="book-share-btns">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn share-fb"><i class="fab fa-facebook-f"></i></a>
                <a href="https://wa.me/?text={{ urlencode($book->title . ' - ' . url()->current()) }}" target="_blank" class="share-btn share-wa"><i class="fab fa-whatsapp"></i></a>
                <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($book->title) }}" target="_blank" class="share-btn share-tg"><i class="fab fa-telegram-plane"></i></a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($book->title) }}" target="_blank" class="share-btn share-tw"><i class="fab fa-twitter"></i></a>
            </div>
        </div>

        {{-- Right: Info --}}
        <div class="book-info">
            <nav style="font-size:.85rem;color:var(--text-3);margin-bottom:16px">
                <a href="{{ route('home') }}">হোম</a> &rsaquo;
                <a href="{{ route('categories.show', $book->category->slug) }}">{{ $book->category->name }}</a> &rsaquo;
                {{ $book->title }}
            </nav>

            <h1 class="book-info-title">{{ $book->title }}</h1>
            <div class="book-info-author">
                <i class="fas fa-user-edit"></i>
                <a href="{{ $book->author ? route('authors.show', $book->author->slug) : '#' }}" style="color:var(--primary)">
                    {{ $book->author?->name ?? 'অজানা লেখক' }}
                </a>
            </div>

            @if($book->rating > 0)
            <div class="book-rating-display">
                <div class="book-rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="{{ $i <= round($book->rating) ? 'fas' : 'far' }} fa-star"></i>
                    @endfor
                </div>
                <span style="font-weight:700;font-size:1.1rem">{{ number_format($book->rating, 1) }}</span>
                <span class="book-rating-count">({{ $book->total_ratings }} রিভিউ)</span>
                <span class="book-rating-count"> · {{ number_format($book->download_count) }} ডাউনলোড</span>
            </div>
            @endif

            {{-- Metadata Grid --}}
            <div class="book-meta-grid">
                <div class="book-meta-item">
                    <div class="book-meta-label"><i class="fas fa-building"></i> প্রকাশক</div>
                    <div class="book-meta-value">{{ $book->publisher?->name ?? 'অজানা' }}</div>
                </div>
                <div class="book-meta-item">
                    <div class="book-meta-label"><i class="fas fa-language"></i> ভাষা</div>
                    <div class="book-meta-value">{{ $book->language }}</div>
                </div>
                <div class="book-meta-item">
                    <div class="book-meta-label"><i class="fas fa-file-alt"></i> পৃষ্ঠাসংখ্যা</div>
                    <div class="book-meta-value">{{ $book->pages ? number_format($book->pages) : 'N/A' }}</div>
                </div>
                <div class="book-meta-item">
                    <div class="book-meta-label"><i class="fas fa-calendar"></i> প্রকাশের বছর</div>
                    <div class="book-meta-value">{{ $book->publication_year ?? 'N/A' }}</div>
                </div>
                <div class="book-meta-item">
                    <div class="book-meta-label"><i class="fas fa-hdd"></i> ফাইল সাইজ</div>
                    <div class="book-meta-value">{{ $book->file_size_formatted }}</div>
                </div>
                <div class="book-meta-item">
                    <div class="book-meta-label"><i class="fas fa-file-pdf"></i> ফরম্যাট</div>
                    <div class="book-meta-value">{{ $book->file_format }}</div>
                </div>
            </div>

            {{-- Description --}}
            @if($book->description)
            <div class="book-description">
                <h3 style="margin-bottom:10px;font-size:1rem;">বইয়ের বিবরণ</h3>
                <p>{{ $book->description }}</p>
            </div>
            @endif

            {{-- Tags --}}
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px">
                <a href="{{ route('categories.show', $book->category->slug) }}" class="badge-pill badge-primary">
                    <i class="{{ $book->category->icon }}"></i> {{ $book->category->name }}
                </a>
                @if($book->is_featured)
                <span class="badge-pill badge-warning"><i class="fas fa-star"></i> ফিচারড</span>
                @endif
            </div>

            {{-- In-content Ad --}}
            @if($inContentAd)
            <div style="margin-bottom:24px">{!! $inContentAd->ad_code !!}</div>
            @endif

            {{-- Reviews Section --}}
            <div class="reviews-section">
                <h3 style="font-size:1.1rem;margin-bottom:16px;display:flex;align-items:center;gap:8px">
                    <i class="fas fa-comment-alt" style="color:var(--primary)"></i>
                    পাঠকদের মতামত ({{ $book->reviews->count() }})
                </h3>

                {{-- Add Review --}}
                @auth
                <div class="form-card mb-3">
                    <h4 style="margin-bottom:14px;font-size:.95rem">আপনার রিভিউ লিখুন</h4>
                    <form id="reviewForm" data-slug="{{ $book->slug }}">
                        @csrf
                        <input type="hidden" name="rating" id="ratingInput" value="{{ $userReview?->rating ?? 0 }}">
                        <div class="star-rating mb-2" data-selected="{{ $userReview?->rating ?? 0 }}">
                            @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= ($userReview?->rating ?? 0) ? 'active' : '' }}" data-val="{{ $i }}">&#9733;</span>
                            @endfor
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="review" rows="3" placeholder="আপনার মতামত লিখুন (ঐচ্ছিক)...">{{ $userReview?->review }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">রিভিউ জমা দিন</button>
                    </form>
                </div>
                @else
                <div class="form-card mb-3" style="text-align:center;padding:20px">
                    <a href="{{ route('login') }}" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> লগইন করে রিভিউ দিন</a>
                </div>
                @endauth

                {{-- Reviews List --}}
                @forelse($book->reviews->where('is_approved', true)->take(10) as $review)
                <div style="display:flex;gap:12px;padding:16px 0;border-bottom:1px solid var(--border)">
                    <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" style="width:42px;height:42px;border-radius:50%;object-fit:cover;flex-shrink:0">
                    <div style="flex:1">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                            <strong style="font-size:.9rem">{{ $review->user->name }}</strong>
                            <span style="color:var(--accent);font-size:.8rem">
                                @for($i = 1; $i <= 5; $i++)<i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>@endfor
                            </span>
                            <span style="color:var(--text-4);font-size:.78rem">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->review)
                        <p style="color:var(--text-2);font-size:.9rem">{{ $review->review }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-state" style="padding:30px">
                    <i class="far fa-comment-alt"></i>
                    <p>এখনো কোনো রিভিউ নেই। প্রথম রিভিউ লিখুন!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Related Books --}}
    @if($relatedBooks->count())
    <div style="margin-top:50px">
        <div class="section-header">
            <h2 class="section-title">সম্পর্কিত বই</h2>
        </div>
        <div class="books-grid books-grid-wide">
            @foreach($relatedBooks as $book)
                @include('partials.book-card', ['book' => $book])
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- QR Code Modal --}}
<div class="modal-overlay" id="qrModal" style="position:fixed">
    <div class="modal-box" style="position:relative;text-align:center">
        <button class="icon-btn js-close-modal" style="position:absolute;top:12px;right:12px"><i class="fas fa-times"></i></button>
        <h3 style="margin-bottom:16px">QR Code দিয়ে শেয়ার করুন</h3>
        <div style="display:flex;justify-content:center;margin-bottom:16px">
            {!! QrCode::size(200)->generate(url()->current()) !!}
        </div>
        <p style="color:var(--text-3);font-size:.85rem">এই QR Code স্ক্যান করে বইটি শেয়ার করুন</p>
    </div>
</div>
@endsection

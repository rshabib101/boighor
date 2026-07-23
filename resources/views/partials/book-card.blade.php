<a href="{{ route('books.show', $book->slug) }}" class="book-card">
    <div class="book-card-cover">
        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" loading="lazy">
        <span class="book-format-badge">{{ $book->file_format }}</span>
        <button class="book-card-favorite js-favorite {{ auth()->check() && auth()->user()->favorites()->where('book_id', $book->id)->exists() ? 'active' : '' }}"
                data-slug="{{ $book->slug }}"
                onclick="event.preventDefault()">
            <i class="{{ auth()->check() && auth()->user()->favorites()->where('book_id', $book->id)->exists() ? 'fas' : 'far' }} fa-heart"></i>
        </button>
    </div>
    <div class="book-card-body">
        <div class="book-card-title">{{ $book->title }}</div>
        <div class="book-card-author"><i class="fas fa-user-edit fa-xs"></i> {{ $book->author?->name ?? 'অজানা' }}</div>
        @if($book->rating > 0)
        <div class="book-card-rating">
            <span class="stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= round($book->rating) ? 'fas' : 'far' }} fa-star"></i>
                @endfor
            </span>
            <span class="rating-num">{{ number_format($book->rating, 1) }}</span>
        </div>
        @endif
        <div class="book-card-dl"><i class="fas fa-download"></i> {{ number_format($book->download_count) }}</div>
    </div>
</a>

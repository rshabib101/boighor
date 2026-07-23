<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'category_id', 'author_id', 'publisher_id', 'cover_image',
        'description', 'language', 'pages', 'publication_year', 'edition', 'isbn',
        'file_size_mb', 'file_format', 'pdf_path', 'epub_path', 'buy_link', 'affiliate_link',
        'rating', 'total_ratings', 'download_count', 'view_count',
        'is_featured', 'is_sponsored', 'is_active', 'meta_title', 'meta_description'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_sponsored' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'float',
        'file_size_mb' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }

    public function downloads()
    {
        return $this->hasMany(BookDownload::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return asset('images/default-cover.jpg');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size_mb) return 'N/A';
        return $this->file_size_mb >= 1
            ? round($this->file_size_mb, 1) . ' MB'
            : round($this->file_size_mb * 1024) . ' KB';
    }

    public function updateRating(): void
    {
        $avg = $this->reviews()->avg('rating');
        $total = $this->reviews()->count();
        $this->update(['rating' => round($avg, 2), 'total_ratings' => $total]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNewBooks($query, $limit = 12)
    {
        return $query->active()->latest()->limit($limit);
    }

    public function scopePopular($query, $limit = 12)
    {
        return $query->active()->orderBy('view_count', 'desc')->limit($limit);
    }

    public function scopeMostDownloaded($query, $limit = 12)
    {
        return $query->active()->orderBy('download_count', 'desc')->limit($limit);
    }
}

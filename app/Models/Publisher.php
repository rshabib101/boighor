<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'logo', 'description', 'website', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

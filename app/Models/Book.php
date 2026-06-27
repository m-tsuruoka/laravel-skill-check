<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = ['title', 'author', 'category_id', 'price', 'published_at'];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }

    /** 書籍はカテゴリに属する */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

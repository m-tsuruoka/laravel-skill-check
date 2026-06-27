<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name'];

    /** カテゴリは複数の書籍を持つ */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}

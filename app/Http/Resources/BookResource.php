<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'price' => $this->price,
            'published_at' => $this->published_at?->format('Y-m-d'),

            'category_name' => $this->category ? $this->category->name : null, 
        ];
    }
}
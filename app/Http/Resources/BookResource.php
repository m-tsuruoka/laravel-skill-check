<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 必要な項目だけを選んで返す
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            // キャスト設定してある場合は、キャスト後の形で返ります（例：Carbonインスタンスなどから文字列へ変換）
            'published_at' => $this->published_at?->format('Y-m-d'), 
            
            // もしリレーション（カテゴリ）がロードされている場合だけ含めたい場合
            'category' => $this->whenLoaded('category', function() {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),
        ];
    }
}
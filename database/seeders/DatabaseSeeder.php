<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * カテゴリ 4 件 + 書籍 16 件のサンプルデータを投入する。
     */
    public function run(): void
    {
        $categoryNames = ['技術書', '小説', 'ビジネス', 'デザイン'];
        $categories = [];
        foreach ($categoryNames as $name) {
            $categories[] = Category::create(['name' => $name]);
        }

        // 各カテゴリに 4 件ずつ（計 16 件）書籍を作成
        foreach ($categories as $category) {
            Book::factory()
                ->count(4)
                ->create(['category_id' => $category->id]);
        }
    }
}

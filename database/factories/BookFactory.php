<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'        => rtrim($this->faker->sentence(3), '.'),
            'author'       => $this->faker->name(),
            'price'        => $this->faker->numberBetween(1000, 4000),
            'published_at' => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
            // category_id は Seeder 側で指定します
        ];
    }
}

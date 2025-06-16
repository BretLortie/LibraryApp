<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{

    protected $model = \App\Models\Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'publisher' => $this->faker->company(),
            'publicationDate' => $this->faker->date(),
            'category' => $this->faker->randomElement(['Fiction', 'Non-fiction', 'Sci-Fi', 'Fantasy', 'Biography']),
            'ISBN' => $this->faker->unique()->isbn13(),
            'pageCount' => $this->faker->numberBetween(100, 1000),
            'avgRating' => $this->faker->randomFloat(1, 1, 5),
            'coverImage' => 'https://placehold.co/200x300/' .
                ltrim($this->faker->hexColor, '#') . '/' .
                ltrim($this->faker->hexColor, '#') . '?text=' .
                urlencode($this->faker->words(2, true)),

        ];
    }
}

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
            'coverImage' => $this->faker->imageUrl(200, 300, 'books', true),
            'publisher' => $this->faker->company(),
            'publicationDate' => $this->faker->date(),
            'category' => $this->faker->randomElement(['Fiction', 'Non-fiction', 'Sci-Fi', 'Fantasy', 'Biography']),
            'IBSN' => $this->faker->unique()->isbn13(),
            'pageCount' => $this->faker->numberBetween(100, 1000),
            'avgRating' => $this->faker->randomFloat(1, 1, 5),
        ];
    }
}

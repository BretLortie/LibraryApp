<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Library>
 */
class LibraryFactory extends Factory
{
    protected $model = \App\Models\Library::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'availability' => $this->faker->boolean(),
        ];
    }
}

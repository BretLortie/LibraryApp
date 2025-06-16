<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;   // <-- import class
use App\Models\User;   // <-- import class

class ReviewFactory extends Factory
{
    public function definition()
    {
        return [
            'book_id' => Book::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->paragraph(),
        ];
    }
}

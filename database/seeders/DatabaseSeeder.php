<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use App\Models\Transaction;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create(); // creates 10 fake users

        User::factory()->create([ // creates a specific user
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Book::factory(20)->create(); // creates 20 fake books

        Review::factory(50)->create(); // creates 50 fake reviews

        Transaction::factory(30)->create(); // creates 30 fake transactions
    }
}

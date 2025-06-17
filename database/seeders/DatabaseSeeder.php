<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Library;
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
        User::factory(20)->create(); // creates 10 fake users

        User::factory()->create([ // creates a specific user
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Book::factory(30)->create(); // creates 20 fake books

        Review::factory(100)->create(); // creates 50 fake reviews

        Transaction::factory(300)->create(); // creates 30 fake transactions

        // Then, for each book, create a return transaction as the latest one
        $books = Book::all();

        foreach ($books as $book) {
            Transaction::factory()->create([
                'book_id' => $book->id,
                'actionType' => 'return',
                'timestamp' => now(),  // or any recent timestamp
            ]);
        }

        Library::factory(25)->create(); // creates 20 fake library entries
    }
}

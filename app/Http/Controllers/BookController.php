<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('reviews.user')->get();

        return Inertia::render('Books/Index', [
            'books' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'publisher' => $book->publisher,
                    'publicationDate' => $book->publicationDate,
                    'category' => $book->category,
                    'ISBN' => $book->isbn,
                    'pageCount' => $book->pageCount,
                    'reviews' => $book->reviews->map(function ($review) {
                        return [
                            'id' => $review->id,
                            'user' => $review->user->name ?? 'Unknown',
                            'rating' => $review->rating,
                            'description' => $review->description,
                        ];
                    }),
                ];
            }),
        ]);
    }
}

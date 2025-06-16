<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Library;
use Inertia\Inertia;

class FeaturedBooksController extends Controller
{
    public function index()
    {
        $books = Library::with('book:id,title,author,description,coverImage,avgRating') //Pulls 10 random books from the library
            ->inRandomOrder()
            ->take(20)
            ->get();

        return Inertia::render('Books/Featured', [
            'books' => $books,
        ]);
    }
}

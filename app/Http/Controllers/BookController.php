<?php

namespace App\Http\Controllers;

// app/Http/Controllers/BookController.php

use App\Models\Book;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return Inertia::render('Books/Index', [
            'books' => $books
        ]);
    }
}

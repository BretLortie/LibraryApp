<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Library;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Review;


// Im aware this definitely doesnt follow best practices. I just kind of kept throwing more in here because it was easy
// If this was a larger project, I would definitely break this up into multiple controllers

class BookController extends Controller
{
    // List all books
    public function index()
    {
        $books = Book::with('reviews.user')->get();

        return Inertia::render('All_Books/Index', [
            'books' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'publisher' => $book->publisher,
                    'publicationDate' => $book->publicationDate,
                    'category' => $book->category,
                    'ISBN' => $book->ISBN,
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

    // Show form to create a new book
    public function create()
    {
        return Inertia::render('Add_Book/Create');
    }

    // Handle form submission to store new book
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publicationDate' => 'nullable|date',
            'category' => 'nullable|string|max:255',
            'ISBN' => 'nullable|string|max:50',
            'pageCount' => 'nullable|integer|min:1',
        ]);

        Book::create($validated);

        return redirect()->route('books.index') // Redirect to the page with all the books on it
            ->with('success', 'Book added successfully.');
    }

    //Edits the book information
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publicationDate' => 'nullable|date',
            'category' => 'nullable|string|max:255',
            'ISBN' => 'nullable|string|max:20',
            'pageCount' => 'nullable|integer',
        ]);

        $book->update($validated);

        return redirect()->route('books.edit')->with('success', 'Book updated successfully.');
    }

    //Displays the edit page with all books
    public function editPage()
    {
        $books = Book::all();

        return Inertia::render('Edit_Books/Edit', [
            'books' => $books,
        ]);
    }

    //Deletes the book
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.edit') //Come back to this
            ->with('success', 'Book removed successfully.');
    }
}

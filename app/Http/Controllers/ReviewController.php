<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\Library;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Show the review form for a specific book
    public function reviewForm(Book $book)
    {
        return Inertia::render('Review_Books/ReviewForm', [
            'book' => $book,
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : new \stdClass(),
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    // Handle review submission
    public function submitReview(Request $request, Book $book)
    {
        $validated = $request->validate([
            'review' => 'required|string|min:5',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'description' => $validated['review'],
            'rating' => $validated['rating'],
        ]);


        return redirect()->route('books.review.landing')->with('success', 'Review submitted successfully.');
    }

    // Landing page for book reviews
    public function reviewLanding(Request $request)
    {
        $search = $request->input('search', '');

        $books = \App\Models\Book::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('author', 'like', "%$search%");
            })
            ->get(['id', 'title', 'author', 'description']);

        return Inertia::render('Review_Books/ReviewLanding', [
            'books' => $books,
            'filters' => ['search' => $search],
        ]);
    }
}

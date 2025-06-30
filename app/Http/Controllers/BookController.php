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

        return redirect()->route('Add_Book.index')
            ->with('success', 'Book added successfully.');
    }

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

        return redirect()->route('All_Books.index')->with('success', 'Book updated successfully.');
    }

    public function editPage()
    {
        $books = Book::all();

        return Inertia::render('Edit_Books/Edit', [
            'books' => $books,
        ]);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('Edit_Books.editPage') //Come back to this
            ->with('success', 'Book removed successfully.');
    }

    public function availableBooks(Request $request)
    {
        $search = $request->input('search');

        $query = Book::query()
            ->join('library', 'books.id', '=', 'library.book_id')
            ->where('library.availability', 1)
            ->select('books.id', 'books.title', 'books.author', 'books.description');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('books.title', 'like', "%{$search}%")
                    ->orWhere('books.author', 'like', "%{$search}%");
            });
        }

        $availableBooks = $query->get();

        return Inertia::render('Book_Checkout/Checkout', [
            'books' => $availableBooks,
            'filters' => ['search' => $search],
        ]);
    }

    public function checkoutPage(Request $request)
    {
        return $this->availableBooks($request);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = Auth::user();

        // Create a checkout transaction
        Transaction::create([
            'user_id' => $user->id,
            'book_id' => $validated['book_id'],
            'actionType' => 'checkout',
            'timestamp' => now(),
        ]);

        // Update availability in the library table
        Library::where('book_id', $validated['book_id'])
            ->update(['availability' => 0]);

        return redirect()->back()->with('success', 'Book checked out successfully.');
    }

    public function returnPage()
    {
        // Subquery: latest checkout transaction per book
        $latestCheckouts = DB::table('transactions as t2')
            ->select('book_id', DB::raw('MAX(id) as latest_id'))
            ->where('actionType', 'checkout')
            ->groupBy('book_id');

        $books = DB::table('library')
            ->where('availability', 0)
            ->join('books', 'library.book_id', '=', 'books.id')
            ->joinSub($latestCheckouts, 'latest', function ($join) {
                $join->on('library.book_id', '=', 'latest.book_id');
            })
            ->join('transactions', 'transactions.id', '=', 'latest.latest_id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select(
                'library.id as library_id',
                'books.id as book_id',
                'books.title',
                'books.author',
                'users.name as checked_out_by',
                'transactions.timestamp as checked_out_at'
            )
            ->orderBy('transactions.timestamp', 'desc')
            ->get();

        return Inertia::render('Return_Books/Return', [
            'books' => $books,
        ]);
    }
    public function returnBook(Request $request)
    {
        $validated = $request->validate([
            'library_id' => 'required|integer|exists:library,id',
            'book_id' => 'required|integer|exists:books,id',
        ]);

        DB::table('library')
            ->where('id', $validated['library_id'])
            ->update(['availability' => 1]);

        Transaction::create([
            'user_id' => Auth::id(),
            'book_id' => $validated['book_id'],
            'actionType' => 'return',
            'timestamp' => now(),
        ]);

        return redirect()->route('Return_Books.return')->with('success', 'Book returned successfully.');
    }

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


        return redirect()->route('Review_Books.ReviewLanding')->with('success', 'Review submitted successfully.');
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

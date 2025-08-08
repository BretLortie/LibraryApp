<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\Library;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    //Lists all available books for checkout
    public function checkoutPage(Request $request)
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
}

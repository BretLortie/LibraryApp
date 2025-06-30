<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\Library;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnsController extends Controller
{
    //Displays the page to return books
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

    //Retiurns the book
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

        return redirect()->route('books.return')->with('success', 'Book returned successfully.');
    }
}

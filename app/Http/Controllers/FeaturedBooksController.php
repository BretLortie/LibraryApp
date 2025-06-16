<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Library;

class FeaturedBooksController extends Controller
{
    public function index(Request $request)
    {
        $query = Library::with('book');

        // Filter by availability (0 or 1)
        if ($request->has('availability')) {
            $query->where('availability', $request->input('availability'));
        }

        // Join with books table for sorting purposes
        $query->join('books', 'library.book_id', '=', 'books.id')
            ->select('library.*'); // select only library table columns

        // Apply sorting
        if ($request->filled('sort_by')) {
            $direction = $request->input('direction', 'asc');
            $column = match ($request->input('sort_by')) {
                'title', 'author', 'avgRating' => 'books.' . $request->input('sort_by'),
                default => null,
            };

            if ($column) {
                $query->orderBy($column, $direction);
            }
        } else {
            $query->inRandomOrder(); // fallback to random order
        }

        $books = $query->get();

        return inertia('Books/Featured', [
            'books' => $books->map(function ($entry) {
                return [
                    'availability' => $entry->availability,
                    'book' => [
                        'title' => $entry->book->title,
                        'author' => $entry->book->author,
                        'description' => $entry->book->description,
                        'coverImage' => $entry->book->coverImage,
                        'avgRating' => $entry->book->avgRating,
                    ],
                ];
            }),
        ]);
    }
}

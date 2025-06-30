<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FeaturedBooksController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); 

// Page with all books that anyone can access
Route::get('/All_Books/Index', [BookController::class, 'index'])->name('books.index'); // All books page

// Routes accessible by authenticated users (any role)
Route::middleware(['auth'])->group(function () {
    Route::get('/Featured_Books/Featured', [FeaturedBooksController::class, 'index'])->name('featured.books'); // Featured books page

    //Customer Only Routes
    Route::middleware('role:admin|customer')->group(function () {
        Route::get('/Book_Checkout/checkout', [BookController::class, 'checkoutPage'])->name('books.checkout'); // Checkout page
        Route::post('/Book_Checkout/checkout', [BookController::class, 'checkout'])->name('books.checkout.store'); // Handles the checkout of a book
        Route::get('/Review_Books/ReviewLanding', [BookController::class, 'reviewLanding'])->name('books.review.landing'); // Review landing page
        Route::get('/Review_Books/{book}/ReviewLanding', [BookController::class, 'reviewForm'])->name('books.review.form'); // Page to review a book
        Route::post('/Review_Books/{book}/ReviewLanding', [BookController::class, 'submitReview'])->name('books.review.submit'); // Handles the submission of a review
    });

    //Librarian only routes
    Route::middleware(['auth', 'role:admin|librarian'])->group(function () {
        Route::get('/Add_Book/create', [BookController::class, 'create'])->name('books.create'); //Add book page
        Route::post('', [BookController::class, 'store'])->name('books.store'); // creates the new book
        Route::get('/Edit_Books/edit', [BookController::class, 'editPage'])->name('books.edit'); //Edit book page
        Route::put('/Edit_Books/{book}', [BookController::class, 'update'])->name('books.update'); // updates the book
        Route::delete('/Edit_Books/{book}', [BookController::class, 'destroy'])->name('books.destroy'); // deletes the book
        Route::get('/Return_Books/return', [BookController::class, 'returnPage'])->name('books.return.page'); // Return book page
        Route::post('/Return_Books/return', [BookController::class, 'returnBook'])->name('books.return'); // Handles the return of a book
    });
});

Route::middleware(['auth', 'role:admin'])->get('/test-role-middleware', function () {
    return 'Role middleware works';
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

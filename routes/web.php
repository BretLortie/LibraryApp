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

// Public book listing
//Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/All_Books/Index', [BookController::class, 'index'])->name('books.index');

// Routes accessible by authenticated users (any role)
Route::middleware(['auth'])->group(function () {
    //Route::get('/featured-books', [FeaturedBooksController::class, 'index'])->name('featured.books');
    Route::get('/Featured_Books/Featured', [FeaturedBooksController::class, 'index'])->name('featured.books');

    // Customer-only routes
    // Route::middleware('role:admin|customer')->group(function () {
    //     Route::get('/books/checkout', [BookController::class, 'checkoutPage'])->name('books.checkout');
    //     Route::post('/books/checkout', [BookController::class, 'checkout'])->name('books.checkout.store');
    //     Route::get('/books/review', [BookController::class, 'reviewLanding'])->name('books.review.landing');
    //     Route::get('/books/{book}/review', [BookController::class, 'reviewForm'])->name('books.review.form');
    //     Route::post('/books/{book}/review', [BookController::class, 'submitReview'])->name('books.review.submit');
    // });

    Route::middleware('role:admin|customer')->group(function () {
        Route::get('/Book_Checkout/checkout', [BookController::class, 'checkoutPage'])->name('books.checkout');
        Route::post('/Book_Checkout/checkout', [BookController::class, 'checkout'])->name('books.checkout.store');
        Route::get('/Review_Books/ReviewLanding', [BookController::class, 'reviewLanding'])->name('books.review.landing');
        Route::get('/Review_Books/{book}/ReviewLanding', [BookController::class, 'reviewForm'])->name('books.review.form');
        Route::post('/Review_Books/{book}/ReviewLanding', [BookController::class, 'submitReview'])->name('books.review.submit');
    });

    //Librarian only routes
    // Route::middleware(['auth', 'role:admin|librarian'])->group(function () {
    //     Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    //     Route::post('/books', [BookController::class, 'store'])->name('books.store');
    //     Route::get('/books/edit', [BookController::class, 'editPage'])->name('books.editPage');
    //     Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    //     Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    //     Route::get('/books/return', [BookController::class, 'returnPage'])->name('books.return.page');
    //     Route::post('/books/return', [BookController::class, 'returnBook'])->name('books.return');
    // });

    Route::middleware(['auth', 'role:admin|librarian'])->group(function () {
        Route::get('/Add_Book/create', [BookController::class, 'create'])->name('books.create');
        Route::post('', [BookController::class, 'store'])->name('books.store');
        Route::get('/Edit_Books/edit', [BookController::class, 'editPage'])->name('books.editPage');
        Route::put('/Edit_Books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/Edit_Books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        Route::get('/Return_Books/return', [BookController::class, 'returnPage'])->name('books.return.page');
        Route::post('/Return_Books/return', [BookController::class, 'returnBook'])->name('books.return');
    });
});

Route::middleware(['auth', 'role:admin'])->get('/test-role-middleware', function () {
    return 'Role middleware works';
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

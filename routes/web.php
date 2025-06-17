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

Route::get('/books', [BookController::class, 'index'])->name('books.index'); //Books page
Route::get('/books/create', [BookController::class, 'create'])->name('books.create'); //Web form to create a new book
Route::post('/books', [BookController::class, 'store'])->name('books.store'); //Creates the new book
Route::get('/books/edit', [BookController::class, 'editPage'])->name('books.editPage'); //For editing a book
Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update'); //Updates the book details
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy'); 
Route::get('/featured-books', [FeaturedBooksController::class, 'index'])->name('featured.books'); //Featured books page
Route::get('/books/checkout', [BookController::class, 'checkoutPage'])->name('books.checkout');
Route::post('/books/checkout', [BookController::class, 'checkout'])->name('books.checkout.store');
Route::get('/books/return', [BookController::class, 'returnPage'])->name('books.return.page');
Route::post('/books/return', [BookController::class, 'returnBook'])->name('books.return');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

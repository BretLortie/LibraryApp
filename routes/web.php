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

Route::get('/featured-books', [FeaturedBooksController::class, 'index'])->name('featured.books'); //Featured books page


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

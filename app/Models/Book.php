<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Book extends Model
{

    use HasFactory;

    // Specify which attributes can be mass-assigned
    protected $fillable = [
        'title',
        'author',
        'description',
        'coverImage',
        'publisher',
        'publicationDate',
        'category',
        'ISBN',
        'pageCount',
        'avgRating',
    ];

    /**
     * Get the library inventory entry for this book.
     */
    public function library(): HasOne
    {
        return $this->hasOne(Library::class, 'book_id');
    }

    /**
     * Get all reviews for this book.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all transactions involving this book.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}

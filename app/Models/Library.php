<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Library extends Model
{

    use HasFactory;

    protected $table = 'library';

    // Fields you can mass assign
    protected $fillable = [
        'book_id',
        'availability',
    ];

    /**
     * Get the book that this library inventory entry refers to.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}

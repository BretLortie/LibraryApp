<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{

    use HasFactory;

    public $timestamps = false;

    //
    protected $fillable = [
        'user_id',
        'book_id',
        'actionType',
        'timestamp',
    ];

    /**
     * Get the user who made the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book involved in the transaction.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}

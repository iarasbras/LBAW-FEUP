<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    /**
     * The table backing this model uses a singular name and a custom key.
     */
    protected $table = 'book';

    protected $primaryKey = 'book_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'author',
        'price',
        'language',
        'synopsis',
        'category_name',
        'seller_id',
        'available_stock',
        'image',
    ];

    /**
     * Seller account (user) that owns the book listing.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }

    /**
     * Category associated with the book.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_name', 'category_name');
    }

    /**
     * Customer reviews associated with this book.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'book_id', 'book_id');
    }

    /**
     * Users who have this book in their wishlist.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(\App\Models\Wishlist::class, 'book_id', 'book_id');
    }
}


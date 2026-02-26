<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    // Table already exists in the database as `shopping_cart` per project SQL
    protected $table = 'shopping_cart';

    // The table uses `cart_id` as the primary key (serial).
    protected $primaryKey = 'cart_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'book_id',
        'quantity',
    ];

    // Let Eloquent know `cart_id` is auto-incrementing.
    public $incrementing = true;
}

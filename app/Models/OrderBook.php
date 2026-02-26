<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderBook extends Model
{
    protected $table = 'order_book';

    public $incrementing = false;
    protected $primaryKey = null; 

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'book_id', 
        'unit_price_at_purchase',
        'quantity'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
}
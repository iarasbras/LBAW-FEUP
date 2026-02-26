<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Payment;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'payment_id',
        'total_price',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderBook::class, 'order_id', 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'order_book', 'order_id', 'book_id')
                    ->withPivot('quantity', 'unit_price_at_purchase');
    }    
}

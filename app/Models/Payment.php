<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = [
        'payment_method',
        'amount',
    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'payment_id', 'payment_id');
    }
}

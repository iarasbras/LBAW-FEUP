<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seller extends Model
{
    protected $table = 'seller';

    protected $primaryKey = 'seller_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['seller_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }
}
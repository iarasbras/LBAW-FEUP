<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'category';

    protected $primaryKey = 'category_name';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'category_name',
    ];

    /**
     * Books that belong to this category.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'category_name', 'category_name');
    }
}


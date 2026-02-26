<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Order;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users'; // (Opcional, mas boa prática confirmar)
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'profile_img_url',
        'is_blocked',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
            'is_blocked' => 'boolean',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'user_id', 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    public function unreadNotifications(): HasMany
    {
        return $this->notifications()->where('is_read', false);
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'seller_id');
    }

    public function sellerAccount()
    {
        return $this->hasOne(Seller::class, 'seller_id', 'user_id');
    }
}
<?php

namespace App\Models;

class WishlistedOnSaleNotification extends Notification
{
    protected $attributes = [
        'type' => 'WishlistedOnSaleNotification',
        'is_read' => false,
    ];

    /**
     * Boot method para definir automaticamente o tipo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'WishlistedOnSaleNotification';
        });
    }

    /**
     * Retorna mensagem formatada para livro da wishlist em promoção
     */
    public function getFormattedMessage()
    {
        if ($this->book) {
            $bookName = $this->book->name;
            $newPrice = number_format($this->book->price, 2, ',', ' ');
            return "Boa notícia! '{$bookName}' da tua wishlist baixou de preço e está agora a {$newPrice} €!";
        }
        
        return "Um livro da tua lista de desejos teve uma redução de preço.";
    }
}

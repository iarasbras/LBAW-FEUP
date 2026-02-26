<?php

namespace App\Models;

class CartPriceNotification extends Notification
{
    protected $attributes = [
        'type' => 'CartPriceNotification',
        'is_read' => false,
    ];

    /**
     * Boot method para definir automaticamente o tipo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'CartPriceNotification';
        });
    }

    /**
     * Retorna mensagem formatada para mudança de preço no carrinho
     */
    public function getFormattedMessage()
    {
        if ($this->book) {
            $bookName = $this->book->name;
            $newPrice = number_format($this->book->price, 2, ',', ' ');
            return "Atenção! O preço de '{$bookName}' no teu carrinho mudou para {$newPrice} €.";
        }
        
        return "O preço de um item no teu carrinho foi alterado.";
    }
}

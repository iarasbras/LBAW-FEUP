<?php

namespace App\Models;

class OrderStatusNotification extends Notification
{
    protected $attributes = [
        'type' => 'OrderStatusNotification',
        'is_read' => false,
    ];

    /**
     * Boot method para definir automaticamente o tipo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'OrderStatusNotification';
        });
    }

    /**
     * Retorna mensagem formatada para mudança de estado da encomenda
     */
    public function getFormattedMessage()
    {
        if ($this->order) {
            $orderId = $this->order->order_id;
            return "O estado da tua encomenda #{$orderId} foi atualizado.";
        }
        
        return "O estado de uma encomenda foi atualizado.";
    }
}

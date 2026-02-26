<?php

namespace App\Models;

class PaymentApprovedNotification extends Notification
{
    protected $attributes = [
        'type' => 'PaymentApprovedNotification',
        'is_read' => false,
    ];

    /**
     * Boot method para definir automaticamente o tipo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'PaymentApprovedNotification';
        });
    }

    /**
     * Retorna mensagem formatada para pagamento aprovado
     */
    public function getFormattedMessage()
    {
        if ($this->order) {
            $orderId = $this->order->order_id;
            $total = number_format($this->order->total_price, 2, ',', ' ');
            return "Pagamento aprovado! A tua encomenda #{$orderId} ({$total} €) foi processada com sucesso.";
        }
        
        return "O teu pagamento foi aprovado com sucesso!";
    }
}

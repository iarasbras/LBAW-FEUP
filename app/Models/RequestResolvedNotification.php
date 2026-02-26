<?php

namespace App\Models;

class RequestResolvedNotification extends Notification
{
    protected $attributes = [
        'type' => 'RequestResolvedNotification',
        'is_read' => false,
    ];

    /**
     * Boot method para definir automaticamente o tipo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'RequestResolvedNotification';
        });
    }

    /**
     * Retorna mensagem formatada para pedido resolvido
     */
    public function getFormattedMessage()
    {
        return $this->message ?? "O teu pedido de suporte foi resolvido.";
    }

    /**
     * Criar notificação de pedido resolvido
     */
    public static function create(array $attributes = [])
    {
        $attributes['type'] = 'RequestResolvedNotification';
        
        if (!isset($attributes['message'])) {
            $attributes['message'] = "O teu pedido de suporte foi resolvido por um administrador.";
        }
        
        return parent::create($attributes);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'notification_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'date',
        'is_read',
        'order_id',
        'book_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'date' => 'datetime',
    ];

    protected $attributes = [
        'is_read' => false,
    ];

    /**
     * Relacionamento com User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relacionamento com Order (para OrderStatusNotification)
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * Relacionamento com Book (para WishlistedOnSaleNotification e CartPriceNotification)
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    /**
     * Single Table Inheritance: retorna a instância correta baseada no tipo
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $attributes = (array) $attributes;

        if (isset($attributes['type'])) {
            $class = 'App\\Models\\' . $attributes['type'];
            
            if (class_exists($class) && $class !== self::class) {
                $instance = new $class;
                $instance->exists = true;
                $instance->setRawAttributes($attributes, true);
                $instance->setConnection($connection ?: $this->getConnectionName());
                $instance->fireModelEvent('retrieved', false);
                return $instance;
            }
        }

        return parent::newFromBuilder($attributes, $connection);
    }

    /**
     * Marcar como lida
     */
    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();
    }

    /**
     * Scope para notificações não lidas
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope para notificações de um utilizador
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Método abstrato para obter mensagem formatada
     * (sobrescrito nas subclasses)
     */
    public function getFormattedMessage()
    {
        return $this->message ?? 'Nova notificação';
    }
}

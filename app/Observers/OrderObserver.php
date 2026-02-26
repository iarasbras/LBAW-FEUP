<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatusNotification;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     * Dispara notificação quando o estado da encomenda é alterado.
     */
    public function updated(Order $order)
    {
        // Verificar se o status/estado foi alterado
        if ($order->isDirty('status') && $order->getOriginal('status') !== null) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            // Apenas notificar se o estado mudou de facto
            if ($oldStatus !== $newStatus) {
                OrderStatusNotification::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->order_id,
                    'date' => now(),
                    'message' => "Estado da encomenda #{$order->order_id} atualizado.",
                ]);
            }
        }
    }
}

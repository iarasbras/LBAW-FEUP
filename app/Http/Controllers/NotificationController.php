<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Listar todas as notificações do utilizador autenticado
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        if (Auth::user()->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'A sua conta encontra-se bloqueada.',
            ], 403);
        }
        $showAll = (bool) $request->boolean('show_all');

        $query = Notification::forUser(Auth::id())
            ->orderBy('date', 'desc')
            ->with(['book', 'order']);

        // Por defeito, mostrar apenas não lidas; se show_all=1, mostrar todas
        if (!$showAll) {
            $query->unread();
        }

        $notifications = $query->paginate(20)->appends(['show_all' => $showAll ? 1 : 0]);

        return view('pages.notifications', compact('notifications', 'showAll'));
    }

    /**
     * Obter notificações não lidas (para AJAX/badge)
     */
    public function unread()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0, 'notifications' => []]);
        }

        $notifications = Notification::forUser(Auth::id())
            ->unread()
            ->orderBy('date', 'desc')
            ->with(['book', 'order'])
            ->limit(10)
            ->get();

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->notification_id,
                    'message' => $n->getFormattedMessage(),
                    'date' => $n->date->diffForHumans(),
                    'type' => $n->type,
                ];
            }),
        ]);
    }

    /**
     * Marcar uma notificação como lida
     */
    public function markAsRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Não autenticado'], 401);
        }

        $notification = Notification::forUser(Auth::id())
            ->where('notification_id', $id)
            ->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notificação não encontrada'], 404);
        }

        $notification->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notificação marcada como lida');
    }

    /**
     * Marcar todas as notificações como lidas
     */
    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Não autenticado'], 401);
        }

        Notification::forUser(Auth::id())
            ->unread()
            ->update(['is_read' => true]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Todas as notificações foram marcadas como lidas');
    }
}

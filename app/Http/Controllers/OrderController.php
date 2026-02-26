<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function history(): View
    {
        if (Auth::user()->is_blocked) {
            abort(403, 'A sua conta encontra-se bloqueada.');
        }
        // 1. Busca as encomendas do utilizador logado (Auth::user()).
        // 2. Com 'with('books')', carrega os itens de cada encomenda (evita o problema N+1).
        $orders = Auth::user()
                    ->orders()
                    ->with('books') 
                    ->latest('date') // Mais recentes primeiro
                    ->get();

        // 3. Envia os dados para a View
        return view('pages.order_history', compact('orders'));
    }
}
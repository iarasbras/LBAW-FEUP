@extends('layouts.app')

@section('title', 'Histórico de Compras')

@push('styles')
<style>
    body.profile-page {
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
    }

    body.profile-page main {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
    }

    body.profile-page #content {
        width: 100%;
        max-width: 720px;
    }

    .orders-card {
        background: rgba(17, 24, 39, 0.9);
        border-radius: 26px;
        padding: 3rem;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45);
    }

    .profile-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .avatar {
        width: 120px;
        height: 120px;
        border-radius: 999px;
        margin: 0 auto 1rem;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.25);
        background: rgba(255, 255, 255, 0.08);
    }

    .back-link {
        border-radius: 999px;
        padding: 0.6rem 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
    }

    .order-item {
        background: rgba(255, 255, 255, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .order-item h4 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #818cf8;
    }

    .order-item p {
        font-size: 1rem;
        margin-top: 0.5rem;
        color: #d1d5db;
    }

    .order-item .order-items-list {
        margin-top: 1rem;
        list-style-type: none;
        padding: 0;
    }

    .order-item .order-items-list li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        color: #e5e7eb;
    }

    .order-item .order-items-list li span {
        color: #34d399;
    }

    .order-item .total-price {
        margin-top: 1rem;
        font-weight: 700;
        font-size: 1.25rem;
        color: #818cf8;
    }

    .no-orders-message {
        text-align: center;
        color: #d1d5db;
        font-size: 1.2rem;
        margin-top: 2rem;
    }

    .button-link {
        border-radius: 999px;
        padding: 0.6rem 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
        background-color: #6366f1;
        transition: background-color 0.3s ease;
    }

    .button-link:hover {
        background-color: #4f46e5;
    }
</style>
@endpush

@section('content')
<div class="orders-card">
    
    {{-- 1. Navegação e Título --}}
    <div class="profile-nav">
        <a class="back-link" href="{{ route('profile.show') }}">← Voltar ao Perfil</a>
        <h2 style="font-size: 1.5rem; font-weight: 600; margin: 0 auto; color: #a5b4fc;">Histórico de Compras</h2>
        <a class="back-link" href="{{ route('logout') }}">Terminar sessão</a>
    </div>

    {{-- 2. Lista de Encomendas --}}
    @forelse ($orders as $order)
        <div class="order-item">
            
            {{-- Cabeçalho da Encomenda (ID e Data) --}}
            <div class="flex justify-between items-center pb-2 border-b border-gray-600 mb-3">
                <h4 class="font-bold text-lg text-white">Encomenda #{{ $order->order_id }}</h4>
                
                <span class="text-sm text-gray-400">
                    Data: {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}
                </span>
            </div>
            
            {{-- Status 
            <p class="text-sm text-gray-300 mb-2">
                Status: 
                <span class="font-bold text-indigo-400 uppercase tracking-wider">
                    {{ $order->status ?? 'Pendente' }}
                </span>
            </p>--}}

            {{-- Lista de Livros --}}
            <ul class="order-items-list">
                @foreach ($order->books as $book)
                    <li>
                        <span>{{ $book->pivot->quantity }}x</span>
                        {{ $book->name }}
                        <span>{{ number_format($book->pivot->unit_price_at_purchase, 2) }} €/un</span>
                    </li>
                @endforeach
            </ul>

            {{-- Total --}}
            <p class="total-price">Total Pago: {{ number_format($order->total_price, 2) }} €</p>
        </div>
    @empty
        <p class="no-orders-message">Ainda não tens encomendas registadas neste momento.</p>
    @endforelse
</div>
@endsection

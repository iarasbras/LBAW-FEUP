@extends('layouts.app')

@section('title', 'Histórico de Vendas')

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
        max-width: 800px; /* Ligeiramente mais largo para caber o nome do comprador */
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

    .back-link {
        border-radius: 999px;
        padding: 0.6rem 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.2s;
    }
    .back-link:hover { background: rgba(255,255,255,0.1); }

    .order-item {
        background: rgba(255, 255, 255, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255,255,255,0.05);
    }

    /* Header da Encomenda */
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 0.8rem;
        margin-bottom: 1rem;
    }

    .order-header h4 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #818cf8;
        margin: 0;
    }

    .buyer-info {
        font-size: 1rem;
        color: #e5e7eb;
        margin-top: 0.2rem;
    }
    
    .buyer-badge {
        background: rgba(99, 102, 241, 0.2);
        color: #c7d2fe;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .order-date {
        font-size: 0.9rem;
        color: #9ca3af;
    }

    .order-items-list {
        margin-top: 1rem;
        list-style-type: none;
        padding: 0;
    }

    .order-items-list li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        color: #e5e7eb;
    }

    .order-items-list li span {
        color: #34d399;
        font-weight: 500;
    }

    .total-price {
        margin-top: 1rem;
        font-weight: 700;
        font-size: 1.25rem;
        color: #818cf8;
        text-align: right;
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 1rem;
    }

    .no-orders-message {
        text-align: center;
        color: #d1d5db;
        font-size: 1.2rem;
        margin-top: 2rem;
    }
</style>
@endpush

@section('content')
<div class="orders-card">
    
    {{-- 1. Navegação e Título --}}
    <div class="profile-nav">
        {{-- Link volta para o Dashboard de Vendedor --}}
        <a class="back-link" href="{{ route('seller.dashboard') }}">← Voltar ao Painel</a>
        <h2 style="font-size: 1.5rem; font-weight: 600; margin: 0 auto; color: #a5b4fc;">Vendas Realizadas</h2>
        <div style="width: 140px;"></div> {{-- Espaçador para centrar o título --}}
    </div>

    {{-- 2. Lista de Vendas --}}
    @forelse ($sales as $orderId => $items)
        @php
            // Como agrupámos por OrderID, os dados da encomenda são iguais para todos os items
            $orderInfo = $items[0]; 
            // Calcular o total ganho nesta encomenda específica (só os meus livros)
            $totalEarned = $items->sum(fn($item) => $item->quantity * $item->unit_price_at_purchase);
        @endphp

        <div class="order-item">
            
            {{-- Cabeçalho da Encomenda --}}
            <div class="order-header">
                <div>
                    <h4>Encomenda #{{ $orderId }}</h4>
                    <div class="buyer-info">
                        Comprador: <span class="buyer-badge">{{ $orderInfo->buyer_name }}</span>
                    </div>
                </div>
                <div class="order-date">
                    {{ \Carbon\Carbon::parse($orderInfo->date)->format('d/m/Y') }}
                </div>
            </div>

            {{-- Lista de Livros Vendidos nesta Encomenda --}}
            <ul class="order-items-list">
                @foreach ($items as $item)
                    <li>
                        <div style="display:flex; gap:0.5rem;">
                            <span style="color: #9ca3af;">{{ $item->quantity }}x</span>
                            {{ $item->book_name }}
                        </div>
                        <span>{{ number_format($item->unit_price_at_purchase, 2) }} €</span>
                    </li>
                @endforeach
            </ul>

            {{-- Total Ganho --}}
            <p class="total-price">Total Ganho: {{ number_format($totalEarned, 2) }} €</p>
        </div>
    @empty
        <div class="no-orders-message">
            <p>Ainda não efetuaste nenhuma venda.</p>
        </div>
    @endforelse
</div>
@endsection
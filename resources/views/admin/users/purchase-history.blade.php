@extends('layouts.app')

@section('title', 'Liberato · Administração · Histórico de Compras')
@section('body-class', 'admin-page')

@push('styles')
<style>
    body.admin-page {
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        font-size: 1.4rem;
        min-height: 100vh;
        margin: 0;
    }

    .admin-shell {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 0 1.5rem;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: rgba(17, 24, 39, 0.8);
        padding: 2rem;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .admin-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .admin-header .meta {
        color: #9ca3af;
        font-size: 1.2rem;
        margin-top: 0.4rem;
    }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.9rem 1.4rem;
        background: rgba(255,255,255,0.08);
        color: #f9fafb;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.12);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-ghost:hover { background: rgba(255,255,255,0.14); }

    .orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .order-card {
        background: rgba(17, 24, 39, 0.65);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 14px;
        padding: 1.4rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    }

    .order-top {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.8rem;
        align-items: start;
        margin-bottom: 1rem;
    }

    .order-title { font-weight: 700; font-size: 1.2rem; }
    .order-meta { color: #9ca3af; font-size: 1.1rem; }

    .status-badge {
        padding: 0.35rem 0.9rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        justify-content: center;
    }
    .status-completed { background: rgba(16,185,129,0.2); color: #34d399; }
    .status-pending { background: rgba(251,146,60,0.2); color: #fb923c; }

    .order-total { font-size: 1.3rem; font-weight: 800; color: #22c55e; text-align: right; }

    .books-box {
        background: rgba(255,255,255,0.03);
        border: 1px dashed rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 1rem;
    }

    .books-box p { margin: 0 0 0.6rem 0; color: #e5e7eb; font-weight: 600; }

    .books-list {
        margin: 0;
        padding-left: 1.3rem;
        color: #d1d5db;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: rgba(17,24,39,0.6);
        border: 2px dashed rgba(255,255,255,0.08);
        border-radius: 14px;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')
<div class="admin-shell">
    <div class="admin-header">
        <div>
            <h1>Histórico de Compras</h1>
            <div class="meta">{{ $user->username }} · {{ $user->email }}</div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-ghost">← Voltar</a>
    </div>

    @if(count($orders) > 0)
        <div class="orders-grid">
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-top">
                        <div>
                            <div class="order-title">Order #{{ $order['order_id'] }}</div>
                            <div class="order-meta">{{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}</div>
                        </div>
                        <div style="text-align: right;">
                            <span class="status-badge {{ $order['status'] === 'Completado' ? 'status-completed' : 'status-pending' }}">{{ $order['status'] }}</span>
                            <div class="order-total">€{{ number_format($order['total_price'], 2, ',', '.') }}</div>
                        </div>
                    </div>

                    @if(count($order['books']) > 0)
                        <div class="books-box">
                            <p>Livros comprados</p>
                            <ul class="books-list">
                                @foreach($order['books'] as $book)
                                    <li>{{ $book['title'] }} — Qtd: {{ $book['quantity'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p style="color: #9ca3af; margin: 0;">Sem livros registados nesta compra.</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <p>Este utilizador não tem compras registadas.</p>
        </div>
    @endif
</div>
@endsection

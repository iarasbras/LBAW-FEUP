@extends('layouts.app')

@section('title', 'Liberato · Carrinho')
@section('body-class', 'cart-page')

@push('styles')
<style>
    body.cart-page {
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
    }

    body.cart-page main {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
    }

    body.cart-page #content {
        width: 100%;
        max-width: 980px;
    }

    .cart-shell {
        background: rgba(17, 24, 39, 0.9);
        border-radius: 26px;
        padding: 2.25rem;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45);
    }

    .cart-hero {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:1rem;
        margin-bottom:1.5rem;
    }

    .cart-hero h1 { margin:0; font-size:2.5rem; letter-spacing: -0.5px; }

    .cart-items { display:flex; flex-direction:column; gap:1rem; }

    .cart-item {
        display: grid;
        grid-template-columns: 1fr 140px 180px 140px 110px; /* meta / price / qty / subtotal / actions */
        align-items: center;
        column-gap: 1rem;
        padding: 1rem;
        border-radius: 16px;
        background: linear-gradient(165deg, rgba(31,41,55,0.85), rgba(17,24,39,0.85));
        border: 1px solid rgba(255,255,255,0.04);
        box-sizing: border-box;
    }

    .cart-item__meta { grid-column: 1; min-width: 0; }
    .cart-item__meta h3 { margin:0 0 0.25rem 0; }
    .cart-item__meta .author { color:#d1d5db; font-size:0.95rem; margin:0; }

    .cart-item__price { grid-column: 2; text-align:right; font-weight:700; }

    .cart-item__qty { grid-column: 3; display:flex; gap:0.5rem; align-items:center; justify-content:center; }
    
    /* --- CORREÇÃO DE CSS PARA O INPUT --- */
    .cart-item__qty form {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0;
    }

    .cart-item__qty input[type="number"] { 
        width: 70px !important;
        height: 38px !important;
        padding: 0.45rem !important;
        border-radius: 8px; 
        border: 1px solid rgba(255,255,255,0.08); 
        background: transparent; 
        color: inherit; 
        margin: 0 !important;
        display: inline-block !important;
    }
    
    .cart-item__qty button {
        margin: 0 !important;
    }
    /* ----------------------------------- */

    .cart-item__subtotal { grid-column: 4; text-align:right; font-weight:600; }

    .cart-actions { grid-column: 5; justify-self: end; display:flex; gap:0.5rem; align-items:center; }
    .book-card__link, .logout-link, .back-link { text-decoration:none; color:inherit; }

    /* Make cart actions and update/remove controls match site buttons and fit properly */
    .book-card__link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.12);
        padding: 0.45rem 0.9rem;
        text-decoration: none;
        font-weight: 600;
        color: inherit;
        background: transparent;
        transition: transform 0.12s ease, box-shadow 0.12s ease;
        cursor: pointer;
    }

    .book-card__link:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(99,102,241,0.12);
    }

    .cart-actions .book-card__link,
    .cart-item__qty form > .book-card__link {
        padding: 0.35rem 0.7rem;
        font-size: 0.95rem;
    }

    .cart-actions .book-card__link.remove {
        /* darker, slightly muted danger tone */
        background: linear-gradient(120deg, #7f1d1d, #9b111e);
        color: #fff;
        border-color: rgba(155,17,30,0.18);
        box-shadow: 0 10px 24px rgba(155,17,30,0.12);
    }

    .cart-actions .book-card__link.remove:hover {
        transform: translateY(-2px) scale(1.01);
    }

    /* Responsive stacking for very small screens */
    @media (max-width: 640px) {
        .cart-item { display:flex; flex-direction: column; align-items: stretch; }
        .cart-item__price, .cart-item__qty, .cart-item__subtotal, .cart-actions { width: 100%; text-align: left; display:flex; justify-content: flex-start; }
        .cart-item__qty { justify-content: flex-start; }
        .cart-item__qty form { display:flex; gap:0.5rem; align-items:center; }
        .cart-item__meta { margin-bottom: 0.5rem; }
        .cart-actions { justify-content: flex-end; }
    }

    .cart-total {
        font-size: 1.35rem;
        color: #e6eef8;
        font-weight: 600;
    }

    .cart-total strong {
        font-size: 1.6rem;
        display: inline-block;
        margin-left: 0.5rem;
        color: #ffffff;
    }

    .logout-link {
        border-radius: 999px;
        padding: 0.6rem 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<section class="cart-shell">
        {{-- Page-level header for Cart: Home / Perfil / Terminar sessão --}}
        <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-bottom:1rem;">
            <a class="logout-link" href="{{ route('home') }}">Início</a>
            @auth
                <a class="logout-link" href="{{ route('profile.show') }}">Perfil</a>
                <a class="logout-link" href="{{ route('logout') }}">Terminar sessão</a>
            @else
                <a class="logout-link" href="{{ route('login') }}">Entrar</a>
            @endauth
        </div>
    <div class="cart-hero">
        <h1>Carrinho de Compras</h1>
        <div style="text-align:right;">
            <small class="book-card__author">Itens: <span id="cart-items-count">{{ array_sum($cart ?? []) }}</span></small>
        </div>
    </div>

    @if(empty($cart) || count($cart) === 0)
        <div class="empty-state" style="margin-top:1rem;">
            <h2>O teu carrinho está vazio</h2>
            <p>Adiciona livros ao carrinho a partir do catálogo.</p>
            <a class="book-card__link" href="{{ route('catalog.index') }}" style="display:inline-block; margin-top:1rem;">Explorar catálogo</a>
        </div>
    @else
        @php $total = 0; @endphp
        <div class="cart-items">
            @foreach($cart as $id => $qty)
                @php
                    $book = $books[$id] ?? null;
                    $price = $book ? $book->price : 0;
                    $subtotal = $price * $qty;
                    $total += $subtotal;
                @endphp

                <article class="cart-item" id="cart-row-{{ $id }}">
                    <div class="cart-item__meta">
                        @if($book)
                            <h3>{{ $book->name }}</h3>
                            <p class="author">de {{ $book->author }}</p>
                        @else
                            <h3>Livro #{{ $id }}</h3>
                        @endif
                    </div>

                    <div class="cart-item__price">{{ number_format($price, 2, ',', ' ') }} €</div>

                    <div class="cart-item__qty">
                        <form method="POST" action="{{ route('cart.update') }}" class="ajax-cart-update">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $id }}">
                            {{-- Input corrigido: sem onchange e com CSS inline forçado --}}
                            <input type="number" name="quantity" value="{{ $qty }}" min="1">
                            <button type="submit" class="book-card__link" style="margin-left:0.5rem;">Atualizar</button>
                        </form>
                    </div>

                    <div class="cart-item__subtotal" id="subtotal-{{ $id }}">
                        {{ number_format($subtotal, 2, ',', ' ') }} €
                    </div>

                    <div class="cart-actions">
                        <form method="POST" action="{{ route('cart.remove') }}" class="ajax-cart-remove">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $id }}">
                            <button type="submit" class="book-card__link remove">Remover</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.5rem;">
            <div>
            </div>

            <div style="text-align:right;">
                <div class="cart-total">Total: <strong id="cart-total-value">{{ number_format($total, 2, ',', ' ') }} €</strong></div>
                <div style="margin-top:0.5rem; display:flex; gap:0.5rem; justify-content:flex-end; align-items:center;">
                    <a class="book-card__link" href="{{ route('catalog.index') }}" style="margin-right:0.5rem;">Continuar a comprar</a>
                    <a class="book-card__link" href="{{ route('cart.checkout') }}" style="background:linear-gradient(120deg,#6366f1,#8b5cf6); color:#fff;">Comprar</a>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection
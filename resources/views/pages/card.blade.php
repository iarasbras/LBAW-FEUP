@extends('layouts.app')

@section('title', 'Liberato · Livro')
@section('body-class', 'catalog-page')

@push('styles')
<style>
    body.catalog-page {
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
    }

    body.catalog-page main {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
    }

    body.catalog-page main > header {
        display: none;
    }

    body.catalog-page #content {
        width: 100%;
        max-width: 1100px;
    }

    .book-shell {
        background: rgba(17, 24, 39, 0.9);
        border-radius: 26px;
        padding: 3rem;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45);
    }

    .book-hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }

    .book-hero__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        justify-content: center;
    }

    .book-hero__meta span {
        padding: 0.5rem 1rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        font-size: 1.1rem;
        font-weight: 500;
        font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }

    .book-hero__card h1 {
        margin: 0 0 0.75rem 0;
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1.2;
        font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }

    .book-hero__card .book-hero__author {
        font-size: 1.75rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
        font-weight: 500;
        font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }

    .book-hero__card .book-hero__price {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }

    .book-hero__card {
        border-radius: 22px;
        padding: 2.5rem;
        background: linear-gradient(165deg, rgba(31, 41, 55, 0.95), rgba(17, 24, 39, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
        width: 100%;
        max-width: 800px;
    }

    .book-hero__card h2 {
        margin-top: 0;
        margin-bottom: 2rem;
        font-size: 3rem;
        font-weight: 700;
        text-align: center;
    }

    .book-hero__card p {
        margin: 1.25rem 0;
        font-size: 1.75rem;
        text-align: left;
        line-height: 1.6;
    }

    .book-hero__card p strong {
        display: inline-block;
        min-width: 220px;
        font-weight: 600;
        font-size: 1.75rem;
    }

    /* buy panel inside the details card */
    .buy-panel {
        display: flex;
        gap: 0.8rem;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.25rem;
        padding: 0.85rem 1rem;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.04);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.08);
    }

    .buy-panel input[type="number"] {
        width: 90px;
        padding: 0.65rem;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.04);
        color: inherit;
        font-weight: 600;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .buy-panel .buy-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 1.75rem;
        min-width: 150px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        background: linear-gradient(120deg,#6366f1,#8b5cf6);
        color: #fff;
        box-shadow: 0 12px 30px rgba(99,102,241,0.25);
    }

    .book-synopsis {
        margin-top: 3rem;
        line-height: 1.8;
        color: #e5e7eb;
    }

    .book-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 1rem;
        text-decoration: none;
        font-weight: 600;
        color: #a5b4fc;
        align-self: flex-start;
    }

    .catalog-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        gap: 1rem;
    }

    .catalog-topbar h2 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
        font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }

    .topbar-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .logout-link {
        border-radius: 999px;
        padding: 0.6rem 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        background: rgba(255, 255, 255, 0.08);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .logout-link:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .rating-section {
        margin-top: 3rem;
        padding: 2.5rem;
        background: linear-gradient(165deg, rgba(31, 41, 55, 0.95), rgba(17, 24, 39, 0.95));
        border-radius: 22px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
        width: 100%;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .rating-section h2 {
        margin-top: 0;
        margin-bottom: 1.5rem;
    }

    .rating-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .rating-input-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .rating-input-group label {
        font-weight: 600;
        color: #e5e7eb;
    }

    .rating-stars {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .rating-stars {
        display: flex;
        gap: 0.25rem;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating-stars input[type="radio"] {
        display: none;
    }

    .rating-stars label {
        font-size: 2rem;
        color: #6b7280;
        cursor: pointer;
        transition: color 0.2s;
        margin: 0;
        user-select: none;
    }

    .rating-stars label:hover,
    .rating-stars label:hover ~ label {
        color: #fbbf24;
    }

    .rating-stars input[type="radio"]:checked ~ label {
        color: #fbbf24;
    }

    .rating-submit-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        background: linear-gradient(120deg,#6366f1,#8b5cf6);
        color: #fff;
        box-shadow: 0 12px 30px rgba(99,102,241,0.25);
        transition: transform 0.2s, box-shadow 0.2s;
        max-width: 200px;
    }

    .rating-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(99,102,241,0.35);
    }

    .user-rating-display {
        padding: 1rem;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        margin-bottom: 1rem;
    }

    .user-rating-display p {
        margin: 0;
        color: #d1d5db;
    }

    .alert {
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.4);
        color: #86efac;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #fca5a5;
    }

    /* Wishlist heart button */
    .wishlist-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 2rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.08);
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.4rem;
        color: #6b7280;
        margin-top: 1rem;
        font-weight: 600;
    }

    .wishlist-btn:hover {
        background: rgba(31, 41, 55, 0.9);
        border-color: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    .wishlist-btn.active {
        color: #ef4444;
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.15);
    }

    .wishlist-btn.active:hover {
        background: rgba(239, 68, 68, 0.25);
    }

    .wishlist-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
@php
    $isAdmin = auth('admin')->check();
    $adminUser = auth('admin')->user();
@endphp
<section class="book-shell">
    <div class="catalog-topbar" style="margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center; gap:1rem;">
        <h2>
            Bem-vindo(a),
            @if ($isAdmin)
                {{ $adminUser->name ?? 'Administrador' }}
            @else
                {{ Auth::user()->username ?? 'Visitante' }}
            @endif
        </h2>
        <div class="topbar-actions">
            @if ($isAdmin)
                <span class="badge">Modo administração · compras desativadas</span>
                <a class="logout-link" href="{{ route('admin.dashboard') }}">Painel admin</a>
                <a class="logout-link" href="{{ route('logout') }}">Terminar sessão</a>
            @else
                {{-- ID cart-items-count para o AJAX atualizar o número --}}
                <a class="logout-link" href="{{ url('/cart') }}">Carrinho (<span id="cart-items-count">{{ array_sum(session('cart', [])) }}</span>)</a>

                @auth
                    <a class="logout-link" href="{{ route('profile.show') }}">Perfil</a>
                    <a class="logout-link" href="{{ route('logout') }}">Terminar sessão</a>
                @else
                    <a class="logout-link" href="{{ route('login') }}">Entrar</a>
                @endauth
            @endif
        </div>
    </div>
    <div class="book-hero">
        <a class="book-back-link" href="{{ route('catalog.index') }}">← Voltar ao catálogo</a>
        
        <article class="book-hero__card">
            <h2>Detalhes</h2>
            
            <p><strong>Nome do livro:</strong> {{ $book->name }}</p>
            <p><strong>Autor:</strong> {{ $book->author }}</p>
            <p><strong>Preço:</strong> {{ number_format($book->price, 2, ',', ' ') }} €</p>
            <p><strong>Vendido por:</strong> {{ optional($book->seller)->username ?? 'Loja Liberato' }}</p>
            <p>
                <strong>Avaliação média:</strong>
                {{-- ID ADICIONADO PARA O AJAX ATUALIZAR --}}
                <span id="avg-rating-display">
                    @if ($book->average_rating)
                        ★ {{ $book->average_rating }} ({{ $book->reviews_count }} avaliações)
                    @else
                        Sem avaliações
                    @endif
                </span>
            </p>
            <p><strong>Idioma:</strong> {{ $book->language }}</p>
            <p><strong>Categoria:</strong> {{ $book->category_name }}</p>
            <p><strong>Stock disponível:</strong> {{ $book->available_stock }}</p>
            <p><strong>Sinopse:</strong> {{ $book->synopsis ?? 'Ainda não temos uma sinopse detalhada para este livro.' }}</p>

            @if(Auth::guard('web')->check())
                <button 
                    class="wishlist-btn {{ $isFavorite ?? false ? 'active' : '' }}" 
                    data-book-id="{{ $book->book_id }}"
                    data-favorite="{{ $isFavorite ?? false ? 'true' : 'false' }}"
                    aria-label="{{ $isFavorite ?? false ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
                >
                    {{ $isFavorite ?? false ? '❤️ Adicionado aos favoritos' : '🤍 Adicionar aos favoritos' }}
                </button>
            @endif

            {{-- CTA: only regular users can buy --}}
            @if ($isAdmin)
                <div class="buy-panel" style="justify-content:flex-start;">
                    <span class="badge">Compras desativadas em modo administração</span>
                </div>
            @else
                {{-- Classe ajax-cart-add presente para o JS funcionar --}}
                <form method="POST" action="{{ route('cart.add') }}" class="buy-panel ajax-cart-add">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                    <label for="quantity" style="display:none">Quantidade</label>
                    <input id="quantity" type="number" name="quantity" value="1" min="1" max="{{ $book->available_stock }}">
                    <button type="submit" class="buy-btn">Comprar</button>
                </form>
            @endif
        </article>
    </div>

    {{-- Rating Section --}}
    @if (!$isAdmin)
        <section class="rating-section">
            <h2>Avaliar este livro</h2>
            
            {{-- Div para mensagens de erro/sucesso via AJAX --}}
            <div id="review-message-box">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif
            </div>

            @auth
                @if ($userReview)
                    <div class="user-rating-display">
                        <p><strong>A sua avaliação anterior:</strong> 
                            <span id="user-current-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $userReview->rating) ★ @else ☆ @endif
                                @endfor
                                ({{ $userReview->rating }}/5)
                            </span>
                        </p>
                        <p style="font-size: 0.9rem; color: #9ca3af; margin-top: 0.5rem;">
                            Avaliado em {{ \Carbon\Carbon::parse($userReview->date)->format('d/m/Y') }}
                        </p>
                    </div>
                @endif

                @if ($hasBought)
                    {{-- FORMULÁRIO COM ID PARA AJAX --}}
                    <form id="review-form" method="POST" action="{{ route('catalog.review', $book) }}" class="rating-form">
                        @csrf
                        <div class="rating-input-group">
                            <label for="rating">A sua avaliação:</label>
                            <div class="rating-stars">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                        {{ $userReview && $userReview->rating == $i ? 'checked' : '' }} required>
                                    <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: center; margin-top: 0.5rem;">
                            <button type="submit" class="rating-submit-btn">
                                {{ $userReview ? 'Atualizar avaliação' : 'Submeter avaliação' }}
                            </button>
                            @if ($userReview)
                                <button type="button" id="delete-review-btn" 
                                    data-url="{{ route('catalog.review.destroy', $book) }}"
                                    class="rating-submit-btn"
                                    style="background: transparent; border: 1px solid #ef4444; color: #ef4444; box-shadow: none;">
                                    Remover
                                </button>
                            @endif
                        </div>
                    </form>
                @endif
            @else
                <p style="color: #9ca3af;">
                    <a href="{{ route('login') }}" style="color: #a5b4fc; text-decoration: underline;">
                        Inicie sessão
                    </a> para avaliar este livro.
                </p>
            @endauth
        </section>
    @endif
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Wishlist functionality
        const wishlistBtn = document.querySelector('.wishlist-btn');
        if (wishlistBtn) {
            wishlistBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const bookId = this.getAttribute('data-book-id');
                const isFavorite = this.getAttribute('data-favorite') === 'true';
                
                this.disabled = true;
                this.style.opacity = '0.6';
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                fetch(`/wishlist/${bookId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.setAttribute('data-favorite', data.isFavorite ? 'true' : 'false');
                        this.classList.toggle('active', data.isFavorite);
                        this.innerHTML = data.isFavorite ? '❤️ Adicionado aos favoritos' : '🤍 Adicionar aos favoritos';
                        this.setAttribute('aria-label', data.isFavorite ? 'Remover dos favoritos' : 'Adicionar aos favoritos');
                    } else {
                        alert(data.message || 'Erro ao atualizar favoritos');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erro ao atualizar favoritos. Por favor, tenta novamente.');
                })
                .finally(() => {
                    this.disabled = false;
                    this.style.opacity = '1';
                });
            });
        }

        // 2. Review AJAX Functionality
        const reviewForm = document.getElementById('review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerText;
                const msgBox = document.getElementById('review-message-box');
                
                // Loading State
                btn.disabled = true;
                btn.innerText = "A guardar...";
                msgBox.innerHTML = ''; 

                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Mensagem de sucesso
                        msgBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        
                        // Atualizar Botão
                        btn.innerText = "Guardado!";
                        setTimeout(() => {
                            btn.innerText = "Atualizar avaliação"; 
                            btn.disabled = false;
                        }, 1000);

                        const badge = document.getElementById('avg-rating-display');
                        if(badge) {
                            badge.innerText = `★ ${data.newAverage} (${data.newCount} avaliações)`;
                        }

                    } else {
                        throw new Error(data.message || 'Erro ao guardar avaliação');
                    }
                } catch (error) {
                    console.error(error);
                    msgBox.innerHTML = `<div class="alert alert-error">Erro: ${error.message}</div>`;
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            });
        }

        // 3. Delete Review Functionality
        const deleteBtn = document.getElementById('delete-review-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                if(!confirm('Tem a certeza que deseja remover a sua avaliação?')) return;

                const originalText = this.innerText;
                this.disabled = true;
                this.innerText = "...";
                
                const msgBox = document.getElementById('review-message-box');
                msgBox.innerHTML = '';

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const url = this.dataset.url;

                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        msgBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        
                        // Update UI
                        document.querySelector('.user-rating-display')?.remove();
                        
                        const form = document.getElementById('review-form');
                        if(form) {
                            form.reset();
                            form.querySelector('button[type="submit"]').innerText = 'Submeter avaliação';
                        }

                        const badge = document.getElementById('avg-rating-display');
                        if(badge) badge.innerText = data.newAverage ? `★ ${data.newAverage} (${data.newCount} avaliações)` : 'Sem avaliações';

                        this.remove();
                    } else {
                        throw new Error(data.message || 'Erro ao remover.');
                    }
                } catch (error) {
                    console.error(error);
                    msgBox.innerHTML = `<div class="alert alert-error">Erro: ${error.message}</div>`;
                    this.innerText = originalText;
                    this.disabled = false;
                }
            });
        }
    });
</script>
@endpush
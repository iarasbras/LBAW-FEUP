@extends('layouts.app')

@section('title', 'Liberato · Catálogo')
@section('body-class', 'catalog-page')

@push('styles')
<style>
    body.catalog-page {
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
    }
    body.catalog-page main { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 3rem 1.5rem; }
    body.catalog-page main > header { display: none; }
    body.catalog-page #content { width: 100%; max-width: 1200px; }

    .catalog-shell { background: rgba(17, 24, 39, 0.85); border-radius: 26px; padding: 3rem; box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45); }
    .catalog-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; gap: 1rem; }
    .catalog-topbar h2 { margin: 0; font-size: 2.00rem; font-weight: 600; }

    .logout-link {
        border-radius: 999px; 
        padding: 0.6rem 1.2rem; 
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: inherit; 
        text-decoration: none; 
        font-weight: 600; 
        transition: all 0.2s ease;
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        background: transparent; 
        cursor: pointer;
        text-transform: none; 
    }

    .logout-link:hover { background: rgba(255, 255, 255, 0.1); transform: translateY(-1px); }
    .topbar-actions { display: flex; gap: 0.75rem; align-items: center; }
    .logout-form { display: inline; margin: 0; padding: 0; }
    .logout-form button { font-family: inherit; font-size: inherit; }

    .catalog-hero { text-align: center; margin-bottom: 2.5rem; }
    .catalog-hero h1 { margin-bottom: 0.5rem; font-size: 3.25rem; font-weight: 700; }
    .catalog-hero p { max-width: 700px; margin: 0 auto 1.5rem; color: #d1d5db; font-size: 1.2rem; }
    .badge { display: inline-flex; padding: 0.35rem 0.9rem; border-radius: 999px; background: rgba(99, 102, 241, 0.15); color: #c7d2fe; font-weight: 600; }

    .catalog-filters { display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; margin-bottom: 2.5rem; }
    .catalog-filters input, .catalog-filters select {
        padding: 0.9rem 1.2rem; border-radius: 999px; border: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.08); color: inherit; min-width: 240px; text-align: center;
    }
    .catalog-filters button {
        padding: 0.9rem 1.8rem; border-radius: 999px; border: none;
        background: linear-gradient(120deg, #6366f1, #8b5cf6); color: #fff; font-weight: 600; cursor: pointer;
    }
    .catalog-filters select option { color: #000; }

    /* Estilos do Grid e Cards */
    .catalog-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.75rem; }
    .book-card {
        border-radius: 22px; padding: 1.75rem; display: flex; flex-direction: column; gap: 1rem;
        background: linear-gradient(165deg, rgba(31, 41, 55, 0.95), rgba(17, 24, 39, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.08); position: relative;
    }
    .book-card h2 { font-size: 2rem; font-weight: 700; margin: 0; line-height: 1.3; }
    .book-card__author { color: #d1d5db; margin: 0; font-size: 1.15rem; }
    .book-card__price { font-size: 1.75rem; font-weight: 700; }
    .book-card__actions { margin-top: auto; display: flex; gap: 0.75rem; }
    .book-card__action-btn {
        flex: 1; padding: 0.6rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.25);
        text-align: center; text-decoration: none; color: inherit; font-weight: 600; cursor: pointer;
        background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center;
        transition: all 0.2s ease;
    }
    .book-card__action-btn:hover { background: rgba(255,255,255,0.15); }
    .book-card__details-btn { background: linear-gradient(120deg, #6366f1, #8b5cf6); border: none; }
    
    .wishlist-btn {
        position: absolute; top: 1rem; right: 0.75rem; background: rgba(17, 24, 39, 0.8);
        border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; border: 1px solid rgba(255,255,255,0.15); font-size: 1.25rem;
    }
    .wishlist-btn.active { color: #ef4444; border-color: #ef4444; background: rgba(239, 68, 68, 0.15); }
    .empty-state { text-align: center; padding: 3rem; border: 2px dashed rgba(255,255,255,0.25); border-radius: 22px; color: #d1d5db; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resultsContainer = document.getElementById('catalog-results');
        
        function initButtons() {
            // --- 1. Wishlist Logic ---
            document.querySelectorAll('.wishlist-btn').forEach(btn => {
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);
                
                newBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const bookId = this.dataset.bookId;
                    const token = document.querySelector('meta[name="csrf-token"]').content;
                    
                    fetch(`/wishlist/${bookId}/toggle`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    })
                    .then(r => r.json())
                    .then(data => {
                        if(data.success) {
                            this.classList.toggle('active', data.isFavorite);
                            this.innerHTML = data.isFavorite ? '❤️' : '🤍';
                        }
                    });
                });
            });

            // --- 2. Cart Logic ---
            document.querySelectorAll('.ajax-cart-add').forEach(form => {
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);

                newForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    const btn = this.querySelector('button');
                    const originalText = btn.innerText;
                    
                    btn.innerText = "..."; 
                    btn.disabled = true;

                    const formData = new FormData(this);
                    
                    try {
                        const res = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest', // Força o controller a saber que é AJAX
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });
                        
                        const data = await res.json();
                        
                        if(data.success) {
                            const counter = document.getElementById('cart-items-count');
                            if(counter) counter.innerText = data.cartCount;
                            
                            // Feedback Visual
                            btn.innerText = "Adicionado!";
                            btn.style.backgroundColor = "#10b981"; // Verde
                            
                            setTimeout(() => { 
                                btn.innerText = originalText; 
                                btn.style.backgroundColor = "";
                                btn.disabled = false; 
                            }, 300);
                        } else {
                            // Se o controller devolver erro (ex: admin bloqueado)
                            alert(data.message || 'Erro ao adicionar.');
                            btn.innerText = originalText; 
                            btn.disabled = false;
                        }
                    } catch(e) { 
                        console.error(e); 
                        btn.innerText = originalText; 
                        btn.disabled = false; 
                    }
                });
            });
        }

        // --- 3. AJAX Fetch Logic ---
        async function fetchCatalog(url) {
            resultsContainer.style.opacity = '0.5';
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if(res.ok) {
                    resultsContainer.innerHTML = await res.text();
                    window.history.pushState(null, '', url);
                    initButtons(); // Re-liga os eventos no novo HTML
                }
            } finally { resultsContainer.style.opacity = '1'; }
        }

        // Filtros Submit
        document.querySelector('.catalog-filters')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(this)).toString();
            fetchCatalog(`${this.action}?${params}`);
        });

        // Paginação Links
        resultsContainer?.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination a');
            if(link) {
                e.preventDefault();
                fetchCatalog(link.href);
                document.querySelector('.catalog-hero').scrollIntoView({ behavior: 'smooth' });
            }
        });

        initButtons(); 
    });
</script>
@endpush

@section('content')
@php $isAdmin = auth('admin')->check(); @endphp
<section class="catalog-shell">
    <div class="catalog-topbar">
        <h2>Olá, {{ $isAdmin ? (auth('admin')->user()->name) : (auth()->user()->username ?? 'Visitante') }}</h2>
        <div class="topbar-actions">
            @if($isAdmin)
                <span class="badge">Admin Mode</span>
                <a class="logout-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">@csrf <button class="logout-link">Terminar Sessao</button></form>
            @else
                <a class="logout-link" href="{{ url('/cart') }}">Carrinho (<span id="cart-items-count">{{ array_sum(session('cart', [])) }}</span>)</a>
                @auth
                    @php
                        $favoritesParams = array_merge(
                            request()->except(['favorites', 'page']),
                            ['favorites' => ($showFavorites ?? false) ? '0' : '1']
                        );
                    @endphp
                    <a class="logout-link {{ $showFavorites ?? false ? 'active' : '' }}" 
                       href="{{ route('catalog.index', $favoritesParams) }}">
                        {{ ($showFavorites ?? false) ? 'Todos os livros' : 'Meus favoritos' }}
                    </a>
                    @if(\Illuminate\Support\Facades\DB::table('seller')->where('seller_id', auth()->id())->exists())
                        <a class="logout-link" href="{{ route('seller.dashboard') }}" style="color:#a5b4fc; border-color:#6366f1;">Painel Vendedor</a>
                    @endif
                    <a class="logout-link" href="{{ route('notifications.index') }}">🔔</a>
                    <a class="logout-link" href="{{ route('profile.show') }}">Perfil</a>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">@csrf <button class="logout-link">Terminar Sessao</button></form>
                @else
                    <a class="logout-link" href="{{ route('login') }}">Entrar</a>
                @endauth
            @endif
        </div>
    </div>

    <div class="catalog-hero">
        <span class="badge">Catálogo</span>
        <h1>Livros da Liberato</h1>
        <p>Encontra o teu próximo livro.</p>

        <form class="catalog-filters" action="{{ route('catalog.index') }}" method="GET">
            @if($showFavorites ?? false) <input type="hidden" name="favorites" value="1"> @endif
            <input type="search" name="q" placeholder="Pesquisar..." value="{{ $searchTerm ?? '' }}">
            <select name="category">
                <option value="">Categorias</option>
                @foreach($categories as $c) <option value="{{ $c }}" @selected($c === ($activeCategory ?? ''))>{{ $c }}</option> @endforeach
            </select>
            <select name="sort">
                <option value="name">Nome</option>
                <option value="price_asc">Preço Crescente</option>
                <option value="price_desc">Preço Decrescente</option>
            </select>
        </form>
    </div>

    <div id="catalog-results">
        @include('pages.catalog_grid')
    </div>
</section>
@endsection
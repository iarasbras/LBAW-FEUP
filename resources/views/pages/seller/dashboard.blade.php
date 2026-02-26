@extends('layouts.app')

@section('title', 'Painel de Vendedor')
@section('body-class', 'seller-page')

@push('styles')
<style>
    body.seller-page {
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
    }
    .seller-shell {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 0 1.5rem;
    }
    
    /* Header */
    .seller-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: rgba(17, 24, 39, 0.8);
        padding: 2rem;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }
    .seller-header h1 { margin: 0; font-size: 2rem; font-weight: 700; }
    .header-actions { display: flex; gap: 1rem; }
    
    /* Botões Topo */
    .btn {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600;
        text-decoration: none; border: none; cursor: pointer; transition: all 0.2s;
    }
    .btn-primary { background: linear-gradient(120deg, #6366f1, #8b5cf6); color: white; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,0.4); }
    .btn-ghost { background: rgba(255,255,255,0.1); color: white; }
    .btn-ghost:hover { background: rgba(255,255,255,0.15); }

    /* Filtros */
    .dashboard-filters {
        display: flex; gap: 1rem; margin-bottom: 2.5rem; flex-wrap: wrap; justify-content: flex-start;
    }
    .dashboard-filters input, .dashboard-filters select {
        padding: 0 1.2rem; 
        height: 50px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.08);
        color: white;
        min-width: 240px; 
        font-weight: 400;
        font-size: 1.5rem;
        outline: none;
        transition: all 0.2s;
        text-align: center;
    }
    .dashboard-filters input::placeholder { color: #9ca3af; }
    .dashboard-filters input:focus, .dashboard-filters select:focus {
        border-color: rgba(99, 102, 241, 0.5); background: rgba(255, 255, 255, 0.12);
    }
    .dashboard-filters select option { background: #1f2937; color: white; text-align: left; }
    
    .btn-filter-submit {
        height: 50px; padding: 0 1.8rem; border-radius: 999px; border: none;
        background: linear-gradient(120deg, #6366f1, #8b5cf6); color: #fff; font-weight: 600; cursor: pointer;
    }

    /* Tabela */
    .inventory-table {
        width: 100%; border-collapse: separate; border-spacing: 0;
        background: rgba(17, 24, 39, 0.6); border-radius: 16px; overflow: hidden;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .inventory-table th { background: rgba(255,255,255,0.05); padding: 1.2rem; text-align: left; color: #9ca3af; }
    .inventory-table td { padding: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
    .book-thumb { width: 40px; height: 60px; object-fit: cover; border-radius: 6px; background: #374151; }
    
    /* --- BOTÕES DE AÇÃO --- */
    .actions { 
        display: flex; 
        gap: 0.5rem; 
        justify-content: flex-end; 
        align-items: center; 
    }
    
    .ajax-delete-book {
        display: flex;
        margin: 0;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 36px; 
        padding: 0 1.2rem;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        background: transparent;
        color: white;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
        white-space: nowrap;
        box-sizing: border-box; 
        line-height: 1; 
    }

    .btn-action:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: white;
    }
    
    .btn-action.danger {
         border-color: rgba(239, 68, 68, 0.5); color: #fca5a5;
    }
    .btn-action.danger:hover {
         background: rgba(239, 68, 68, 0.15); border-color: #ef4444; color: #ef4444;
    }
</style>
@endpush

@section('content')
<div class="seller-shell">
    
    {{-- Header --}}
    <div class="seller-header">
        <div>
            <h1>Painel de Vendedor</h1>
            <p style="color: #9ca3af; margin-top: 0.5rem;">Gerir inventário de {{ Auth::user()->username }}</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('catalog.index') }}" class="btn btn-ghost">← Voltar ao Catálogo</a>
            <a href="{{ route('seller.orders.history') }}" class="btn btn-ghost">Histórico de Vendas</a>
            <a href="{{ route('seller.books.create') }}" class="btn btn-primary">+ Novo Livro</a>
        </div>
    </div>

    {{-- Filtros e Pesquisa (Estilo Catálogo) --}}
    <form class="dashboard-filters" method="GET" action="{{ route('seller.dashboard') }}">
        <input type="text" name="search" placeholder="Pesquisar por título ou autor" value="{{ $search }}">
        
        <select name="category" onchange="this.form.submit()">
            <option value="">Todas as categorias</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
            @endforeach
        </select>

        <select name="sort" onchange="this.form.submit()">
            <option value="newest" @selected($sort === 'newest')>Ordenar por: Mais recentes</option>
            <option value="stock_asc" @selected($sort === 'stock_asc')>Ordenar por: Menor stock</option>
            <option value="name" @selected($sort === 'name')>Ordenar por: Nome (A-Z)</option>
        </select>
        
    </form>

    {{-- Tabela --}}
    @if($books->isEmpty())
        <div style="text-align: center; padding: 4rem; border: 2px dashed rgba(255,255,255,0.1); border-radius: 16px; color: #9ca3af;">
            <p>Não tens livros à venda correspondentes à pesquisa.</p>
        </div>
    @else
        <table class="inventory-table">
            <thead>
                <tr>
                    <th width="70">Capa</th>
                    <th>Livro</th>
                    <th>Preço</th>
                    <th>Stock</th>
                    <th style="text-align: right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                    <tr id="book-row-{{ $book->book_id }}">
                        <td>
                            @if($book->image)
                                <img src="{{ asset('storage/' . $book->image) }}" class="book-thumb" alt="Capa de {{ $book->name }}">
                            @else
                                <div class="book-thumb"></div>
                            @endif
                        </td>
                        <td>
                            <strong style="font-size: 1.5rem; display:block;">{{ $book->name }}</strong>
                            <span style="color: #9ca3af; font-size: 1.3rem;">{{ $book->author }} • {{ $book->category_name }}</span>
                        </td>
                        <td style="font-weight: 700;">{{ number_format($book->price, 2) }} €</td>
                        <td>{{ $book->available_stock }}</td>
                        <td>
                            <div class="actions">
                                {{-- Botão Editar (Link) --}}
                                <a href="{{ route('seller.books.edit', $book) }}" class="btn-action">Editar</a>
                                
                                {{-- Botão Remover (Form) --}}
                                <form action="{{ route('seller.books.destroy', $book) }}" method="POST" class="ajax-delete-book" data-id="{{ $book->book_id }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action danger">Remover</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top: 1.5rem;">{{ $books->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// AJAX Delete Script
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.ajax-delete-book').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            
            const btn = this.querySelector('button');
            const row = document.getElementById(`book-row-${this.dataset.id}`);
            const originalText = btn.innerText;
            
            btn.disabled = true; 
            btn.innerText = "...";

            try {
                const res = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: new FormData(this)
                });
                
                if (res.ok) {
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 300);
                } else {
                    alert('Erro ao remover.');
                    btn.disabled = false;
                    btn.innerText = originalText;
                }
            } catch (err) { 
                console.error(err);
                btn.disabled = false;
                btn.innerText = originalText;
            }
        });
    });
});
</script>
@endpush
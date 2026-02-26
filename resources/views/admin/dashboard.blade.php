@extends('layouts.app')

@section('title', 'Liberato · Administração')
@section('body-class', 'admin-page')

@push('styles')
<style>
    body.admin-page {
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        font-size: 1.5rem;
        min-height: 100vh; 
        margin: 0;
    }
    .admin-shell {
        max-width: 1600px;
        margin: 3rem auto;
        padding: 0 1.5rem;
    }
    
    /* Header */
    .admin-header {
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
    .admin-header h1 { margin: 0; font-size: 2.5rem; font-weight: 700; }
    .header-actions { display: flex; gap: 1rem; align-items: center; }
    
    /* Botões */
    .btn {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 1rem 2rem; border-radius: 12px; font-weight: 600;
        font-size: 1.5rem;
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
        padding: 0 1.5rem; 
        height: 60px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.08);
        color: white;
        min-width: 250px; 
        font-weight: 400;
        font-size: 1.5rem;
        outline: none;
        transition: all 0.2s;
    }
    .dashboard-filters input::placeholder { color: #9ca3af; }
    .dashboard-filters input:focus, .dashboard-filters select:focus {
        border-color: rgba(99, 102, 241, 0.5); background: rgba(255, 255, 255, 0.12);
    }
    .dashboard-filters select option { background: #1f2937; color: white; }

    /* Tabela */
    .inventory-table {
        width: 100%; border-collapse: separate; border-spacing: 0;
        background: rgba(17, 24, 39, 0.6); border-radius: 16px; overflow: hidden;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .inventory-table th { 
        background: rgba(255,255,255,0.05); 
        padding: 1.5rem; 
        text-align: left; 
        color: #9ca3af; 
        font-size: 1.5rem;
    }
    .inventory-table td { 
        padding: 1.5rem; 
        border-bottom: 1px solid rgba(255,255,255,0.05); 
        vertical-align: middle; 
        font-size: 1.5rem;
    }
    .book-thumb { width: 60px; height: 90px; object-fit: cover; border-radius: 6px; background: #374151; }
    
    /* Tags Especiais */
    .badge-store {
        display: inline-block; padding: 0.5rem 1rem; border-radius: 99px;
        background: rgba(16, 185, 129, 0.2); color: #34d399; 
        font-size: 1.5rem;
        font-weight: 600;
    }
    .badge-user {
        display: inline-block; padding: 0.5rem 1rem; border-radius: 99px;
        background: rgba(59, 130, 246, 0.2); color: #60a5fa; 
        font-size: 1.5rem;
        font-weight: 600;
    }

    /* Ações */
    .actions { display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center; }
    .ajax-delete-book { display: flex; margin: 0; }
    .btn-action {
        display: inline-flex; align-items: center; justify-content: center;
        height: 50px;
        padding: 0 1.5rem; border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        background: transparent; color: white; text-decoration: none;
        font-size: 1.5rem;
        font-weight: 600; transition: all 0.2s; cursor: pointer;
    }
    .btn-action:hover { background: rgba(255, 255, 255, 0.1); border-color: white; }
    .btn-action.danger { border-color: rgba(239, 68, 68, 0.5); color: #fca5a5; }
    .btn-action.danger:hover { background: rgba(239, 68, 68, 0.15); border-color: #ef4444; color: #ef4444; }

    /* Paginação */
    .pagination-wrapper {
        margin-top: 1.5rem;
        font-size: 1rem; 
    }
    .pagination-wrapper svg {
        width: 20px;
        height: 20px;
    }
    .pagination-wrapper nav div {
        align-items: center;
    }
</style>
@endpush

@section('content')
<div class="admin-shell">
    
    {{-- Header --}}
    <div class="admin-header">
        <div>
            <h1>Painel de Administração</h1>
            <p style="color: #9ca3af; margin-top: 0.5rem; font-size: 1.5rem;">Gestão global da plataforma e catálogo</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.platform-info.edit') }}" class="btn btn-ghost">Editar Sobre Nós</a>

            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Gerir Utilizadores</a>

            <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Gerir Categorias</a>
            
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">+ Adicionar Livro</a>
            
            {{-- CORREÇÃO: Ação aponta para admin.logout --}}
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-ghost" style="border: 1px solid rgba(255,255,255,0.1);">Terminar Sessão</button>
            </form>
        </div>
    </div>

    {{-- Filtros --}}
    <form class="dashboard-filters" method="GET" action="{{ route('admin.dashboard') }}">
        <input type="text" name="search" placeholder="Pesquisar..." value="{{ request('search') }}">
        
        <select name="category" onchange="this.form.submit()">
            <option value="">Todas as categorias</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>

        <select name="seller_id" onchange="this.form.submit()">
            <option value="">Todos os vendedores</option>
            <option value="store" @selected(request('seller_id') === 'store')>Loja Liberato</option>
            @if(isset($sellers))
            @foreach($sellers as $seller)
                <option value="{{ $seller->user_id }}" @selected(request('seller_id') == $seller->user_id)>
                    {{ $seller->username }}
                </option>
            @endforeach
            @endif
        </select>

        <select name="sort" onchange="this.form.submit()">
            <option value="newest" @selected(request('sort') === 'newest')>Mais recentes</option>
            <option value="stock_asc" @selected(request('sort') === 'stock_asc')>Menor stock</option>
            <option value="name" @selected(request('sort') === 'name')>Nome (A-Z)</option>
        </select>
    </form>

    {{-- Tabela --}}
    @if($books->isEmpty())
        <div style="text-align: center; padding: 4rem; border: 2px dashed rgba(255,255,255,0.1); border-radius: 16px; color: #9ca3af;">
            <p style="font-size: 1.5rem;">Não existem livros correspondentes à pesquisa.</p>
        </div>
    @else
        <table class="inventory-table">
            <thead>
                <tr>
                    <th width="90">Capa</th>
                    <th>Livro</th>
                    <th>Vendedor</th>
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
                            <strong style="font-size: 1.8rem; display:block; margin-bottom: 0.25rem;">{{ $book->name }}</strong>
                            <span style="color: #9ca3af; font-size: 1.5rem;">{{ $book->author }} • {{ $book->category_name }}</span>
                        </td>
                        <td>
                            @if($book->seller_id)
                                <span class="badge-user">{{ $book->seller->username ?? 'Utilizador' }}</span>
                            @else
                                <span class="badge-store">Loja Liberato</span>
                            @endif
                        </td>
                        <td style="font-weight: 700; font-size: 1.7rem;">{{ number_format($book->price, 2) }} €</td>
                        <td style="font-size: 1.7rem;">{{ $book->available_stock }}</td>
                        <td>
                            <div class="actions">
                                @if(is_null($book->seller_id))
                                    <a href="{{ route('admin.books.edit', $book) }}" class="btn-action">Editar</a>
                                @endif
                                
                                <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="ajax-delete-book" data-id="{{ $book->book_id }}">
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
        
        <div class="pagination-wrapper">
            {{ $books->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
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
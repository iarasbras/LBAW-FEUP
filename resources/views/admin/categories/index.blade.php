@extends('layouts.app')

@section('title', 'Liberato · Gestão de Categorias')
@section('body-class', 'admin-page')

@push('styles')
<style>
    body.admin-page {
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        font-size: 1.8rem;
        min-height: 100vh;
        margin: 0;
    }
    .admin-shell {
        max-width: 1600px;
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
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }
    .admin-header h1 { margin: 0; font-size: 3rem; font-weight: 700; color: #ffffff; }
    .header-actions { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
    .btn {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 1rem 2rem; border-radius: 12px; font-weight: 600;
        font-size: 1.6rem;
        text-decoration: none; border: none; cursor: pointer; transition: all 0.2s;
        text-transform: none;
    }
    .btn-primary { background: linear-gradient(120deg, #6366f1, #8b5cf6); color: white; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,0.4); }
    .btn-secondary { background: rgba(255,255,255,0.1); color: white; }
    .btn-secondary:hover { background: rgba(255,255,255,0.15); }
    .btn-sm { padding: 0.5rem 1rem; font-size: 1.4rem; }
    .btn-success { background: #10b981; color: #fff; }
    .btn-success:hover { background: #059669; transform: translateY(-1px); }
    .btn-warning { background: #f59e0b; color: #fff; }
    .btn-warning:hover { background: #d97706; }
    .btn-danger { background: #ef4444; color: #fff; }
    .btn-danger:hover { background: #dc2626; }
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    .alert-success {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.5);
    }
    .alert-danger {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
        border: 1px solid rgba(239, 68, 68, 0.5);
    }
    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: rgba(17, 24, 39, 0.6);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .admin-table th {
        background: rgba(255,255,255,0.05);
        padding: 1.5rem;
        text-align: left;
        color: #9ca3af;
        font-size: 1.6rem;
        font-weight: 600;
    }
    .admin-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        font-size: 1.6rem;
        vertical-align: middle;
    }
    .actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: nowrap;
        white-space: nowrap;
    }
    .actions form {
        display: inline-flex;
        margin: 0;
    }
    .admin-table td.actions {
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="admin-shell">
    <x-admin-header />

    <div style="margin: 2rem 0;">
        <h2 style="font-size: 2.5rem; font-weight: 700; color: #ffffff; margin-bottom: 0.5rem;">Categorias</h2>
        <p style="color: #9ca3af; font-size: 1.5rem;">Gestão de categorias de livros</p>
    </div>

    <div style="margin-bottom: 2rem; display: flex; justify-content: flex-end;">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Categoria
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nome da Categoria</th>
                    <th>Nº de Livros</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->category_name }}</td>
                        <td>{{ $category->books_count }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.categories.edit', $category->category_name) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @if($category->books_count == 0)
                                <form action="{{ route('admin.categories.destroy', $category->category_name) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja eliminar esta categoria?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled title="Não é possível eliminar categorias com livros">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Nenhuma categoria encontrada</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Liberato · Editar Categoria')
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
        max-width: 1400px;
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
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    .alert-danger {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
        border: 1px solid rgba(239, 68, 68, 0.5);
    }
    .admin-form {
        background: rgba(17, 24, 39, 0.6);
        padding: 2rem;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #f9fafb;
        font-size: 1.5rem;
    }
    .required { color: #ef4444; }
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 8px;
        font-size: 1.5rem;
        background: rgba(255,255,255,0.08);
        color: #f9fafb;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        background: rgba(255,255,255,0.12);
    }
    .form-text {
        display: block;
        margin-top: 0.25rem;
        font-size: 1.3rem;
        color: #9ca3af;
    }
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
</style>
@endpush

@section('content')
<div class="admin-shell">
    <x-admin-header />

    <div style="margin: 2rem 0;">
        <h2 style="font-size: 2.5rem; font-weight: 700; color: #ffffff; margin-bottom: 0.5rem;">Editar Categoria</h2>
        <p style="color: #9ca3af; font-size: 1.5rem;">Modificar categoria existente</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category->category_name) }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="category_name">Nome da Categoria <span class="required">*</span></label>
            <input 
                type="text" 
                name="category_name" 
                id="category_name" 
                class="form-control" 
                value="{{ old('category_name', $category->category_name) }}" 
                required 
                placeholder="Ex: Ficção, Romance, Suspense..."
            >
            <small class="form-text">O nome da categoria deve ser único.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Alterações
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection

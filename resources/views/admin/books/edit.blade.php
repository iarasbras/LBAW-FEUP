@extends('layouts.app')

@section('title', 'Editar Livro')
@section('body-class', 'admin-page')

@push('styles')
<style>
    body.admin-page {
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        min-height: 100vh;
        font-size: 1.5rem;
    }
    .form-shell {
        max-width: 1100px;
        margin: 3rem auto;
        padding: 0 1.5rem;
    }
    .form-card {
        background: rgba(17, 24, 39, 0.8);
        padding: 3rem;
        border-radius: 24px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }
    
    h1 { font-size: 3rem; font-weight: 700; margin-bottom: 2.5rem; color: white; }
    .form-group { margin-bottom: 2.5rem; }
    
    label {
        display: block;
        margin-bottom: 1rem;
        font-weight: 600;
        color: #d1d5db;
        font-size: 1.7rem;
    }
    
    input[type="text"], input[type="number"], select, textarea {
        width: 100%;
        padding: 1.2rem 1.5rem;
        border-radius: 14px;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255, 255, 255, 0.05);
        color: white;
        font-size: 1.5rem;
        outline: none;
        min-height: 65px;
    }
    
    input:focus, select:focus, textarea:focus { border-color: #6366f1; background: rgba(255, 255, 255, 0.08); }
    select option { background-color: #1f2937; color: white; font-size: 1.5rem; }
    textarea { resize: vertical; min-height: 200px; }

    .current-cover {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(255,255,255,0.05);
        display: inline-block;
        border-radius: 12px;
    }
    .current-cover img { height: 150px; display: block; border-radius: 6px; }

    .form-actions {
        display: flex; justify-content: flex-end; gap: 1.5rem;
        margin-top: 4rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);
    }

    .btn {
        padding: 1.2rem 3rem; border-radius: 99px; font-weight: 600;
        text-decoration: none; cursor: pointer; border: none; font-size: 1.5rem;
    }
    .btn-cancel { background: transparent; color: #9ca3af; border: 2px solid rgba(255,255,255,0.1); }
    .btn-submit { background: linear-gradient(120deg, #6366f1, #8b5cf6); color: white; }
</style>
@endpush

@section('content')
<div class="form-shell">
    <div class="form-card">
        <h1>Editar Livro: {{ $book->name }}</h1>
        
        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row" style="display: flex; gap: 2rem; flex-wrap: wrap;">
                <div class="col" style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label>Título do Livro</label>
                        <input type="text" name="name" value="{{ old('name', $book->name) }}" required>
                    </div>
                </div>
                <div class="col" style="flex: 1; min-width: 300px;">
                    <div class="form-group">
                        <label>Autor</label>
                        <input type="text" name="author" value="{{ old('author', $book->author) }}" required>
                    </div>
                </div>
            </div>

            <div class="row" style="display: flex; gap: 2rem; flex-wrap: wrap;">
                <div class="col" style="flex: 1; min-width: 250px;">
                    <div class="form-group">
                        <label>Preço (€)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $book->price) }}" required>
                    </div>
                </div>
                <div class="col" style="flex: 1; min-width: 250px;">
                    <div class="form-group">
                        <label>Stock Disponível</label>
                        {{-- Alterado para 'available_stock' para bater certo com o Controller --}}
                        <input type="number" name="available_stock" value="{{ old('available_stock', $book->available_stock) }}" min="0" required>
                    </div>
                </div>
                <div class="col" style="flex: 1; min-width: 250px;">
                    <div class="form-group">
                        <label>Categoria</label>
                        {{-- Alterado para 'category_name' para bater certo com o Controller --}}
                        <select name="category_name" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category_name }}" @selected($book->category_name == $cat->category_name)>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Capa do Livro</label>
                @if($book->image)
                    <div class="current-cover">
                        <img src="{{ asset('storage/' . $book->image) }}" alt="Capa atual">
                        <span style="display: block; text-align: center; font-size: 1.2rem; margin-top: 0.5rem; color: #9ca3af;">Atual</span>
                    </div>
                @endif
                {{-- Alterado para 'image' para bater certo com o Controller --}}
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="form-group">
                <label>Sinopse</label>
                <textarea name="synopsis" rows="5">{{ old('synopsis', $book->synopsis) }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-cancel">Cancelar</a>
                <button type="submit" class="btn btn-submit">Guardar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection
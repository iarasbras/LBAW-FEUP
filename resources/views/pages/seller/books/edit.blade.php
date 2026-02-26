@extends('layouts.app')

@section('title', 'Editar Livro')
@section('body-class', 'seller-page')

@push('styles')
<style>
    body.seller-page {
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
    }
    .form-shell {
        max-width: 800px;
        margin: 3rem auto;
        padding: 0 1.5rem;
    }
    .form-card {
        background: rgba(17, 24, 39, 0.8);
        padding: 2.5rem;
        border-radius: 24px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }
    
    h1 { font-size: 2.5rem; font-weight: 700; margin-bottom: 2rem; color: white; }
    
    .form-group { margin-bottom: 1.5rem; }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #d1d5db;
        font-size: 1.75rem;
    }
    
    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 1rem 1.4rem;
        border-radius: 14px;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255, 255, 255, 0.05);
        color: white;
        font-size: 1.5rem;
        outline: none;
        transition: border-color 0.2s;
    }
    
    input:focus, select:focus, textarea:focus {
        border-color: #6366f1;
        background: rgba(255, 255, 255, 0.08);
    }

    /* --- CORREÇÃO AQUI: Estilo das opções do Dropdown --- */
    select option {
        background-color: #1f2937; /* Cinzento escuro igual ao dashboard */
        color: white;
        padding: 10px;
    }

    textarea { resize: vertical; min-height: 120px; }

    /* File Input Personalizado */
    input[type="file"] {
        padding: 0.6rem;
        background: rgba(255, 255, 255, 0.05);
        font-size: 0.9rem;
    }

    .row { display: flex; gap: 1.5rem; }
    .col { flex: 1; }

    .current-cover {
        margin-bottom: 1rem;
        padding: 0.5rem;
        background: rgba(255,255,255,0.05);
        display: inline-block;
        border-radius: 8px;
    }
    .current-cover img { height: 80px; display: block; border-radius: 4px; }
    .current-cover span { display: block; font-size: 0.8rem; color: #9ca3af; margin-top: 0.2rem; text-align: center;}

    /* Botões */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .btn {
        padding: 0.8rem 1.8rem;
        border-radius: 99px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        font-size: 1.5rem;
        transition: transform 0.2s;
    }
    
    .btn-cancel {
        background: transparent;
        color: #9ca3af;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .btn-cancel:hover { background: rgba(255,255,255,0.05); color: white; }

    .btn-submit {
        background: linear-gradient(120deg, #6366f1, #8b5cf6);
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }
    .btn-submit:hover { transform: translateY(-2px); }
</style>
@endpush

@section('content')
<div class="form-shell">
    <div class="form-card">
        <h1>Editar Livro: {{ $book->name }}</h1>
        
        <form action="{{ route('seller.books.update', $book) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            {{-- Linha 1: Título e Autor --}}
            <div class="row">
                <div class="col form-group">
                    <label>Título do Livro</label>
                    <input type="text" name="name" value="{{ old('name', $book->name) }}" required>
                </div>
                <div class="col form-group">
                    <label>Autor</label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" required>
                </div>
            </div>

            {{-- Linha 2: Preço, Stock e Categoria --}}
            <div class="row">
                <div class="col form-group">
                    <label>Preço (€)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $book->price) }}" required>
                </div>
                <div class="col form-group">
                    <label>Stock Disponível</label>
                    <input type="number" name="stock" value="{{ old('stock', $book->available_stock) }}" min="0" required>
                </div>
                <div class="col form-group">
                    <label>Categoria</label>
                    <select name="category_id" required>
                        @foreach($categories as $cat)
                            {{-- Nota: Usamos category_name no value porque o teu controller espera isso --}}
                            <option value="{{ $cat->category_name }}" @selected($book->category_name == $cat->category_name)>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Imagem de Capa --}}
            <div class="form-group">
                <label>Capa do Livro</label>
                
                @if($book->image)
                    <div class="current-cover">
                        <img src="{{ asset('storage/' . $book->image) }}" alt="Capa atual">
                        <span>Atual</span>
                    </div>
                @endif
                
                <input type="file" name="cover_image" accept="image/*">
                <small style="color: #6b7280; display:block; margin-top:0.3rem;">Deixa vazio se não quiseres alterar a imagem.</small>
            </div>

            {{-- Sinopse --}}
            <div class="form-group">
                <label>Sinopse</label>
                <textarea name="synopsis" rows="5">{{ old('synopsis', $book->synopsis) }}</textarea>
            </div>

            {{-- Botões --}}
            <div class="form-actions">
                <a href="{{ route('seller.dashboard') }}" class="btn btn-cancel">Cancelar</a>
                <button type="submit" class="btn btn-submit">Guardar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection
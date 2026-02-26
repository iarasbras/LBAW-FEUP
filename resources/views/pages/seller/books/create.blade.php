@extends('layouts.app')

@section('title', 'Vender Novo Livro')
@section('body-class', 'seller-page')

@push('styles')
<style>
    body.seller-page {
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
    }
    .form-shell {
        max-width: 900px;
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
    
    h1 { 
        font-size: 2.2rem; 
        font-weight: 700; 
        margin-bottom: 2.5rem; 
        color: white; 
    }
    
    .form-group { margin-bottom: 2rem; }
    
    label {
        display: block;
        margin-bottom: 0.8rem;
        font-weight: 600;
        color: #e5e7eb;
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

    select option {
        background-color: #1f2937; 
        color: white;
        padding: 10px;
    }

    textarea { resize: vertical; min-height: 150px; }

    input[type="file"] {
        padding: 0.8rem;
        background: rgba(255, 255, 255, 0.05);
        font-size: 1rem;
        height: auto;
    }

    .row { display: flex; gap: 2rem; flex-wrap: wrap; }
    .col { flex: 1; min-width: 250px; }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1.5rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .btn {
        padding: 1rem 2.5rem; 
        border-radius: 99px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        font-size: 1.1rem;
        transition: transform 0.2s;
    }
    
    .btn-cancel {
        background: transparent;
        color: #9ca3af;
        border: 2px solid rgba(255,255,255,0.1);
    }
    .btn-cancel:hover { background: rgba(255,255,255,0.05); color: white; border-color: white; }

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
        <h1>Vender Novo Livro</h1>
        
        <form action="{{ route('seller.books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Linha 1: Título e Autor --}}
            <div class="row">
                <div class="col form-group">
                    <label>Título do Livro</label>
                    <input type="text" name="name" placeholder="Ex: Os Lusíadas" value="{{ old('name') }}" required>
                </div>
                <div class="col form-group">
                    <label>Autor</label>
                    <input type="text" name="author" placeholder="Ex: Luís de Camões" value="{{ old('author') }}" required>
                </div>
            </div>

            {{-- Linha 2: Preço, Stock e Categoria --}}
            <div class="row">
                <div class="col form-group">
                    <label>Preço (€)</label>
                    <input type="number" step="0.01" name="price" placeholder="0.00" value="{{ old('price') }}" required>
                </div>
                <div class="col form-group">
                    <label>Stock Inicial</label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}" min="1" required>
                </div>
                <div class="col form-group">
                    <label>Categoria</label>
                    <select name="category_id" required>
                        <option value="" disabled selected>Selecionar...</option>
                        @foreach($categories as $cat)
                            {{-- Envia o NOME da categoria pois o controller espera category_name --}}
                            <option value="{{ $cat->category_name }}" @selected(old('category_id') == $cat->category_name)>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Imagem de Capa --}}
            <div class="form-group">
                <label>Capa do Livro</label>
                <input type="file" name="cover_image" accept="image/*">
            </div>

            {{-- Sinopse --}}
            <div class="form-group">
                <label>Sinopse</label>
                <textarea name="synopsis" rows="5" placeholder="Escreve um breve resumo do livro...">{{ old('synopsis') }}</textarea>
            </div>

            {{-- Botões --}}
            <div class="form-actions">
                <a href="{{ route('seller.dashboard') }}" class="btn btn-cancel">Cancelar</a>
                <button type="submit" class="btn btn-submit">Colocar à Venda</button>
            </div>
        </form>
    </div>
</div>
@endsection
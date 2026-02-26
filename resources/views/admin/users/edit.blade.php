@extends('layouts.app')

@section('title', 'Liberato · Administração · Editar Utilizador')
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
    .admin-shell { max-width: 1000px; margin: 3rem auto; padding: 0 1.5rem; }
    
    .admin-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 2rem; background: rgba(17, 24, 39, 0.8);
        padding: 2rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }
    .admin-header h1 { margin: 0; font-size: 2.5rem; font-weight: 700; }

    .form-card {
        background: rgba(17, 24, 39, 0.6); padding: 3rem; border-radius: 24px;
        border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .form-footer {
        display: flex; justify-content: flex-end; gap: 1.5rem;
        margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);
    }

    .btn {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 1rem 2.5rem; border-radius: 99px; font-weight: 600;
        font-size: 1.5rem; cursor: pointer; border: none; transition: 0.2s; text-decoration: none;
    }
    .btn-primary { background: linear-gradient(120deg, #6366f1, #8b5cf6); color: white; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(99,102,241,0.4); }
    .btn-ghost { background: rgba(255,255,255,0.1); color: white; }
    .btn-ghost:hover { background: rgba(255,255,255,0.15); }
</style>
@endpush

@section('content')
<div class="admin-shell">
    <div class="admin-header">
        <div>
            <h1>Editar Utilizador</h1>
            <p style="color: #9ca3af; font-size: 1.5rem; margin-top: 0.5rem;">A gerir perfil de: {{ $user->username }}</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">← Voltar à lista</a>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            @include('admin.users.partials.form', ['user' => $user])

            <div class="form-footer">
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection
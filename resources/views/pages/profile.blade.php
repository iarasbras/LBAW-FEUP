@extends('layouts.app')

@section('title', 'Liberato · Perfil')
@section('body-class', 'profile-page')

@push('styles')
<style>
    body.profile-page {
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
    }

    body.profile-page main {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
    }

    body.profile-page main > header {
        display: none;
    }

    body.profile-page #content {
        width: 100%;
        max-width: 720px;
    }

    .profile-card {
        background: rgba(17, 24, 39, 0.9);
        border-radius: 26px;
        padding: 3rem;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45);
    }

    .profile-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .avatar {
        width: 120px;
        height: 120px;
        border-radius: 999px;
        margin: 0 auto 1rem;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.25);
        background: rgba(255, 255, 255, 0.08);
    }

    .back-link {
        border-radius: 999px;
        padding: 0.6rem 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
    }

    .profile-form .field {
        margin-bottom: 1.5rem;
    }

    .profile-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-form input[type="text"],
    .profile-form input[type="email"],
    .profile-form input[type="password"],
    .profile-form input[type="file"] {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: inherit;
    }

    .profile-form input:focus {
        border-color: #818cf8;
        background: rgba(255, 255, 255, 0.08);
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
    }

    .profile-form button {
        width: 100%;
        padding: 0.95rem 1rem;
        border: none;
        border-radius: 999px;
        font-size: 1rem;
        font-weight: 600;
        color: #f9fafb;
        background: linear-gradient(120deg, #6366f1, #8b5cf6);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-align: center;
    }

    .profile-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 35px rgba(99, 102, 241, 0.35);
    }
    
    .btn-danger {
        background: rgba(239, 68, 68, 0.1) !important;
        border: 1px solid #ef4444 !important;
        color: #ef4444 !important;
    }
    .btn-danger:hover {
        background: #ef4444 !important;
        color: white !important;
    }

    .error {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.9rem;
        color: #f87171;
    }

    .success {
        margin-top: 1rem;
        text-align: center;
        color: #34d399;
    }
</style>
@endpush

@section('content')
<section class="profile-card">
    <div class="profile-nav" style="align-items:center;">
        <div class="profile-nav" style="align-items:center;">
          <a class="back-link" href="{{ route('catalog.index') }}">← Voltar ao catálogo</a>
        
          <div style="display:flex; gap:0.75rem; align-items:center;">
                {{-- Link para o Histórico (Vindo do branch do colega/rebase) --}}
                <a class="back-link" href="{{ route('profile.orders') }}">Histórico de Compras</a>
            
                {{-- Link para o Carrinho --}}
                <a class="back-link" href="{{ url('/cart') }}">Carrinho ({{ array_sum(session('cart', [])) }})</a>
            
                {{-- Notificações --}}
                <a class="back-link" href="{{ route('notifications.index') }}" style="position:relative; display:inline-flex; align-items:center; gap:0.5rem;" id="notificationBell">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width:20px;height:20px;fill:currentColor;">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                    
                    <span class="notification-badge" id="notificationCount" style="display:none;">0</span>
                </a>
            
                <a class="back-link" href="{{ route('logout') }}">Terminar sessão</a>
            </div>
        </div>
    </div>

    <div class="profile-header">
        <img
            class="avatar"
            src="{{ $user->profile_img_url ? Storage::url($user->profile_img_url) : asset('images/avatar-placeholder.svg') }}"
            alt="Avatar do utilizador"
        >
        <h1>Olá, {{ $user->username }}</h1>
        <p>Atualiza os teus dados e personaliza a tua experiência na Liberato.</p>
    </div>

    <form class="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="field">
            <label for="username">Nome de utilizador</label>
            <input
                type="text"
                id="username"
                name="username"
                value="{{ old('username', $user->username) }}"
                required
                autocomplete="username"
            >
            @error('username')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label for="email">E-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="email"
            >
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label for="profile_img">Foto de perfil</label>
            <input type="file" id="profile_img" name="profile_img" accept="image/*">
            @if ($user->profile_img_url)
                <label style="display:flex;align-items:center;gap:0.5rem;margin-top:0.75rem;">
                    <input type="checkbox" name="remove_profile_img">
                    <span>Remover imagem atual</span>
                </label>
            @endif
            @error('profile_img')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label for="password">Nova password</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="new-password"
            >
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirmar nova password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                autocomplete="new-password"
            >
        </div>

        <button type="submit">Guardar alterações</button>

        @if (session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif
    </form>

    <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
        <h3 style="color: #ef4444; font-size: 1.1rem; margin: 0 0 0.5rem;">Zona de Perigo</h3>
        <p style="color: #9ca3af; font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.5;">
            Ao eliminares a conta, os teus dados pessoais serão anonimizados e perderás o acesso ao histórico de encomendas.
        </p>
        
        <form class="profile-form" action="{{ route('delete-account.destroy') }}" method="POST" onsubmit="return confirm('Tens a certeza que queres eliminar a tua conta? Esta ação é irreversível.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">Eliminar Conta</button>
        </form>
    </div>
</section>
@endsection

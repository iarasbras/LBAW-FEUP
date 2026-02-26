@extends('layouts.app')

@section('title', 'Liberato · Registo')
@section('body-class', 'auth-page')

@push('styles')
<style>
    body.auth-page {
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
    }

    body.auth-page main {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
    }

    body.auth-page main > header {
        display: none;
    }

    body.auth-page #content {
        width: 100%;
        max-width: 420px;
    }

    .auth-card {
        background: rgba(17, 24, 39, 0.85);
        border-radius: 20px;
        padding: 2.75rem 2.5rem;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.45);
    }

    .auth-card h1 {
        margin: 0;
        font-size: 2rem;
    }

    .auth-card__lead {
        margin: 0.75rem 0 2rem;
        color: #d1d5db;
        line-height: 1.5;
    }

    .auth-form .field {
        margin-bottom: 1.5rem;
    }

    .auth-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .auth-form input[type="text"],
    .auth-form input[type="email"],
    .auth-form input[type="password"] {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: inherit;
        transition: border-color 0.2s ease, background 0.2s ease;
    }

    .auth-form input:focus {
        border-color: #818cf8;
        background: rgba(255, 255, 255, 0.08);
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
    }

    .auth-form button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.95rem 1rem;
        border: none;
        border-radius: 999px;
        font-size: 1rem;
        font-weight: 600;
        color: #f9fafb;
        background: linear-gradient(120deg, #10b981, #059669);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .auth-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 35px rgba(16, 185, 129, 0.35);
    }

    .login-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 1.2rem;
        color: #a5b4fc;
        text-decoration: none;
        font-weight: 600;
    }

    .login-link:hover {
        color: #c7d2fe;
    }

    body.auth-page .error {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.9rem;
        color: #f87171;
    }
</style>
@endpush

@section('content')
<section class="auth-card">
    <h1>Criar conta na Liberato</h1>
    <p class="auth-card__lead">
        Junta-te à comunidade para guardares favoritos, criar listas e receberes recomendações personalizadas.
    </p>

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
            <label for="username">Nome de utilizador</label>
            <input
                id="username"
                name="username"
                type="text"
                value="{{ old('username') }}"
                required
                autofocus
                autocomplete="username"
            >
            @error('username')
                <span id="username-error" class="error" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="field">
            <label for="email">E-mail</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                inputmode="email"
            >
            @error('email')
                <span id="email-error" class="error" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="new-password"
            >
            @error('password')
                <span id="password-error" class="error" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirmar password</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
            >
        </div>

        <button type="submit">
            <span>Criar conta</span>
        </button>

        <a class="login-link" href="{{ route('login') }}">
            Já tens conta? Inicia sessão
        </a>
    </form>
</section>
@endsection
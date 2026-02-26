@extends('layouts.app')

@section('title', 'Liberato · Recuperar Password')
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

    .auth-form input[type="email"] {
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
        background: linear-gradient(120deg, #6366f1, #8b5cf6);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .auth-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 35px rgba(99, 102, 241, 0.35);
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 1.5rem;
        color: #9ca3af;
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #f9fafb;
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
        background: rgba(52, 211, 153, 0.1);
        padding: 0.75rem;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<section class="auth-card">
    <h1>Recuperar Password</h1>
    <p class="auth-card__lead">
        Indique o seu email para receber o link de recuperação.
    </p>

    @if (session('status'))
        <div class="success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label for="email">E-mail</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
            >
            @error('email')
                <span class="error" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <button type="submit">
            Enviar Link de Recuperação
        </button>

        <div style="text-align: center;">
            <a class="back-link" href="{{ route('login') }}">
                ← Voltar ao Login
            </a>
        </div>
    </form>
</section>
@endsection
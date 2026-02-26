@extends('layouts.app')

@section('title', 'Liberato · Login')
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

    .auth-card__eyebrow {
        letter-spacing: 0.2em;
        text-transform: uppercase;
        font-size: 0.8rem;
        color: #9ca3af;
        margin-bottom: 0.75rem;
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

    .auth-form .remember {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.95rem;
        color: #d1d5db;
    }

    .auth-form .remember input {
        width: auto;
        margin: 0;
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

    .register-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 1.2rem;
        color: #a5b4fc;
        text-decoration: none;
        font-weight: 600;
    }

    .register-link:hover {
        color: #c7d2fe;
    }

    .skip-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 1.2rem;
        color: #a5b4fc;
        text-decoration: none;
    }
    
    .forgot-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0;
        color: #a5b4fc;
        text-decoration: none;
        translate: 0 -1.5rem;
        font-size: small;
    }
    
    .register-link:hover {
        color: #c7d2fe;
    }

    body.auth-page .error {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.9rem;
        color: #f87171;
    }

    body.auth-page .success {
        margin-top: 1rem;
        text-align: center;
        color: #34d399;
    }
</style>
@endpush

@php
    $supportEmail = \App\Http\Controllers\PlatformInformationController::getValue('support_email') ?? 'help@liberato.com';
@endphp

@section('content')
<section class="auth-card">
    <h1>Entrar na Liberato</h1>
    <p class="auth-card__lead">
        Acede à tua conta para continuares a explorar o catálogo de livros e gerir os teus favoritos.
    </p>

    <form class="auth-form" method="POST" action="{{ route('login') }}">
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
                inputmode="email"
                autocomplete="email"
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
                autocomplete="current-password"
            >
            <a class="forgot-link" href="{{ route('password.request') }}">
                Esqueci-me da palavra-passe
            </a>
        </div>

        <button type="submit">
            <span>Iniciar sessão</span>
        </button>

        <a class="register-link" href="{{ route('register') }}">
            Ainda não tens conta? Regista-te
        </a>

        <a class="skip-link" href="{{ route('catalog.index') }}">
            Continuar sem registo
        </a>
        
        @error('blocked')
            <a class="error" href="mailto:{{ $supportEmail }}">
                Recorrer do bloqueio da conta
            </a>
        @enderror

        @if (session('status'))
            <p class="success" role="status">{{ session('status') }}</p>
        @endif
    </form>
</section>
@endsection
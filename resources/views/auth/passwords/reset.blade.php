@extends('layouts.app')

@section('title', 'Liberato · Redefinir Password')
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
        margin: 0 0 2rem;
        font-size: 2rem;
    }

    .auth-form .field {
        margin-bottom: 1.5rem;
    }

    .auth-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .auth-form input {
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

    .error {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.9rem;
        color: #f87171;
    }
</style>
@endpush

@section('content')
<section class="auth-card">
    <h1>Redefinir Password</h1>

    <form class="auth-form" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label for="email">E-mail</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ $email ?? old('email') }}"
                required
                autofocus
            >
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label for="password">Nova Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
            >
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label for="password-confirm">Confirmar Password</label>
            <input id="password-confirm" type="password" name="password_confirmation" required>
        </div>

        <button type="submit">
            Alterar Password
        </button>
    </form>
</section>
@endsection
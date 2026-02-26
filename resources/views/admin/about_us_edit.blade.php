@extends('layouts.app')

@section('title', 'Liberato · Sobre Nós')
@section('body-class', 'about-page')

@php
    $aboutUsTitle = $info->get('about_us_title') ?? 'Sobre Nós';
    $aboutUsDescription = $info->get('about_us_long') ?? 'Bem-vindo(a) à Liberato.';
    $supportEmail = $info->get('support_email') ?? 'help@liberato.com';
    $supportPhoneNumber = $info->get('support_phone') ?? '123456789';
@endphp

@push('styles')
<style>
    body.about-page {
        /* These styles are already defined below, keeping the more complete definition */
    }

    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #d1d5db;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(31, 41, 55, 0.8);
        color: #f9fafb;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.3);
    }
    .form-group textarea {
        min-height: 150px;
        resize: vertical;
        font-family: inherit;
    }

    .submit-button {
        background: linear-gradient(120deg, #6366f1, #8b5cf6);
        border: none;
        cursor: pointer;
    }
    .submit-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    }

    body.about-page main {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
    }

    body.about-page main > header {
        display: none;
    }

    body.about-page #content {
        width: 100%;
        max-width: 900px;
    }

    h3 {
        margin: 0;
        font-size: 1.75rem;
    }

    .about-shell {
        background: rgba(17, 24, 39, 0.9);
        border-radius: 26px;
        padding: 3rem;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45);
    }

    .logout-link {
        border-radius: 999px;
        padding: 0.6rem 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .logout-link:hover {
        background: rgba(255, 255, 255, 0.1);
        /* transform: translateY(-1px); */
    }

    .logout-link.active {
        background: rgba(239, 68, 68, 0.2);
        border-color: #ef4444;
        color: #fca5a5;
    }

    .back-link {
        display: inline-block;
        margin-top: 2rem;
        color: #818cf8;
        text-decoration: none;
        font-weight: 600;
    }
    
    .back-link:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
@php
    $isAdmin = auth('admin')->check();
    $adminUser = auth('admin')->user();
@endphp

<section class="about-shell">
    <form method="POST" action="{{ route('admin.platform-info.update') }}">
        @csrf

        <div class="form-group">
            <label for="about_us_title">Título da Página "Sobre Nós"</label>
            <input type="text" id="about_us_title" name="about_us_title" value="{{ old('about_us_title', $aboutUsTitle) }}" required>
        </div>

        <div class="form-group">
            <label for="about_us_long">Descrição Longa "Sobre Nós"</label>
            <textarea id="about_us_long" name="about_us_long" required>{{ old('about_us_long', $aboutUsDescription) }}</textarea>
        </div>

        <div class="form-group">
            <label for="support_email">Email de Suporte</label>
            <input type="email" id="support_email" name="support_email" value="{{ old('support_email', $supportEmail) }}" required>
        </div>

        <div class="form-group">
            <label for="support_phone">Telefone de Suporte</label>
            <input type="text" id="support_phone" name="support_phone" value="{{ old('support_phone', $supportPhoneNumber) }}" required>
        </div>

        <div style="text-align: center; margin-top: 2.5rem; display: flex; justify-content: center; align-items: center; gap: 1rem;">
            <button type="submit" class="logout-link submit-button">Guardar Alterações</button>
            <a class="back-link" href="{{ route('admin.dashboard') }}" style="margin-top: 0;">Cancelar</a>
        </div>
    </form>
</section>
@endsection
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
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
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

    .catalog-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        gap: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 1.5rem;
    }

    .catalog-topbar h2 {
        margin: 0;
        font-size: 2.00rem;
        font-weight: 600;
        font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }

    .topbar-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .logout-link {
        border-radius: 999px;
        padding: 0.6rem 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        background: transparent; 
        cursor: pointer;
        text-transform: none; 
    }

    .logout-link:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-1px);
    }

    .logout-form { display: inline; margin: 0; padding: 0; }
    .logout-form button { font-family: inherit; font-size: inherit; }

    .logout-link.active {
        background: rgba(239, 68, 68, 0.2);
        border-color: #ef4444;
        color: #fca5a5;
    }

    .about-hero {
        text-align: center;
        margin-bottom: 3rem;
    }

    .about-hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        background: linear-gradient(120deg, #a5b4fc, #c7d2fe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .about-hero p {
        font-size: 1.2rem;
        line-height: 1.7;
        color: #d1d5db;
        max-width: 700px;
        margin: 0 auto;
    }

    .contact-card {
        background: linear-gradient(165deg, rgba(31, 41, 55, 0.6), rgba(17, 24, 39, 0.6));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.1rem;
        color: #e5e7eb;
    }

    .contact-icon {
        color: #818cf8;
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

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.35rem 0.9rem;
        border-radius: 999px;
        background: rgba(99, 102, 241, 0.15);
        font-size: 1rem;
        color: #c7d2fe;
        font-weight: 600;
    }

    .about-description {
        text-align: justify;
    }
</style>
@endpush

@section('content')
@php
    $isAdmin = auth('admin')->check();
    $adminUser = auth('admin')->user();
@endphp

<section class="about-shell">
    <div class="catalog-topbar">
        <h2>Olá, {{ $isAdmin ? (auth('admin')->user()->name) : (auth()->user()->username ?? 'Visitante') }}</h2>
        <div class="topbar-actions">
            @if($isAdmin)
                <span class="badge">Admin Mode</span>
                <a class="logout-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">@csrf <button class="logout-link">Terminar Sessao</button></form>
            @else
                <a class="logout-link" href="{{ url('/cart') }}">Carrinho (<span id="cart-items-count">{{ array_sum(session('cart', [])) }}</span>)</a>
                @auth
                    @if(\Illuminate\Support\Facades\DB::table('seller')->where('seller_id', auth()->id())->exists())
                        <a class="logout-link" href="{{ route('seller.dashboard') }}" style="color:#a5b4fc; border-color:#6366f1;">Painel Vendedor</a>
                    @endif
                    <a class="logout-link" href="{{ route('notifications.index') }}">🔔</a>
                    <a class="logout-link" href="{{ route('profile.show') }}">Perfil</a>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">@csrf <button class="logout-link">Terminar Sessao</button></form>
                @else
                    <a class="logout-link" href="{{ route('login') }}">Entrar</a>
                @endauth
            @endif
        </div>
    </div>

    <div class="about-hero">
        <h1>{{ $aboutUsTitle }}</h1>
        <p class="about-description">{!! nl2br(e($aboutUsDescription)) !!}</p>
    </div>

    <div class="contact-card">
        <h3>Precisa de ajuda?</h3>
        
        <div class="contact-item">
            <svg class="contact-icon" fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 8L10.89 13.26C11.2187 13.4793 11.6049 13.5963 12 13.5963C12.3951 13.5963 12.7813 13.4793 13.11 13.26L21 8M5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7C21 6.46957 20.7893 5.96086 20.4142 5.58579C20.0391 5.21071 19.5304 5 19 5H5C4.46957 5 3.96086 5.21071 3.58579 5.58579C3.21071 5.96086 3 6.46957 3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
        </div>

        <div class="contact-item">
            <svg class="contact-icon" fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 16.92V19.92C22.0011 20.1986 21.9441 20.4742 21.8325 20.7294C21.7209 20.9846 21.5573 21.2137 21.3521 21.402C21.1468 21.5902 20.9046 21.7336 20.6407 21.8228C20.3769 21.912 20.0974 21.9452 19.82 21.92C16.7428 21.5857 13.787 20.5342 11.19 18.85C8.77382 17.2436 6.72533 15.1951 5.11999 12.7799C3.43398 10.1793 2.38459 7.22005 2.05999 4.13999C2.03466 3.86226 2.06793 3.58243 2.15726 3.31825C2.24659 3.05407 2.39003 2.81148 2.57837 2.60613C2.76671 2.40078 2.99586 2.23729 3.25113 2.12596C3.5064 2.01462 3.78211 1.95789 4.05999 1.95999H7.05999C7.54604 1.95556 8.01652 2.12869 8.38466 2.44734C8.7528 2.76598 8.99395 3.20858 9.06399 3.69999C9.19472 4.69036 9.43715 5.66314 9.78999 6.59999C9.93179 6.97445 9.96248 7.38268 9.87823 7.77333C9.79398 8.16398 9.59857 8.51997 9.31699 8.79599L8.04699 10.066C9.46959 12.5677 11.5323 14.6304 14.034 16.053L15.304 14.783C15.58 14.5014 15.936 14.306 16.3267 14.2218C16.7173 14.1375 17.1255 14.1682 17.5 14.31C18.4368 14.6628 19.4096 14.9053 20.4 15.036C20.896 15.1068 21.342 15.3516 21.6617 15.724C21.9814 16.0964 22.1531 16.5714 22.15 17.06V16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>{{ $supportPhoneNumber }}</span>
        </div>
    </div>

    <div style="text-align: center;">
        <a class="back-link" href="{{ route('home') }}">← Voltar</a>
    </div>
</section>
@endsection
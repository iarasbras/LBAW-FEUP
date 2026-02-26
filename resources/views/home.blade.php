@php
    $platformName = $info->get('site_name') ?? config('app.name', 'Liberato');
    $platformDescription = $info->get('about_us') ?? 'Bem-vindo(a) à Liberato.';
@endphp
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Liberato') }}</title>
    <style>
        :root { color-scheme: dark; }
        body {
            font-family: "Segoe UI", Helvetica, Arial, sans-serif;
            margin: 0; padding: 0; min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: radial-gradient(circle at top, #111827, #020205);
            color: #f9fafb;
        }
        .card {
            max-width: 540px; padding: 3rem; border-radius: 20px;
            background: rgba(17, 24, 39, 0.8);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            text-align: center;
        }
        h1 { margin-top: 0; font-size: 2.5rem; letter-spacing: 0.05em; }
        .actions {
            display:flex; flex-direction:column; gap:1rem;
            justify-content:center; align-items:center; margin-top: 2rem;
        }
        p { margin: 1rem 0 2rem; line-height: 1.6; color: #d1d5db; }
        a {
            display: inline-flex; width: fit-content; align-items: center; gap: 0.5rem;
            padding: 0.9rem 1.6rem; border-radius: 999px;
            background: linear-gradient(120deg, #6366f1, #8b5cf6);
            color: inherit; text-decoration: none; font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        a:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(99, 102, 241, 0.35); }
    </style>
</head>
<body>
    <main class="card">
        <h1>{{ $platformName ?? 'Liberato' }}</h1>
        <p>{{ $platformDescription ?? 'A tua livraria académica online. Compra e vende livros usados com segurança na comunidade FEUP.' }}</p>
        
        <div class="actions">
            <a href="{{ Auth::check() ? route('catalog.index') : route('login') }}">
                {{ Auth::check() ? 'Ver Catálogo' : 'Entrar na Plataforma' }}
                <svg fill="none" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12H19" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                    <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                </svg>
            </a>
            
            <a href="{{ route('about_us') }}" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1);">
                <svg fill="none" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                    <path d="M12 5L5 12L12 19" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                </svg>
                Sobre Nós
            </a>
        </div>
    </main>
</body>
</html>
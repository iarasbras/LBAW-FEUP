<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/milligram.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print">
        @stack('styles')
        @stack('styles')

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        @stack('scripts')

        <style>
            .notification-bell {
                position: relative;
                cursor: pointer;
                display: inline-block;
            }
            .notification-bell svg {
                width: 24px;
                height: 24px;
                fill: currentColor;
            }
            .notification-badge {
                position: absolute;
                top: -4px;
                right: -4px;
                background: #ef4444;
                color: white;
                border-radius: 10px;
                padding: 0 6px;
                font-size: 11px;
                font-weight: 700;
                min-width: 18px;
                text-align: center;
            }
            .notification-dropdown {
                display: none;
                position: absolute;
                top: 100%;
                right: 0;
                margin-top: 0.5rem;
                background: white;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                width: 350px;
                max-height: 400px;
                overflow-y: auto;
                z-index: 1000;
            }
            .notification-dropdown.show {
                display: block;
            }
            .notification-dropdown-item {
                padding: 1rem;
                border-bottom: 1px solid #e5e7eb;
                color: #111827;
            }
            .notification-dropdown-item:hover {
                background: #f9fafb;
            }
            .notification-dropdown-item.unread {
                background: #eff6ff;
            }
            .notification-dropdown-empty {
                padding: 2rem;
                text-align: center;
                color: #6b7280;
            }
        </style>
    </head>
    <body class="@yield('body-class')">
        {{-- Global header removed; page-level headers will be used where appropriate. --}}

        <main>
            <section id="content">
                {{-- Flash messages (success/errors) --}}
                @if(session('success'))
                    <div style="max-width:980px; margin:1rem auto; padding:0.75rem 1rem; border-radius:8px; background:#16a34a; color:white;">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="max-width:980px; margin:1rem auto; padding:0.75rem 1rem; border-radius:8px; background:#dc2626; color:white;">
                        <strong>Erro(s):</strong>
                        <ul style="margin:0.5rem 0 0 1rem;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </section>
        </main>

        @auth
        <script>
            // Notificações
            (function() {
                const bell = document.getElementById('notificationBell');
                const dropdown = document.getElementById('notificationDropdown');
                const badge = document.getElementById('notificationCount');
                let isOpen = false;

                if (!bell) return;

                // Toggle dropdown
                bell.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isOpen = !isOpen;
                    dropdown.classList.toggle('show', isOpen);
                    if (isOpen) {
                        loadNotifications();
                    }
                });

                document.addEventListener('click', function() {
                    if (isOpen) {
                        isOpen = false;
                        dropdown.classList.remove('show');
                    }
                });

                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // notificações não lidas
                function loadNotifications() {
                    fetch('{{ route("notifications.unread") }}')
                        .then(response => response.json())
                        .then(data => {
                            updateBadge(data.count);
                            renderNotifications(data.notifications);
                        })
                        .catch(err => {
                            console.error('Erro ao carregar notificações:', err);
                        });
                }

                // Atualizar badge
                function updateBadge(count) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                }

                // Renderizar notificações
                function renderNotifications(notifications) {
                    if (notifications.length === 0) {
                        dropdown.innerHTML = '<div class="notification-dropdown-empty">Sem notificações novas</div>';
                        return;
                    }

                    let html = '';
                    notifications.forEach(n => {
                        html += `
                            <div class="notification-dropdown-item unread" data-id="${n.id}">
                                <div style="font-size:0.875rem; color:#6b7280; margin-bottom:0.25rem;">${n.date}</div>
                                <div style="font-size:0.95rem; margin-bottom:0.5rem;">${n.message}</div>
                                <button onclick="markAsRead(${n.id})" style="padding:0.25rem 0.75rem; background:#3b82f6; color:white; border:none; border-radius:4px; cursor:pointer; font-size:0.875rem;">
                                    Marcar como lida
                                </button>
                            </div>
                        `;
                    });
                    
                    html += `
                        <div style="padding:1rem; text-align:center; border-top:2px solid #e5e7eb;">
                            <a href="{{ route('notifications.index') }}" style="color:#3b82f6; font-weight:600; text-decoration:none;">Ver todas</a>
                        </div>
                    `;

                    dropdown.innerHTML = html;
                }

                // Marcar como lida
                window.markAsRead = function(id) {
                    fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadNotifications();
                        }
                    })
                    .catch(err => console.error('Erro ao marcar como lida:', err));
                };

                // Carregar badge inicial
                loadNotifications();

                setInterval(loadNotifications, 30000);
            })();
        </script>
        @endauth
    </body>
</html>
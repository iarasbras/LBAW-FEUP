@extends('layouts.app')

@section('title', 'Liberato · Notificações')
@section('body-class', 'notifications-page')

@push('styles')
<style>
    body.notifications-page {
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        background: radial-gradient(circle at 20% 20%, #111827, #020205 65%);
        color: #f9fafb;
        min-height: 100vh;
    }

    .notifications-shell {
        max-width: 920px;
        margin: 2.5rem auto;
        padding: 0 1.25rem;
    }

    .notifications-card {
        background: rgba(17, 24, 39, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45);
    }

    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .notifications-title {
        margin: 0;
        font-size: 2.1rem;
        font-weight: 800;
        color: #fff;
    }

    .mark-all-btn {
        /* Let .pill-btn control sizing; only override color/look */
        background: linear-gradient(120deg, #10b981, #22c55e);
        border: none;
        color: #fff;
        box-shadow: 0 10px 24px rgba(34, 197, 94, 0.28);
    }

    .mark-all-btn:hover {
        background: linear-gradient(120deg, #0ea271, #1fb157);
        transform: translateY(-1px);
    }

    .flash {
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.25rem;
    }
    .flash-success { background: rgba(16, 185, 129, 0.12); color: #bbf7d0; border: 1px solid rgba(16,185,129,0.35); }

    .notification-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .notification-item {
        padding: 1.25rem 1.25rem 1rem;
        background: linear-gradient(165deg, rgba(31,41,55,0.95), rgba(17,24,39,0.95));
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 14px;
        position: relative;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(0,0,0,0.35);
        border-color: rgba(255,255,255,0.12);
    }

    .notification-item.unread {
        border-color: rgba(96, 165, 250, 0.5);
        box-shadow: 0 16px 32px rgba(59,130,246,0.18);
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #60a5fa, #3b82f6);
        border-radius: 14px 0 0 14px;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
        gap: 0.75rem;
    }

    .notification-type {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.01em;
        text-transform: uppercase;
    }

    .notification-type.order { background: rgba(59,130,246,0.15); color: #bfdbfe; }
    .notification-type.cart { background: rgba(251,191,36,0.18); color: #fde68a; }
    .notification-type.wishlist { background: rgba(236,72,153,0.18); color: #fbcfe8; }
    .notification-type.request { background: rgba(16,185,129,0.18); color: #bbf7d0; }

    .notification-date {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.7);
        white-space: nowrap;
    }

    .notification-message {
        font-size: 1.02rem;
        color: #e5e7eb;
        margin: 0.35rem 0 0.9rem;
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .pill-btn {
        padding: 0.5rem 0.9rem;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.08);
        background: rgba(255,255,255,0.04);
        color: #f9fafb;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.12s ease, background 0.12s ease, border-color 0.12s ease;
    }

    .pill-btn:hover {
        transform: translateY(-1px);
        background: rgba(255,255,255,0.08);
        border-color: rgba(255,255,255,0.18);
    }

    .pill-btn-primary {
        background: linear-gradient(120deg, #3b82f6, #60a5fa);
        border: none;
        box-shadow: 0 10px 24px rgba(59,130,246,0.28);
    }

    /* Controls under the title */
    .notifications-controls {
        display: flex;
        gap: 1rem; /* increase spacing between controls */
        align-items: center;
        margin: 0 0 1rem 0;
        flex-wrap: wrap;
    }

    /* Push the mark-all button to the far right for clear separation */
    .notifications-controls .notifications-mark-all {
        margin-left: auto;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #d1d5db;
    }

    .empty-state h2 {
        font-size: 1.6rem;
        margin-bottom: 0.5rem;
    }

    .empty-actions {
        margin-top: 1rem;
        display: inline-flex;
        gap: 0.5rem;
    }

    .pagination { margin-top: 1.75rem; display:flex; justify-content:center; }
</style>
@endpush

@section('content')
<div class="notifications-shell">
    <div class="notifications-card">
        <div class="notifications-header">
            <h1 class="notifications-title">Notificações</h1>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <!-- Quick navigation -->
                <a class="pill-btn" href="{{ route('cart.index') }}">Carrinho</a>
                <a class="pill-btn" href="{{ route('profile.show') }}">Perfil</a>
                <a class="pill-btn" href="{{ route('catalog.index') }}">Catálogo</a>
            </div>
        </div>

        <div class="notifications-controls">
            @if(!($showAll ?? false))
                <a class="pill-btn" href="{{ route('notifications.index', ['show_all' => 1]) }}">Ver todas</a>
            @else
                <a class="pill-btn" href="{{ route('notifications.index', ['show_all' => 0]) }}">Ver só atuais</a>
            @endif
            @if($notifications->total() > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}" style="display:inline;" class="notifications-mark-all">
                    @csrf
                    <button type="submit" class="pill-btn mark-all-btn">Marcar todas como lidas</button>
                </form>
            @endif
        </div>

        @if($notifications->isEmpty())
            <div class="empty-state">
                <h2>Sem notificações por agora</h2>
                <p>Quando houver novidades, aparecem aqui.</p>
                <div class="empty-actions">
                    <a class="pill-btn" href="{{ route('catalog.index') }}">Explorar catálogo</a>
                    <a class="pill-btn" href="{{ route('cart.index') }}">Ir ao carrinho</a>
                </div>
            </div>
        @else
            <div class="notification-list" id="notificationList">
                @foreach($notifications as $notification)
                    <div class="notification-item {{ $notification->is_read ? '' : 'unread' }}" id="notif-{{ $notification->notification_id }}">
                        <div class="notification-header">
                            <span class="notification-type {{ strtolower(str_replace('Notification', '', $notification->type)) }}">
                                @switch($notification->type)
                                    @case('OrderStatusNotification') Encomenda @break
                                    @case('PaymentApprovedNotification') Pagamento @break
                                    @case('CartPriceNotification') Carrinho @break
                                    @case('WishlistedOnSaleNotification') Wishlist @break
                                    @case('RequestResolvedNotification') Suporte @break
                                    @default Notificação @break
                                @endswitch
                            </span>
                            <span class="notification-date">{{ $notification->date->diffForHumans() }}</span>
                        </div>

                        <div class="notification-message">{{ $notification->getFormattedMessage() }}</div>

                        <div class="notification-actions">
                            @if(!$notification->is_read)
                                <button type="button" class="pill-btn" onclick="markAsReadPage({{ $notification->notification_id }})">Marcar como lida</button>
                            @endif

                            @if($notification->order_id)
                                <a class="pill-btn pill-btn-primary" href="{{ route('order.show', $notification->order_id) }}">Ver encomenda</a>
                            @endif

                            @if($notification->book_id)
                                <a class="pill-btn pill-btn-primary" href="{{ route('catalog.show', $notification->book_id) }}">Ver livro</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination" id="paginationBlock">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function markAsReadPage(id) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.success) {
                const el = document.getElementById(`notif-${id}`);
                if (el) {
                    el.remove();
                }
                const list = document.getElementById('notificationList');
                if (list && list.children.length === 0) {
                    // Show empty state if all removed
                    const card = document.querySelector('.notifications-card');
                    if (card) {
                        card.innerHTML = `
                            <div class="notifications-header">
                                <h1 class="notifications-title">Notificações</h1>
                            </div>
                            <div class="empty-state">
                                <h2>Sem notificações por agora</h2>
                                <p>Quando houver novidades, aparecem aqui.</p>
                                <div class="empty-actions">
                                    <a class="pill-btn" href="{{ route('catalog.index') }}">Explorar catálogo</a>
                                    <a class="pill-btn" href="{{ route('cart.index') }}">Ir ao carrinho</a>
                                </div>
                            </div>
                        `;
                    }
                }
            }
        })
        .catch(err => console.error('Erro ao marcar como lida:', err));
    }
</script>
@endpush

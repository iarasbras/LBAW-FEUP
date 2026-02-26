@extends('layouts.app')

@section('title', 'Liberato · Order Confirmation')
@section('body-class', 'order-confirmation-page')

@push('styles')
<style>
    body.order-confirmation-page { background: radial-gradient(circle at top, #111827, #020205); color: #f9fafb; }
    .confirm-shell { max-width:980px; margin:2.5rem auto; background: rgba(17,24,39,0.9); padding:2.25rem; border-radius:26px; }
    .confirm-items { display:flex; flex-direction:column; gap:0.75rem; }
    .confirm-item { display:flex; justify-content:space-between; padding:0.75rem; border-radius:12px; background: linear-gradient(165deg, rgba(31,41,55,0.85), rgba(17,24,39,0.85)); border:1px solid rgba(255,255,255,0.03); }
    .book-card__link { border-radius:999px; padding:0.6rem 1rem; text-decoration:none; font-weight:600; }
</style>
@endpush

@section('content')
<div class="confirm-shell">
    <h1>Encomenda confirmada</h1>

    <p style="color:#d1d5db;">Pedido nº <strong>#{{ $order->order_id }}</strong> — Data: {{ $order->date ?? date('Y-m-d') }}</p>

    <h3 style="margin-top:1rem;">Itens</h3>
    <div class="confirm-items">
        @foreach($order->books as $book)
            @php
                $quantity = $book->pivot->quantity;
                $unitPrice = $book->pivot->unit_price_at_purchase;
                $unitFormatted = number_format($unitPrice, 2, ',', ' ');
            @endphp
            <div class="confirm-item">
                <div>
                    <strong>{{ $book->name }}</strong>
                    <div style="color:#d1d5db; font-size:0.9rem;">
                        x {{ $quantity }} — {{ $unitFormatted }} €
                    </div>
                </div>
                <div style="font-weight:600;">
                    {{ number_format($unitPrice * $quantity, 2, ',', ' ') }} €
                </div>
            </div>
        @endforeach
    </div>

    <h3 style="margin-top:1rem;">Pagamento</h3>
    <div style="color:#d1d5db;">
        Método: <strong>{{ $order->payment->payment_method ?? '—' }}</strong><br>
        Valor pago: <strong>{{ number_format($order->payment->amount ?? $order->total_price, 2, ',', ' ') }} €</strong>
    </div>

    <div style="margin-top:1.5rem; text-align:right;">
        <a class="book-card__link" href="{{ route('catalog.index') }}">Voltar ao início</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const msg = {!! json_encode(session('purchase_popup')) !!};
        if (msg) {
            // Simple modal / popup
            const overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.left = 0; overlay.style.top = 0; overlay.style.right = 0; overlay.style.bottom = 0;
            overlay.style.background = 'rgba(0,0,0,0.6)';
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.style.zIndex = 9999;

            const box = document.createElement('div');
            box.style.background = '#0b1220';
            box.style.color = '#fff';
            box.style.padding = '1.25rem';
            box.style.borderRadius = '12px';
            box.style.maxWidth = '520px';
            box.style.textAlign = 'center';
            box.innerHTML = '<h3 style="margin-top:0;">Compra bem sucedida</h3><p style="color:#d1d5db;">' + msg + '</p>';

            const btn = document.createElement('button');
            btn.textContent = 'Fechar';
            btn.className = 'book-card__link';
            btn.style.marginTop = '1rem';
            btn.onclick = function(){ document.body.removeChild(overlay); };
            box.appendChild(btn);

            overlay.appendChild(box);
            document.body.appendChild(overlay);
        }
    })();
</script>
@endpush

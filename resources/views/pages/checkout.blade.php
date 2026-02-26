@extends('layouts.app')

@section('title', 'Liberato · Checkout')
@section('body-class', 'checkout-page')

@push('styles')
<style>
    body.checkout-page { background: radial-gradient(circle at top, #111827, #020205); color: #f9fafb; }
    .checkout-shell { max-width:980px; margin:2.5rem auto; background: rgba(17,24,39,0.9); padding:2.25rem; border-radius:26px; box-shadow:0 30px 60px rgba(0,0,0,0.45); }
    .checkout-hero { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1rem; }
    .checkout-body { display:flex; gap:1.5rem; align-items:flex-start; }
    .checkout-left { flex:2; }
    .checkout-right { flex:1; max-width:380px; }
    .checkout-items { display:flex; flex-direction:column; gap:0.75rem; }
    /* Each book box: stacked one under the other, white text and richer info */
    .checkout-item { display:flex; gap:1rem; align-items:center; padding:1.25rem; border-radius:12px; background: linear-gradient(165deg, rgba(31,41,55,0.98), rgba(17,24,39,0.98)); border:1px solid rgba(255,255,255,0.06); color:#ffffff; width:100%; }
    .checkout-item .item-img { width:96px; height:140px; flex:0 0 auto; border-radius:6px; background:#111; display:flex; align-items:center; justify-content:center; overflow:hidden; }
    .checkout-item .item-img img { width:100%; height:100%; object-fit:cover; display:block; }
    .checkout-item .item-details { display:flex; flex-direction:column; gap:0.25rem; color:#ffffff; max-width:100%; }
    .checkout-item .item-title { font-weight:900; font-size:1.5rem; }
    .checkout-item .item-author { font-size:1.125rem; color:rgba(255,255,255,0.98); }
    .checkout-item .item-synopsis { font-size:1rem; color:rgba(255,255,255,0.90); }
    .checkout-item .item-meta { margin-left:auto; text-align:right; color:#ffffff; }
    /* Summary card */
    .summary-card { background: linear-gradient(165deg, rgba(31,41,55,0.98), rgba(17,24,39,0.98)); border:1px solid rgba(255,255,255,0.08); border-radius:14px; padding:1rem; }
    .summary-title { font-weight:800; margin:0 0 0.5rem 0; color:#fff; }
    .summary-row { display:flex; justify-content:space-between; align-items:center; margin:0.25rem 0; color:#f9fafb; }
    .summary-total { font-weight:800; font-size:1.1rem; }

    /* Payment area: stack fields vertically and ensure white text */
    .checkout-actions { text-align:left; margin-top:1rem; }
    .checkout-actions label { color: #ffffff; font-weight:600; }
    .checkout-actions input, .checkout-actions select, .checkout-actions textarea { color: #ffffff; background: rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06); }
    .checkout-actions input::placeholder { color: rgba(255,255,255,0.55); }
        .checkout-actions select option { color: #000000; background: #ffffff; }
    .checkout-actions #payment-fields { display:flex; flex-direction:column; gap:0.75rem; align-items:stretch; }
    .book-card__link { border-radius:999px; padding:0.6rem 1rem; text-decoration:none; font-weight:600; }
</style>
@endpush

@section('content')
<div class="checkout-shell">
    <div class="checkout-hero">
        <h1>Finalizar compra</h1>
        <div style="text-align:right; font-weight:600;">Total: {{ number_format($total ?? 0, 2, ',', ' ') }} €</div>
    </div>

    @if(empty($cart) || count($cart) === 0)
        <div class="empty-state">
            <h2>O teu carrinho está vazio</h2>
            <p>Adiciona livros ao carrinho antes de finalizar a compra.</p>
            <a class="book-card__link" href="{{ route('catalog.index') }}">Explorar catálogo</a>
        </div>
    @else
        <div class="checkout-body">
            <div class="checkout-left">
                <div class="checkout-items">
                    @foreach($cart as $id => $qty)
                        @php $book = $books->firstWhere('book_id', $id); @endphp
                        <div class="checkout-item">
                            <div class="item-img">
                                @if(!empty($book) && !empty($book->cover_url))
                                    <img src="{{ $book->cover_url }}" alt="Capa {{ $book->name }}">
                                @else
                                    <img src="https://via.placeholder.com/96x140?text=Livro" alt="Sem capa">
                                @endif
                            </div>
                            <div class="item-details">
                                <div class="item-title">{{ $book->name ?? 'Livro sem título' }}</div>
                                <div class="item-author">{{ $book->author ?? 'Autor desconhecido' }}</div>
                                @if(!empty($book->synopsis))
                                    <div class="item-synopsis">{{ Illuminate\Support\Str::limit($book->synopsis, 220) }}</div>
                                @endif
                            </div>
                            <div class="item-meta">
                                <div>{{ $qty }} ×</div>
                                <div style="font-weight:700; font-size:1.05rem;">{{ number_format($book->price ?? 0, 2, ',', ' ') }} €</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="checkout-actions">
            <!-- Purchase form: includes a hidden payment_method so the server knows what to validate -->
            <form method="POST" action="{{ route('cart.complete') }}" style="display:block;">
                @csrf
                
                <!-- Payment method selector: reloads the checkout page showing the specific inputs -->
                @php $selected = old('payment_method') ?? ($selectedPayment ?? null); @endphp
                <input type="hidden" name="payment_method" value="{{ old('payment_method', $selected) }}">

                <!-- Buyer information section -->
                <fieldset style="border:1px solid rgba(255,255,255,0.08); border-radius:8px; padding:1rem; margin:1.5rem 0; background:rgba(255,255,255,0.02);">
                    <legend style="padding:0 0.5rem; color:#ffffff; font-weight:600;">Informações de entrega</legend>
                    @php
                        $deliveryInfo = session()->get('delivery_info', []);
                        $firstName = old('first_name', $deliveryInfo['first_name'] ?? '');
                        $lastName = old('last_name', $deliveryInfo['last_name'] ?? '');
                        $address = old('address', $deliveryInfo['address'] ?? '');
                        $postalCode = old('postal_code', $deliveryInfo['postal_code'] ?? '');
                    @endphp
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; width:100%;">
                        <div style="display:flex; flex-direction:column; gap:0.25rem;">
                            <label for="first_name" style="font-size:0.95rem;">Nome</label>
                            <input type="text" name="first_name" id="first_name" value="{{ $firstName }}" placeholder="Primeiro nome" required style="padding:0.6rem; border-radius:8px; width:100%;">
                        </div>
                        <div style="display:flex; flex-direction:column; gap:0.25rem;">
                            <label for="last_name" style="font-size:0.95rem;">Sobrenome</label>
                            <input type="text" name="last_name" id="last_name" value="{{ $lastName }}" placeholder="Sobrenome" required style="padding:0.6rem; border-radius:8px; width:100%;">
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:0.25rem; width:100%; margin-top:0.75rem;">
                        <label for="address" style="font-size:0.95rem;">Morada</label>
                        <input type="text" name="address" id="address" value="{{ $address }}" placeholder="Rua, número, andar, etc." required style="padding:0.6rem; border-radius:8px; width:100%;">
                    </div>
                    <div style="display:flex; flex-direction:column; gap:0.25rem; width:100%; margin-top:0.75rem;">
                        <label for="postal_code" style="font-size:0.95rem;">Código postal</label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ $postalCode }}" placeholder="Ex: 1000-001" required style="padding:0.6rem; border-radius:8px; width:100%;">
                    </div>
                </fieldset>

                <!-- Payment method selector -->
                <div style="margin:1.5rem 0;">
                    <label for="pm_select" style="margin-right:0.5rem; font-weight:600; display:block; margin-bottom:0.5rem;">Escolher método:</label>
                    <select name="payment_method_select" id="pm_select" style="padding:0.5rem; border-radius:8px; width:100%;">
                        <option value="">-- Selecionar --</option>
                        <option value="Card" @selected($selected === 'Card')>Cartão</option>
                        <option value="MBWay" @selected($selected === 'MBWay')>MBWay</option>
                        <option value="Paypal" @selected($selected === 'Paypal')>Paypal</option>
                    </select>
                </div>

                <div id="payment-fields">
                    <div style="display:flex; flex-direction:column; gap:0.25rem; width:100%;">
                        <label for="mbway_phone" style="font-size:0.95rem;">MBWay</label>
                        <input type="tel" name="mbway_phone" id="mbway_phone" value="{{ old('mbway_phone') }}" placeholder="Contacto MBWay (ex: 912345678)" style="padding:0.6rem; border-radius:8px; width:100%; {{ ($selected === 'MBWay') ? '' : 'display:none;' }}">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:0.25rem; width:100%;">
                        <label for="card_number" style="font-size:0.95rem;">Cartão</label>
                        <div style="display:flex; gap:0.5rem; align-items:center; width:100%;">
                            <input type="text" name="card_number" id="card_number" value="{{ old('card_number') }}" placeholder="Número do cartão (16 dígitos)" maxlength="19" style="padding:0.6rem; border-radius:8px; flex:1; {{ ($selected === 'Card') ? '' : 'display:none;' }}">
                            <input type="text" name="card_cvc" id="card_cvc" value="{{ old('card_cvc') }}" placeholder="CVC (3 dígitos)" maxlength="4" style="padding:0.6rem; border-radius:8px; width:110px; {{ ($selected === 'Card') ? '' : 'display:none;' }}">
                        </div>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:0.25rem; width:100%;">
                        <label for="paypal_email" style="font-size:0.95rem;">PayPal</label>
                        <input type="email" name="paypal_email" id="paypal_email" value="{{ old('paypal_email') }}" placeholder="Email PayPal" style="padding:0.6rem; border-radius:8px; width:100%; {{ ($selected === 'Paypal') ? '' : 'display:none;' }}">
                    </div>
                </div>

                <div style="display:flex; gap:0.5rem; margin-top:0.75rem;">
                    <button type="submit" class="book-card__link" style="background:linear-gradient(120deg,#10b981,#34d399); color:#fff;">Confirmar compra</button>
                    <a class="book-card__link" href="{{ route('cart.index') }}">Voltar ao carrinho</a>
                </div>
            </form>
        </div>

    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Save delivery info to session via AJAX when values change
        const fieldsToSave = ['first_name', 'last_name', 'address', 'postal_code'];
        
        fieldsToSave.forEach(fieldName => {
            const input = document.getElementById(fieldName);
            if (input) {
                input.addEventListener('input', function() {
                    // Debounce to avoid too many requests
                    clearTimeout(window.deliverySaveTimer);
                    window.deliverySaveTimer = setTimeout(() => {
                        saveDeliveryInfo();
                    }, 500);
                });
            }
        });

        // Payment method change - reload page with new selection
        const pmSelect = document.getElementById('pm_select');
        if (pmSelect) {
            pmSelect.addEventListener('change', function() {
                // Save delivery info first, then reload page
                saveDeliveryInfo(true);
            });
        }

        function saveDeliveryInfo(andReload = false) {
            const data = {};
            fieldsToSave.forEach(fieldName => {
                const input = document.getElementById(fieldName);
                if (input) {
                    data[fieldName] = input.value;
                }
            });

            fetch('{{ route("cart.save-delivery") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            }).then(() => {
                if (andReload) {
                    const selectedMethod = pmSelect.value;
                    window.location.href = '{{ route("cart.checkout") }}' + (selectedMethod ? '?payment_method=' + selectedMethod : '');
                }
            });
        }

        // Card number formatting
        const cardInput = document.getElementById('card_number');
        if (cardInput) {
            cardInput.addEventListener('input', (e) => {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length > 16) v = v.slice(0,16);
                const groups = v.match(/.{1,4}/g) || [];
                e.target.value = groups.join(' ');
            });
        }
    });
</script>
@endpush

@endsection

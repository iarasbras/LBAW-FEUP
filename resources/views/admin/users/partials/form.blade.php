@php
    $currentUser = $user ?? null;
    $editing = filled($currentUser);
@endphp

<style>
    .admin-form { display: flex; flex-direction: column; gap: 2rem; }
    .form-field { display: flex; flex-direction: column; gap: 0.8rem; }
    
    .form-field label { font-weight: 600; color: #d1d5db; font-size: 1.6rem; }
    
    .form-field input[type="text"],
    .form-field input[type="email"],
    .form-field input[type="password"] {
        width: 100%; padding: 1.2rem; border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.05);
        color: white; font-size: 1.5rem; outline: none; transition: border-color 0.2s;
    }
    .form-field input:focus { border-color: #6366f1; }

    .form-checkbox {
        display: flex; align-items: center; gap: 1rem;
        cursor: pointer; padding: 1rem; border-radius: 10px;
        background: rgba(255,255,255,0.03); transition: background 0.2s;
    }
    .form-checkbox:hover { background: rgba(255,255,255,0.08); }
    .form-checkbox input { width: 22px; height: 22px; cursor: pointer; }
    .form-checkbox span { font-size: 1.5rem; color: #f3f4f6; }
    
    .form-error { color: #fca5a5; font-size: 1.2rem; margin-top: 0.4rem; }
</style>

<div class="admin-form">
    <div class="form-field">
        <label for="username">Nome de utilizador</label>
        <input type="text" id="username" name="username" value="{{ old('username', optional($currentUser)->username) }}" required>
        @error('username') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-field">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" value="{{ old('email', optional($currentUser)->email) }}" required>
        @error('email') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    @unless($editing)
        <div class="form-field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-field">
            <label for="password_confirmation">Confirmar password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
    @endunless

    <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-top: 1rem;">
        {{-- Checkbox Bloqueado --}}
        <div class="form-field">
            <input type="hidden" name="is_blocked" value="0">
            <label class="form-checkbox" for="is_blocked">
                <input type="checkbox" id="is_blocked" name="is_blocked" value="1" @checked(old('is_blocked', optional($currentUser)->is_blocked ?? false))>
                <span>Utilizador bloqueado</span>
            </label>
        </div>

        {{-- Checkbox Vendedor (Novo) --}}
        <div class="form-field">
            <input type="hidden" name="is_seller" value="0">
            <label class="form-checkbox" for="is_seller" style="border: 1px solid rgba(99, 102, 241, 0.3);">
                <input type="checkbox" id="is_seller" name="is_seller" value="1" @checked(old('is_seller', optional($currentUser)->is_seller ?? false))>
                <span style="color: #a5b4fc;">Utilizador é vendedor</span>
            </label>
        </div>
    </div>
</div>
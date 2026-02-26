@extends('layouts.app')

@section('title', 'Liberato · Administração · Criar utilizador')
@section('body-class', 'admin-page')

@section('content')
    <div class="admin-shell">
        <div class="admin-header">
            <div>
                <p>Autenticado como {{ auth('admin')->user()->name }}</p>
                <h1>Criar novo utilizador</h1>
            </div>
            <div class="admin-actions">
                <a class="ghost-button" href="{{ route('admin.users.index') }}">← Voltar</a>
                <a class="ghost-button" href="{{ route('logout') }}">Terminar sessão</a>
            </div>
        </div>

        <section class="admin-panel">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                @include('admin.users.partials.form', ['user' => null])

                <div class="form-footer">
                    <a class="ghost-button" href="{{ route('admin.users.index') }}">Cancelar</a>
                    <button type="submit">Criar utilizador</button>
                </div>
            </form>
        </section>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Liberato · Administração · Utilizadores')
@section('body-class', 'admin-page')

@push('styles')
<style>
    body.admin-page {
        background: radial-gradient(circle at top, #111827, #020205);
        color: #f9fafb;
        font-family: "Segoe UI", Helvetica, Arial, sans-serif;
        font-size: 1.5rem;
        min-height: 100vh; 
        margin: 0;
    }
    .admin-shell {
        max-width: 1600px;
        margin: 3rem auto;
        padding: 0 1.5rem;
    }
    
    /* Header */
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: rgba(17, 24, 39, 0.8);
        padding: 2rem;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }
    .admin-header h1 { margin: 0; font-size: 2.5rem; font-weight: 700; }
    .header-actions { display: flex; gap: 1rem; align-items: center; }
    
    /* Botões */
    .btn {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 1rem 2rem; border-radius: 12px; font-weight: 600;
        font-size: 1.5rem;
        text-decoration: none; border: none; cursor: pointer; transition: all 0.2s;
    }
    .btn-primary { background: linear-gradient(120deg, #6366f1, #8b5cf6); color: white; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,0.4); }
    .btn-ghost { background: rgba(255,255,255,0.1); color: white; }
    .btn-ghost:hover { background: rgba(255,255,255,0.15); }

    /* Filtros */
    .dashboard-filters {
        display: flex; gap: 1rem; margin-bottom: 2.5rem; flex-wrap: wrap; justify-content: flex-start;
    }
    .dashboard-filters input, .dashboard-filters select {
        padding: 0 1.5rem; 
        height: 60px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.08);
        color: white;
        min-width: 250px; 
        font-weight: 400;
        font-size: 1.5rem;
        outline: none;
        transition: all 0.2s;
    }
    .dashboard-filters select option { background: #1f2937; color: white; }

    /* Tabela */
    .inventory-table {
        width: 100%; border-collapse: separate; border-spacing: 0;
        background: rgba(17, 24, 39, 0.6); border-radius: 16px; overflow: hidden;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .inventory-table th { 
        background: rgba(255,255,255,0.05); 
        padding: 1.5rem; 
        text-align: left; 
        color: #9ca3af; 
        font-size: 1.5rem;
    }
    .inventory-table td { 
        padding: 1.5rem; 
        border-bottom: 1px solid rgba(255,255,255,0.05); 
        vertical-align: middle; 
        font-size: 1.5rem;
    }

    /* Badges de Função (Seller / Cliente) */
    .badge-role {
        display: inline-block;
        padding: 0.4rem 1.2rem;
        border-radius: 99px;
        font-size: 1.2rem;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .role-seller {
        background: rgba(99, 102, 241, 0.2);
        color: #a5b4fc;
        border-color: rgba(99, 102, 241, 0.3);
    }
    .role-client {
        background: rgba(255, 255, 255, 0.05);
        color: #9ca3af;
        border-color: rgba(255, 255, 255, 0.1);
    }

    /* Status Chips (Ativo / Bloqueado) */
    .status-chip {
        display: inline-block; padding: 0.5rem 1.2rem; border-radius: 99px;
        font-size: 1.3rem; font-weight: 600;
    }
    .status-chip.is-active { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .status-chip.is-blocked { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }

    .actions { display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center; }
    .btn-action {
        display: inline-flex; align-items: center; justify-content: center;
        height: 50px;
        padding: 0 1.5rem; border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        background: transparent; color: white; text-decoration: none;
        font-size: 1.4rem; font-weight: 600; transition: all 0.2s; cursor: pointer;
    }
    .btn-action:hover { background: rgba(255, 255, 255, 0.1); border-color: white; }
    .btn-action.danger { border-color: rgba(239, 68, 68, 0.5); color: #fca5a5; }

    .pagination-wrapper { margin-top: 1.5rem; font-size: 1rem; }
    .pagination-wrapper svg { width: 20px; height: 20px; }
</style>
@endpush

@section('content')
<div class="admin-shell">
    
    <div class="admin-header">
        <div>
            <h1>Gestão de Utilizadores</h1>
            <p style="color: #9ca3af; margin-top: 0.5rem; font-size: 1.5rem;">
                Olá, {{ auth('admin')->user()->username }}. A administrar contas e permissões.
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost">← Painel Principal</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Novo Utilizador</a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-ghost" style="border: 1px solid rgba(255,255,255,0.1);">Terminar Sessão</button>
            </form>
        </div>
    </div>

    <form class="dashboard-filters" method="GET" action="{{ route('admin.users.index') }}">
        <input type="text" name="search" placeholder="Nome, email ou ID..." value="{{ $search }}">
        
        <select name="status" onchange="this.form.submit()">
            <option value="all" @selected($status === 'all')>Todos os estados</option>
            <option value="active" @selected($status === 'active')>Apenas ativos</option>
            <option value="blocked" @selected($status === 'blocked')>Apenas bloqueados</option>
            <option value="deleted" @selected($status === 'deleted')>Apenas eliminados</option>
        </select>

        <select name="role" onchange="this.form.submit()">
            <option value="all" @selected($role === 'all')>Todas as funções</option>
            <option value="seller" @selected($role === 'seller')>Vendedores</option>
            <option value="client" @selected($role === 'client')>Clientes</option>
        </select>
        @if($search || $status !== 'all' || $role !== 'all')
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost" style="height: 60px;">Limpar</a>
        @endif
    </form>

    @if ($users->count())
        <table class="inventory-table">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Utilizador</th>
                    <th>Função</th>
                    <th>Estado</th>
                    <th style="text-align: right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td>#{{ $u->user_id }}</td>
                        <td>
                            <strong style="font-size: 1.7rem;">{{ $u->username }}</strong><br>
                            <small style="color: #9ca3af;">{{ $u->email }}</small>
                        </td>
                        <td>
                            @if($u->sellerAccount)
                                <span class="badge-role role-seller">Vendedor</span>
                            @else
                                <span class="badge-role role-client">Cliente</span>
                            @endif
                        </td>
                        <td>
                            <span id="status-chip-{{ $u->user_id }}" class="status-chip {{ $u->is_active ? ($u->is_blocked ? 'is-blocked' : 'is-active') : 'is-deleted' }}">
                                {{ $u->is_active ? ($u->is_blocked ? 'Bloqueado' : 'Ativo') : 'Eliminado' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.users.purchase-history', $u) }}" class="btn-action">Histórico de Compras</a>
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn-action">Editar</a>
                                @if ($u->is_active)
                                    <form method="POST" action="{{ route('admin.users.toggle-block', $u) }}" class="ajax-user-block" data-user-id="{{ $u->user_id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-action {{ $u->is_blocked ? '' : 'danger' }}">
                                            {{ $u->is_blocked ? 'Desbloquear' : 'Bloquear' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.delete', $u) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar este utilizador? Esta ação é irreversível.');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action danger">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $users->onEachSide(1)->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 4rem; border: 2px dashed rgba(255,255,255,0.1); border-radius: 16px; color: #9ca3af;">
            <p>Não foram encontrados utilizadores.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.ajax-user-block').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button');
            const originalText = btn.innerText;
            const userId = this.getAttribute('data-user-id');
            const statusChip = document.getElementById(`status-chip-${userId}`);
            
            btn.disabled = true;
            btn.innerText = "...";

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: new FormData(this)
                });
                const data = await response.json();
                if (data.success) {
                    btn.innerText = data.is_blocked ? 'Desbloquear' : 'Bloquear';
                    data.is_blocked ? btn.classList.remove('danger') : btn.classList.add('danger');
                    if (statusChip) {
                        label = data.is_active ? (data.is_blocked ? 'Bloqueado' : 'Ativo') : 'Eliminado';
                        statusChip.innerText = label;
                        statusChip.className = `status-chip ${data.is_active ? (data.is_blocked ? 'is-blocked' : 'is-active') : 'is-deleted'}`;
                    }
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao processar.');
                btn.innerText = originalText;
            } finally {
                btn.disabled = false;
            }
        });
    });
});
</script>
@endpush
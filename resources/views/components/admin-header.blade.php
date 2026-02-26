<div class="admin-header">
    <div>
        <h1>Painel de Administração</h1>
        <p style="color: #9ca3af; margin-top: 0.5rem; font-size: 1.5rem;">Gestão global da plataforma e catálogo</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.platform-info.edit') }}" class="btn btn-secondary">Editar Sobre Nós</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Gerir Utilizadores</a>
        <a href="{{ route('admin.categories.index') }}" class="btn {{ request()->routeIs('admin.categories.*') ? 'btn-primary' : 'btn-secondary' }}"> Gerir Categorias</a>
        <a href="{{ route('admin.books.create') }}" class="btn btn-secondary">+ Adicionar Livro</a>
        <form method="POST" action="{{ route('admin.logout') }}" style="margin:0; display:inline;">
            @csrf
            <button type="submit" class="btn btn-secondary" style="border: 1px solid rgba(255,255,255,0.1);">Terminar Sessão</button>
        </form>
    </div>
</div>

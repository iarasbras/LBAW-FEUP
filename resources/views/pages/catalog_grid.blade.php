@if ($books->isEmpty())
    <div class="empty-state">
        <h2>
            @if ($showFavorites ?? false)
                Ainda não tens favoritos
            @else
                Nenhum livro encontrado
            @endif
        </h2>
        <p>
            @if ($showFavorites ?? false)
                Adiciona livros aos favoritos clicando no coração ❤️ nos cards dos livros.
            @else
                Tenta outra pesquisa ou volta a listar todas as categorias.
            @endif
        </p>
    </div>
@else
    <section class="catalog-grid">
        @foreach ($books as $book)
            <article class="book-card">
                @if(Auth::guard('web')->check())
                    <button 
                        class="wishlist-btn {{ $book->is_favorite ?? false ? 'active' : '' }}" 
                        data-book-id="{{ $book->book_id }}"
                        data-favorite="{{ $book->is_favorite ?? false ? 'true' : 'false' }}"
                        aria-label="{{ $book->is_favorite ?? false ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
                    >
                        {{ $book->is_favorite ?? false ? '❤️' : '🤍' }}
                    </button>
                @endif
                <div class="book-card__meta">
                    <span>{{ $book->category_name }}</span>
                </div>

                <h2>{{ $book->name }}</h2>
                <p class="book-card__author">de {{ $book->author }}</p>

                <p class="book-card__price">
                    {{ number_format($book->price, 2, ',', ' ') }} €
                </p>

                <div class="book-card__seller">
                    <small>Vendido por</small>
                    <p>{{ optional($book->seller)->username ?? 'Loja Liberato' }}</p>
                </div>

                <div>
                    @if ($book->average_rating)
                        <span class="badge">
                            ★ {{ $book->average_rating }} ({{ $book->reviews_count }} avaliações)
                        </span>
                    @else
                        <span class="badge">Sem avaliações</span>
                    @endif
                </div>

                <div class="book-card__actions">
                    @php
                        // Pequeno helper para verificar admin na view
                        $isAdmin = auth('admin')->check();
                    @endphp

                    @if ($isAdmin)
                        <span class="badge" style="width: 100%; text-align: center;">Disponível apenas para clientes</span>
                    @else
                        {{-- FORMULÁRIO COM AJAX --}}
                        <form method="POST" action="{{ route('cart.add') }}" class="book-card__action-form ajax-cart-add">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="book-card__action-btn">Adicionar</button>
                        </form>

                        <a class="book-card__action-btn book-card__details-btn" href="{{ route('catalog.show', $book) }}">
                            Ver detalhes
                        </a>
                    @endif
                </div>
            </article>
        @endforeach
    </section>

    <div style="margin-top:1.5rem; display:flex; justify-content:center;">
        {{-- Importante: links() gera links normais, vamos intercetar com JS --}}
        {{ $books->links() }}
    </div>
@endif
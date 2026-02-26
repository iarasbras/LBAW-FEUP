<?php

namespace App\Observers;

use App\Models\Book;
use App\Models\WishlistedOnSaleNotification;
use App\Models\CartPriceNotification;
use App\Models\Wishlist;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\DB;

class BookObserver
{
    /**
     * Handle the Book "updated" event.
     * Dispara notificações quando o preço do livro é alterado.
     */
    public function updated(Book $book)
    {
        // Verificar se o preço foi alterado
        if ($book->isDirty('price') && $book->getOriginal('price') !== null) {
            $oldPrice = (float) $book->getOriginal('price');
            $newPrice = (float) $book->price;

            // Apenas notificar se o preço mudou de facto
            if ($oldPrice !== $newPrice) {
                
                // 1. Notificar utilizadores que têm o livro na wishlist
                $wishlistUsers = Wishlist::where('book_id', $book->book_id)
                    ->distinct()
                    ->pluck('user_id');

                foreach ($wishlistUsers as $userId) {
                    // Apenas criar notificação se o preço baixou (promoção)
                    if ($newPrice < $oldPrice) {
                        $newPriceFmt = number_format($newPrice, 2, ',', ' ');
                        WishlistedOnSaleNotification::create([
                            'user_id' => $userId,
                            'book_id' => $book->book_id,
                            'date' => now(),
                            'message' => "Boa notícia! '{$book->name}' da tua wishlist baixou para {$newPriceFmt} €.",
                        ]);
                    }
                }

                // 2. Notificar utilizadores que têm o livro no carrinho
                $cartUsers = ShoppingCart::where('book_id', $book->book_id)
                    ->distinct()
                    ->pluck('user_id');

                foreach ($cartUsers as $userId) {
                    // Notificar sempre que o preço muda (para cima ou para baixo)
                    $newPriceFmt = number_format($newPrice, 2, ',', ' ');
                    CartPriceNotification::create([
                        'user_id' => $userId,
                        'book_id' => $book->book_id,
                        'date' => now(),
                        'message' => "Atenção! O preço de '{$book->name}' no teu carrinho mudou para {$newPriceFmt} €.",
                    ]);
                }
            }
        }
    }
}

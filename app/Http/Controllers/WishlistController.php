<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle wishlist status for a book (add if not present, remove if present).
     */
    public function toggle(Book $book): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Precisa de estar autenticado para adicionar favoritos.',
            ], 401);
        }

        if (Auth::user()->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'A sua conta encontra-se bloqueada.',
            ], 403);
        }

        $userId = Auth::id();
        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('book_id', $book->book_id)
            ->first();

        if ($wishlistItem) {
            // Remove from wishlist
            $wishlistItem->delete();
            return response()->json([
                'success' => true,
                'isFavorite' => false,
                'message' => 'Removido dos favoritos',
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'book_id' => $book->book_id,
            ]);
            return response()->json([
                'success' => true,
                'isFavorite' => true,
                'message' => 'Adicionado aos favoritos',
            ]);
        }
    }

    /**
     * Check if a book is in the user's wishlist.
     */
    public function check(Book $book): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['isFavorite' => false]);
        }

        if (Auth::user()->is_blocked) {
            return response()->json(['isFavorite' => false]);
        }

        $isFavorite = Wishlist::where('user_id', Auth::id())
            ->where('book_id', $book->book_id)
            ->exists();

        return response()->json(['isFavorite' => $isFavorite]);
    }
}

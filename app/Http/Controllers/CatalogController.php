<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\Category;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    /**
     * Display the catalog landing page with all available books.
     */
    public function index(Request $request) // Removi o type hint : View para permitir retornar string/json
    {
        $query = Book::query()
            ->with('seller');

        // Search functionality with full text search
        $search = $request->input('q');
        if ($search) {
            $modified_search = str_replace(' ', ' & ', trim($search)) . ':*';
            $query->whereRaw("tsvectors @@ to_tsquery('english', ?)", [$modified_search]);
            // Add rank and aggregates explicitly when searching
            // Use double quotes for the SQL string to avoid escaping issues
            $query->selectRaw(
                "book.*, ts_rank_cd(tsvectors, to_tsquery('english', ?)) as rank, " .
                "(select avg(\"review\".\"rating\") from \"review\" where \"book\".\"book_id\" = \"review\".\"book_id\") as \"average_rating\", " .
                "(select count(*) from \"review\" where \"book\".\"book_id\" = \"review\".\"book_id\") as \"reviews_count\"",
                [$modified_search]
            );
        } else {
            // When not searching, use Laravel's built-in aggregate methods
            $query->withAvg('reviews as average_rating', 'rating')
                  ->withCount('reviews');
        }
        
        if ($category = $request->input('category')) {
            $query->where('category_name', $category);
        }
        
        $showFavorites = $request->input('favorites') == '1';
        if ($showFavorites && Auth::check()) {
            $wishlistBookIds = Wishlist::where('user_id', Auth::id())->pluck('book_id')->toArray();
            if (empty($wishlistBookIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('book_id', $wishlistBookIds);
            }
        }
        
        $sortBy = $request->input('sort', 'name');
        // When there's a search, order by rank first (relevance), then by the selected sort
        if ($search) {
            $query->orderBy('rank', 'desc');
        }
        switch ($sortBy) {
            case 'price_asc': $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'name': default: $query->orderBy('name', 'asc'); break;
        }

        $books = $query->paginate(12)->withQueryString();

        // Transformação dos dados (Wishlist e Ratings)
        $wishlistBookIds = Auth::check() ? Wishlist::where('user_id', Auth::id())->pluck('book_id')->toArray() : [];
        $books->getCollection()->transform(function (Book $book) use ($wishlistBookIds) {
             $book->average_rating = $book->average_rating ? round($book->average_rating, 1) : null;
             $book->is_favorite = in_array($book->book_id, $wishlistBookIds);
             return $book;
        });

        // --- ALTERAÇÃO AQUI: Resposta AJAX ---
        if ($request->ajax()) {
            // Se for AJAX, retorna apenas o HTML da grelha, não a página toda
            return view('pages.catalog_grid', [
                'books' => $books,
                'showFavorites' => $showFavorites
            ])->render();
        }

        $categories = Category::orderBy('category_name')->pluck('category_name');

        return view('pages.cards', [
            'books' => $books,
            'categories' => $categories,
            'activeCategory' => $category ?? null,
            'searchTerm' => $search ?? null,
            'sortBy' => $sortBy,
            'showFavorites' => $showFavorites,
        ]);
    }

    /**
     * Display a single book page.
     */
    public function show(Book $book): View
    {
        $book->load('seller')
            ->loadAvg('reviews as average_rating', 'rating')
            ->loadCount('reviews');

        $book->average_rating = $book->average_rating
            ? round($book->average_rating, 1)
            : null;

        // Check if authenticated user has already reviewed this book
        $hasBought = false;
        $userReview = null;
        $isFavorite = false;
        if (Auth::check()) {
            $hasBought = Order::where('user_id', Auth::id())
                ->whereHas('books', function ($query) use ($book) {
                    // FIX: Use 'book.book_id' instead of just 'book_id'
                    $query->where('book.book_id', $book->book_id);
                })
                ->exists();

            $userReview = Review::where('user_id', Auth::id())
                ->where('book_id', $book->book_id)
                ->first();
            
            $isFavorite = Wishlist::where('user_id', Auth::id())
                ->where('book_id', $book->book_id)
                ->exists();
        }

        return view('pages.card', [
            'book' => $book,
            'hasBought' => $hasBought,
            'userReview' => $userReview,
            'isFavorite' => $isFavorite,
        ]);
    }

    /**
     * Store a new review/rating for a book.
     */
    public function storeReview(Request $request, Book $book)
    {
        if (!Auth::check()) {
            return $request->ajax() 
                ? response()->json(['success' => false, 'message' => 'Precisa de login.'], 401)
                : redirect()->route('login');
        }
        if (Auth::user()->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'A sua conta encontra-se bloqueada.',
            ], 403);
        }
        $hasBought = Order::where('user_id', Auth::id())
            ->whereHas('books', function ($query) use ($book) {
                // FIX: Use 'book.book_id' instead of just 'book_id'
                $query->where('book.book_id', $book->book_id);
            })
            ->exists();

        if (!$hasBought) {
            return $request->ajax() 
                ? response()->json(['success' => false, 'message' => 'Precisa de comprar o livro.'], 401)
                : redirect()->back();
        }

        $validated = $request->validate([
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
        ]);

        $userId = Auth::id();

        // Guardar ou Atualizar (upsert logic simplificada)
        Review::updateOrCreate(
            ['user_id' => $userId, 'book_id' => $book->book_id],
            ['rating' => $validated['rating'], 'date' => now()->toDateString()]
        );

        // AJAX: Recalcular média e contagem para atualizar o ecrã
        if ($request->ajax() || $request->wantsJson()) {
            $book->loadAvg('reviews as average_rating', 'rating');
            $book->loadCount('reviews'); // Recarrega contagem

            return response()->json([
                'success' => true,
                'message' => 'Avaliação guardada!',
                'newAverage' => round($book->average_rating, 1),
                'newCount' => $book->reviews_count
            ]);
        }

        return redirect()->back()->with('success', 'Avaliação guardada com sucesso!');
    }

    /**
     * Remove the user's review for a book.
     */
    public function destroyReview(Request $request, Book $book)
    {
        if (!Auth::check()) {
            return $request->ajax() 
                ? response()->json(['success' => false, 'message' => 'Precisa de login.'], 401)
                : redirect()->route('login');
        }
        if (Auth::user()->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'A sua conta encontra-se bloqueada.',
            ], 403);
        }

        $review = Review::where('user_id', Auth::id())
            ->where('book_id', $book->book_id)
            ->first();

        if ($review) {
            $review->delete();
        }

        if ($request->ajax() || $request->wantsJson()) {
            $book->loadAvg('reviews as average_rating', 'rating');
            $book->loadCount('reviews');

            return response()->json([
                'success' => true,
                'message' => 'Avaliação removida.',
                'newAverage' => $book->average_rating ? round($book->average_rating, 1) : null,
                'newCount' => $book->reviews_count
            ]);
        }

        return redirect()->back()->with('success', 'Avaliação removida.');
    }
}

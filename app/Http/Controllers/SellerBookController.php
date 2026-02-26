<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SellerBookController extends Controller
{
    /**
     * SEGURANÇA MANUAL
     * Verifica se o utilizador é vendedor. Se não for, bloqueia.
     */
    private function checkIsSeller()
    {
        // Se não estiver logado, o middleware 'auth' nas rotas já tratou disso.
        // Aqui verificamos apenas se é vendedor.

        if (Auth::user()->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'A sua conta encontra-se bloqueada.',
            ], 403);
        }
        
        $isSeller = DB::table('seller')->where('seller_id', Auth::id())->exists();

        if (!$isSeller) {
            abort(403, 'Acesso restrito a vendedores registados.');
        }
    }

    public function index(Request $request): View
    {
        $this->checkIsSeller(); // <--- VERIFICAÇÃO DE SEGURANÇA

        $search = $request->input('search');
        $category = $request->input('category');
        $sort = $request->input('sort', 'newest');

        $query = Book::where('seller_id', Auth::id());

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('author', 'ilike', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category_name', $category);
        }

        switch ($sort) {
            case 'price_asc': $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'stock_asc': $query->orderBy('available_stock', 'asc'); break;
            case 'name': $query->orderBy('name', 'asc'); break;
            case 'newest': default: $query->orderBy('book_id', 'desc'); break;
        }

        $books = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('category_name')->pluck('category_name');

        return view('pages.seller.dashboard', compact('books', 'categories', 'search', 'category', 'sort'));
    }

    public function create(): View
    {
        $this->checkIsSeller(); // <--- VERIFICAÇÃO DE SEGURANÇA
        
        $categories = Category::orderBy('category_name')->get();
        return view('pages.seller.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->checkIsSeller(); // <--- VERIFICAÇÃO DE SEGURANÇA

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:category,category_name', // Atenção: verifica se passas ID ou Nome
            'stock' => 'required|integer|min:1',
            'cover_image' => 'nullable|image|max:2048',
            'synopsis' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('covers', 'public');
        }
        Book::create([
            'name' => $validated['name'],
            'author' => $validated['author'],
            'price' => $validated['price'],
            'category_name' => $validated['category_id'], // Ajusta se o form enviar ID
            'available_stock' => $validated['stock'],
            //'image' => $imagePath,
            'synopsis' => $validated['synopsis'],
            'seller_id' => Auth::id(),
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Livro criado com sucesso!');
    }

    public function edit(Book $book)
    {
        $this->checkIsSeller(); // <--- VERIFICAÇÃO DE SEGURANÇA

        if ($book->seller_id !== Auth::id()) abort(403);
        $categories = Category::orderBy('category_name')->get();
        return view('pages.seller.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $this->checkIsSeller(); // <--- VERIFICAÇÃO DE SEGURANÇA

        if ($book->seller_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:category,category_name',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'synopsis' => 'nullable|string',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->image) Storage::disk('public')->delete($book->image);
            $book->image = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update([
            'name' => $validated['name'],
            'author' => $validated['author'],
            'price' => $validated['price'],
            'category_name' => $validated['category_id'],
            'available_stock' => $validated['stock'],
            'synopsis' => $validated['synopsis'],
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Livro atualizado.');
    }

    public function destroy(Request $request, Book $book)
    {
        $this->checkIsSeller();

        if ($book->seller_id !== Auth::id()) {
            return $request->wantsJson() 
                ? response()->json(['success' => false], 403) 
                : abort(403);
        }

        if ($book->image) Storage::disk('public')->delete($book->image);
        $book->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('seller.dashboard')->with('success', 'Livro removido.');
    }

    public function ordersHistory()
    {
        $this->checkIsSeller(); 

        $sales = DB::table('order_book')
            ->join('book', 'order_book.book_id', '=', 'book.book_id')
            ->join('orders', 'order_book.order_id', '=', 'orders.order_id')
            ->join('users', 'orders.user_id', '=', 'users.user_id') 
            ->where('book.seller_id', Auth::id()) 
            ->select(
                'orders.order_id',
                'orders.date',
                'users.username as buyer_name',
                'book.name as book_name',
                'order_book.quantity',
                'order_book.unit_price_at_purchase'
            )
            ->orderBy('orders.date', 'desc')
            ->get()
            ->groupBy('order_id'); 

        return view('pages.seller.orders.history', compact('sales'));
    }
}
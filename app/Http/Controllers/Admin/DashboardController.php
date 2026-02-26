<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with inventory management.
     */
    public function index(Request $request): View
    {
        // 1. Iniciar a query carregando a relação 'seller' para evitar N+1
        $query = Book::with('seller');

        // 2. Filtro de Pesquisa (Título ou Autor)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // 3. Filtro de Categoria
        if ($request->filled('category')) {
            $query->where('category_name', $request->input('category'));
        }

        // 4. Filtro de Vendedor
        if ($request->filled('seller_id')) {
            if ($request->input('seller_id') === 'store') {
                // Livros da Loja (seller_id é null)
                $query->whereNull('seller_id');
            } else {
                // Livros de um vendedor específico
                $query->where('seller_id', $request->input('seller_id'));
            }
        }

        // 5. Ordenação
        switch ($request->input('sort')) {
            case 'stock_asc':
                $query->orderBy('available_stock', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default: // 'newest'
                // CORREÇÃO: Usar 'book_id' em vez de 'created_at'
                $query->orderBy('book_id', 'desc');
                break;
        }

        // 6. Paginação
        $books = $query->paginate(10)->withQueryString();

        // 7. Dados auxiliares para os dropdowns
        $categories = Category::orderBy('category_name')->pluck('category_name');
        $sellers = User::has('books')->select('user_id', 'username')->get();

        return view('admin.dashboard', compact('books', 'categories', 'sellers'));
    }
}
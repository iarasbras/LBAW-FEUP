<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookController extends Controller
{
    public function create(): View
    {
        $categories = Category::orderBy('category_name')->get();
        
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_name' => 'required|string', 
            'available_stock' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'synopsis' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
        }

        Book::create([
            'name' => $validated['name'],
            'author' => $validated['author'],
            'price' => $validated['price'],
            'category_name' => $validated['category_name'], 
            'available_stock' => $validated['available_stock'],
            'image' => $imagePath,
            'synopsis' => $validated['synopsis'],
            'seller_id' => null,
        ]);

        return redirect()
            ->route('admin.dashboard') 
            ->with('success', 'Livro adicionado à loja com sucesso!');
    }

    public function edit(Book $book)
    {
        if ($book->seller_id !== null) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Apenas podes editar livros da Loja. Para vendedores, usa a opção remover.');
        }

        $categories = Category::orderBy('category_name')->get();
        
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        if ($book->seller_id !== null) abort(403);
        
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'author'          => 'required|string|max:255',
            'price'           => 'required|numeric|min:0',
            'category_name'   => 'required|string',
            'available_stock' => 'required|integer|min:0',
            'image'           => 'nullable|image|max:2048',
            'synopsis'        => 'nullable|string',
        ]);
    
        if ($request->hasFile('image')) {
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
            $validated['image'] = $request->file('image')->store('books', 'public');
        }
    
        $book->update($validated);
    
        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'As alterações foram gravadas com sucesso.');
    }

    public function destroy(Request $request, Book $book)
    {
        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Livro removido do sistema.');
    }
}
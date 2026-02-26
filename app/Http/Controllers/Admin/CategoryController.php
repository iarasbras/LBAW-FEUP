<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::withCount('books')->orderBy('category_name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:255|unique:category,category_name',
        ]);

        try {
            Category::create($data);
            return redirect()->route('admin.categories.index')->with('success', 'Categoria criada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar categoria: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao criar categoria.']);
        }
    }

    /**
     * Show the form for editing a category.
     */
    public function edit($categoryName)
    {
        $category = Category::findOrFail($categoryName);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $categoryName)
    {
        $category = Category::findOrFail($categoryName);

        $data = $request->validate([
            'category_name' => 'required|string|max:255|unique:category,category_name,' . $categoryName . ',category_name',
        ]);

        try {
            $category->update($data);
            return redirect()->route('admin.categories.index')->with('success', 'Categoria atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar categoria: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao atualizar categoria.']);
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy($categoryName)
    {
        $category = Category::findOrFail($categoryName);

        // Check if category has books
        if ($category->books()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->withErrors(['error' => 'Não é possível eliminar uma categoria com livros associados.']);
        }

        try {
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Categoria eliminada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao eliminar categoria: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erro ao eliminar categoria.']);
        }
    }
}

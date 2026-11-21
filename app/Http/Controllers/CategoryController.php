<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request): View
    {
        $query = Category::withCount('products');

        if ($search = $request->filled('search')) {
            $keyword = '%'.strtolower(trim((string) $request->input('search'))).'%';
            $query->whereRaw('LOWER(name) LIKE ?', [$keyword]);
        }

        $categories = $query->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Proteksi integritas relasi foreign key (restrictOnDelete)
        if ($category->products()->exists()) {
            $productCount = $category->products()->count();

            return redirect()
                ->route('categories.index')
                ->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih digunakan oleh {$productCount} produk.");
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil dihapus.");
    }
}

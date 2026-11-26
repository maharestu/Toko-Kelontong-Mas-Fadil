<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with eager loading, search, and filters.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('category_id')) {
            $query->category($request->input('category_id'));
        }

        if ($request->filled('stock_status')) {
            $query->stockStatus($request->input('stock_status'));
        }

        if ($request->has('is_active') && $request->input('is_active') !== '' && $request->input('is_active') !== null) {
            $query->active($request->input('is_active'));
        }

        $products = $query->orderBy('name', 'asc')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name', 'asc')->get();
        $totalProductsCount = Product::count();
        $lowStockCount = Product::where('stock', '<=', 10)->count();

        return view('products.index', compact('products', 'categories', 'totalProductsCount', 'lowStockCount'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Request $request, Product $product): RedirectResponse
    {
        abort_unless($request->user()?->isAdmin() ?? false, 403);

        if ($product->saleDetails()->exists() || $product->restockDetails()->exists()) {
            return redirect()
                ->route('products.index')
                ->with('error', "Produk '{$product->name}' tidak dapat dihapus karena memiliki riwayat transaksi penjualan atau kulakan. Non-aktifkan status produk sebagai gantinya.");
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', "Produk '{$product->name}' berhasil dihapus.");
    }
}

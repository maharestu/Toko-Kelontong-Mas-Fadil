<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Tampilkan halaman Pembuat Poster Promosi
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        $products = Product::with('category')->where('is_active', true)->orderBy('name')->get();

        return view('promosi.index', compact('categories', 'products'));
    }
}

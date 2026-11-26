<?php

use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RestockController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Modul Manajemen Inventaris - Kategori Produk
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Modul Manajemen Inventaris - Produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/lainnya', function () {
    return view('lainnya');
})->middleware(['auth', 'verified'])->name('lainnya');

Route::middleware(['auth', 'verified'])->group(function () {
    // Modul Kasir / Transaksi Penjualan
    Route::get('/kasir', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('/kasir', [CashierController::class, 'store'])->name('cashier.store');
    Route::get('/sales', [CashierController::class, 'index'])->name('sales.index');

    Route::get('/restocks', [RestockController::class, 'index'])->name('restocks.index');
    Route::post('/restocks', [RestockController::class, 'store'])->name('restocks.store');
<<<<<<< HEAD
    Route::get('/restocks/{id}/detail', [RestockController::class, 'show'])->name('restocks.show');
=======

    // Modul Promosi - Poster Generator
    Route::get('/promosi', [PromotionController::class, 'index'])->name('promosi.index');
>>>>>>> 7608d25723ab1c014d4ed038b51724e019d3831b
});

Route::middleware('auth')->group(function () {
    Route::get('/laporan', function () {
        return view('laporan.index');
    })->name('laporan');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

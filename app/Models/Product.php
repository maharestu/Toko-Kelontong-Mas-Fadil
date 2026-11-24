<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'unit',
        'stock',
        'current_purchase_price',
        'current_selling_price',
        'is_active',
        'image',
    ];

    protected $appends = [
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'current_purchase_price' => 'decimal:2',
            'current_selling_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Dapatkan URL publik dari foto produk.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? Storage::disk('public')->url($this->image) : null,
        );
    }

    /**
     * Scope untuk pencarian berdasarkan nama produk.
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        $term = '%'.strtolower(trim($keyword)).'%';

        return $query->whereRaw('LOWER(name) LIKE ?', [$term]);
    }

    /**
     * Scope untuk filter berdasarkan kategori.
     */
    public function scopeCategory(Builder $query, mixed $categoryId): Builder
    {
        if (blank($categoryId)) {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope untuk filter status stok ('low' <= 10, 'out' = 0, 'available' > 0).
     */
    public function scopeStockStatus(Builder $query, ?string $status): Builder
    {
        return match ($status) {
            'out' => $query->where('stock', '<=', 0),
            'low' => $query->where('stock', '>', 0)->where('stock', '<=', 10),
            'available' => $query->where('stock', '>', 0),
            default => $query,
        };
    }

    /**
     * Scope untuk filter produk aktif atau non-aktif.
     */
    public function scopeActive(Builder $query, mixed $isActive): Builder
    {
        if ($isActive === null || $isActive === '') {
            return $query;
        }

        return $query->where('is_active', (bool) $isActive);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function restockDetails(): HasMany
    {
        return $this->hasMany(RestockDetail::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}

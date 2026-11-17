<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Restock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restock_date',
        'notes',
        'total_cost',
    ];

    protected function casts(): array
    {
        return [
            'restock_date' => 'date',
            'total_cost' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restockDetails(): HasMany
    {
        return $this->hasMany(RestockDetail::class);
    }

    public function stockMovements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }
}

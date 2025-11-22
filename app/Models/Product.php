<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// 👇 TAMBAHAN PENTING 1: Import class Attribute & Storage
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_available',
        'stock'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'stock' => 'integer'
    ];

    /**
     * 👇 TAMBAHAN PENTING 2: MAGIC ACCESSOR
     * Ini yang bikin $product->image otomatis jadi Link Lengkap R2
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Kalau datanya kosong, kembalikan null
                if (!$value) return null;

                // Kalau datanya udah ada 'http' (berarti udah link lengkap), biarin aja
                if (str_contains($value, 'http')) return $value;

                // Kalau cuma nama file, minta Storage R2 buatkan link lengkapnya
                return Storage::disk('r2')->url($value);
            },
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
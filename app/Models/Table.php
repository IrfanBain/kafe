<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Table extends Model
{
    protected $fillable = [
        'number',
        'name',
        'qr_code',
        'uuid',
        'capacity',
        'status',
        'is_available',
        'location'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($table) {
            if (!$table->qr_code) {
                $table->qr_code = Str::uuid();
            }
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getQrCodeUrlAttribute(): string
    {
        return route('table.menu', $this->qr_code);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'table_id',
        'customer_name',
        'customer_phone',
        'order_type',
        'total_amount',
        'status',
        'payment_status',
        'notes',
        'created_by_cashier'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    // Method to update order status and handle table status
    public function updateOrderStatus($newStatus)
    {
        $oldStatus = $this->status;
        $this->update(['status' => $newStatus]);

        // Handle table status update if order is completed or cancelled
        if (in_array($newStatus, ['completed', 'cancelled']) && $this->table_id) {
            $this->updateTableAvailability();
        }

        return $this;
    }

    // Method to check and update table availability
    public function updateTableAvailability()
    {
        if ($this->table_id) {
            // Check if there are other active orders for this table
            $hasActiveOrders = Order::where('table_id', $this->table_id)
                ->where('id', '!=', $this->id) // Exclude current order
                ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
                ->exists();

            // If no other active orders, make table available
            if (!$hasActiveOrders) {
                $table = \App\Models\Table::find($this->table_id);
                if ($table) {
                    $table->update(['status' => 'available']);
                }
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}

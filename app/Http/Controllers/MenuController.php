<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function show($qrCode)
    {
        $table = Table::where('qr_code', $qrCode)->firstOrFail();

        // Check if table has an active order
        // Exclude 'completed' orders so table can accept new orders after completion
        $activeOrder = Order::where('table_id', $table->id)
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->first();

        // If there's an active order, redirect to order status
        if ($activeOrder) {
            return redirect()->route('order.status', $activeOrder->order_number);
        }

        $categories = Category::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_available', true)
                      ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('menu', compact('table', 'categories'));
    }
}

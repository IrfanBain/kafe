<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;

// Endpoint buat Next.js nembak
Route::get('/test-kategori', function () {
    // Ambil kategori yang aktif aja, urutkan dari yang terbaru
    return Category::where('is_active', true)->latest()->get();
});
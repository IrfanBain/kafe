<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Minuman Kopi',
                'description' => 'Berbagai macam kopi pilihan dengan cita rasa yang nikmat',
                'is_active' => true,
            ],
            [
                'name' => 'Minuman Non-Kopi',
                'description' => 'Minuman segar tanpa kopi untuk semua kalangan',
                'is_active' => true,
            ],
            [
                'name' => 'Makanan Ringan',
                'description' => 'Camilan dan makanan ringan untuk menemani minuman',
                'is_active' => true,
            ],
            [
                'name' => 'Makanan Berat',
                'description' => 'Menu makanan utama yang mengenyangkan',
                'is_active' => true,
            ],
            [
                'name' => 'Dessert',
                'description' => 'Makanan penutup manis untuk melengkapi pengalaman kuliner',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kopiCategory = Category::where('name', 'Minuman Kopi')->first();
        $nonKopiCategory = Category::where('name', 'Minuman Non-Kopi')->first();
        $makananRinganCategory = Category::where('name', 'Makanan Ringan')->first();
        $makananBeratCategory = Category::where('name', 'Makanan Berat')->first();
        $dessertCategory = Category::where('name', 'Dessert')->first();

        $products = [
            // Minuman Kopi
            [
                'category_id' => $kopiCategory->id,
                'name' => 'Espresso',
                'description' => 'Kopi hitam pekat dengan rasa yang kuat dan aroma yang memikat',
                'price' => 15000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_id' => $kopiCategory->id,
                'name' => 'Americano',
                'description' => 'Espresso yang dicampur dengan air panas, rasa ringan dan menyegarkan',
                'price' => 18000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_id' => $kopiCategory->id,
                'name' => 'Cappuccino',
                'description' => 'Espresso dengan steamed milk dan foam yang lembut',
                'price' => 25000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_id' => $kopiCategory->id,
                'name' => 'Latte',
                'description' => 'Espresso dengan steamed milk yang creamy dan lembut',
                'price' => 28000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_id' => $kopiCategory->id,
                'name' => 'Caramel Macchiato',
                'description' => 'Latte dengan sirup caramel dan caramel drizzle di atasnya',
                'price' => 32000,
                'stock' => 100,
                'is_available' => true,
            ],

            // Minuman Non-Kopi
            [
                'category_id' => $nonKopiCategory->id,
                'name' => 'Green Tea Latte',
                'description' => 'Teh hijau dengan steamed milk yang creamy',
                'price' => 22000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_id' => $nonKopiCategory->id,
                'name' => 'Chocolate Hot',
                'description' => 'Minuman cokelat panas yang rich dan creamy',
                'price' => 20000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_id' => $nonKopiCategory->id,
                'name' => 'Lemon Tea',
                'description' => 'Teh dengan perasan lemon segar yang menyegarkan',
                'price' => 15000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_id' => $nonKopiCategory->id,
                'name' => 'Fresh Orange Juice',
                'description' => 'Jus jeruk segar tanpa gula tambahan',
                'price' => 18000,
                'stock' => 50,
                'is_available' => true,
            ],

            // Makanan Ringan
            [
                'category_id' => $makananRinganCategory->id,
                'name' => 'Croissant',
                'description' => 'Pastry butter yang renyah dan lembut',
                'price' => 12000,
                'stock' => 30,
                'is_available' => true,
            ],
            [
                'category_id' => $makananRinganCategory->id,
                'name' => 'Muffin Blueberry',
                'description' => 'Muffin lembut dengan blueberry segar',
                'price' => 15000,
                'stock' => 25,
                'is_available' => true,
            ],
            [
                'category_id' => $makananRinganCategory->id,
                'name' => 'Sandwich Club',
                'description' => 'Sandwich dengan daging, sayuran segar, dan saus spesial',
                'price' => 28000,
                'stock' => 20,
                'is_available' => true,
            ],

            // Makanan Berat
            [
                'category_id' => $makananBeratCategory->id,
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam, dan sayuran',
                'price' => 35000,
                'stock' => 20,
                'is_available' => true,
            ],
            [
                'category_id' => $makananBeratCategory->id,
                'name' => 'Pasta Carbonara',
                'description' => 'Pasta dengan saus carbonara creamy dan bacon',
                'price' => 42000,
                'stock' => 15,
                'is_available' => true,
            ],
            [
                'category_id' => $makananBeratCategory->id,
                'name' => 'Chicken Steak',
                'description' => 'Dada ayam grilled dengan saus blackpepper dan kentang',
                'price' => 45000,
                'stock' => 15,
                'is_available' => true,
            ],

            // Dessert
            [
                'category_id' => $dessertCategory->id,
                'name' => 'Tiramisu',
                'description' => 'Dessert Italia dengan layer kopi dan mascarpone',
                'price' => 25000,
                'stock' => 10,
                'is_available' => true,
            ],
            [
                'category_id' => $dessertCategory->id,
                'name' => 'Cheesecake',
                'description' => 'Kue keju lembut dengan topping berry',
                'price' => 22000,
                'stock' => 12,
                'is_available' => true,
            ],
            [
                'category_id' => $dessertCategory->id,
                'name' => 'Ice Cream Vanilla',
                'description' => 'Es krim vanilla premium dengan topping pilihan',
                'price' => 18000,
                'stock' => 20,
                'is_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

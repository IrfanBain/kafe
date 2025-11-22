<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('products')->delete();
        
        \DB::table('products')->insert(array (
            0 => 
            array (
                'id' => 1,
                'category_id' => 1,
                'name' => 'Espresso',
                'description' => 'Kopi hitam pekat dengan rasa yang kuat dan aroma yang memikat',
                'price' => '15000.00',
                'image' => 'products/01K9XTWF6ZZSACX5G12AEEKBY7.png',
                'is_available' => 1,
                'stock' => 100,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-11-13 05:26:47',
            ),
            1 => 
            array (
                'id' => 2,
                'category_id' => 1,
                'name' => 'Americano',
                'description' => 'Espresso yang dicampur dengan air panas, rasa ringan dan menyegarkan',
                'price' => '18000.00',
                'image' => 'products/01K9XV7AQF036558Z3KCCXSB83.png',
                'is_available' => 1,
                'stock' => 100,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-11-13 05:32:43',
            ),
            2 => 
            array (
                'id' => 3,
                'category_id' => 1,
                'name' => 'Cappuccino',
                'description' => 'Espresso dengan steamed milk dan foam yang lembut',
                'price' => '25000.00',
                'image' => 'products/01K9Y36KZ62MDQ6EVRCP4DJXDV.png',
                'is_available' => 1,
                'stock' => 100,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-11-13 07:52:08',
            ),
            3 => 
            array (
                'id' => 4,
                'category_id' => 1,
                'name' => 'Latte',
                'description' => 'Espresso dengan steamed milk yang creamy dan lembut',
                'price' => '28000.00',
                'image' => 'products/01K9Y2KF1Q6GB1VN5WZB5R02G4.png',
                'is_available' => 1,
                'stock' => 100,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-11-13 07:41:41',
            ),
            4 => 
            array (
                'id' => 5,
                'category_id' => 1,
                'name' => 'Caramel Macchiato',
                'description' => 'Latte dengan sirup caramel dan caramel drizzle di atasnya',
                'price' => '32000.00',
                'image' => 'products/01K9YV8K2TSTFE30BBHARJ1CAT.png',
                'is_available' => 1,
                'stock' => 100,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-11-13 14:52:39',
            ),
            5 => 
            array (
                'id' => 6,
                'category_id' => 2,
                'name' => 'Green Tea Latte',
                'description' => 'Teh hijau dengan steamed milk yang creamy',
                'price' => '22000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 100,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            6 => 
            array (
                'id' => 7,
                'category_id' => 2,
                'name' => 'Chocolate Hot',
                'description' => 'Minuman cokelat panas yang rich dan creamy',
                'price' => '20000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 100,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            7 => 
            array (
                'id' => 8,
                'category_id' => 2,
                'name' => 'Lemon Tea',
                'description' => 'Teh dengan perasan lemon segar yang menyegarkan',
                'price' => '15000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 100,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            8 => 
            array (
                'id' => 9,
                'category_id' => 2,
                'name' => 'Fresh Orange Juice',
                'description' => 'Jus jeruk segar tanpa gula tambahan',
                'price' => '18000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 50,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            9 => 
            array (
                'id' => 10,
                'category_id' => 3,
                'name' => 'Croissant',
                'description' => 'Pastry butter yang renyah dan lembut',
                'price' => '12000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 30,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            10 => 
            array (
                'id' => 11,
                'category_id' => 3,
                'name' => 'Muffin Blueberry',
                'description' => 'Muffin lembut dengan blueberry segar',
                'price' => '15000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 25,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            11 => 
            array (
                'id' => 12,
                'category_id' => 3,
                'name' => 'Sandwich Club',
                'description' => 'Sandwich dengan daging, sayuran segar, dan saus spesial',
                'price' => '28000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 20,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            12 => 
            array (
                'id' => 13,
                'category_id' => 4,
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam, dan sayuran',
                'price' => '35000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 20,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            13 => 
            array (
                'id' => 14,
                'category_id' => 4,
                'name' => 'Pasta Carbonara',
                'description' => 'Pasta dengan saus carbonara creamy dan bacon',
                'price' => '42000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 15,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            14 => 
            array (
                'id' => 15,
                'category_id' => 4,
                'name' => 'Chicken Steak',
                'description' => 'Dada ayam grilled dengan saus blackpepper dan kentang',
                'price' => '45000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 15,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            15 => 
            array (
                'id' => 16,
                'category_id' => 5,
                'name' => 'Tiramisu',
                'description' => 'Dessert Italia dengan layer kopi dan mascarpone',
                'price' => '25000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 10,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            16 => 
            array (
                'id' => 17,
                'category_id' => 5,
                'name' => 'Cheesecake',
                'description' => 'Kue keju lembut dengan topping berry',
                'price' => '22000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 12,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            17 => 
            array (
                'id' => 18,
                'category_id' => 5,
                'name' => 'Ice Cream Vanilla',
                'description' => 'Es krim vanilla premium dengan topping pilihan',
                'price' => '18000.00',
                'image' => NULL,
                'is_available' => 1,
                'stock' => 20,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
        ));
        
        
    }
}
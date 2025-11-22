<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Minuman Kopi',
                'description' => 'Berbagai macam kopi pilihan dengan cita rasa yang nikmat',
                'image' => 'categories/01KAH7B3S1W5R19FFNAWJ6X8MP.png',
                'is_active' => 1,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-11-20 18:10:04',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Minuman Non-Kopi',
                'description' => 'Minuman segar tanpa kopi untuk semua kalangan',
                'image' => NULL,
                'is_active' => 1,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Makanan Ringan',
                'description' => 'Camilan dan makanan ringan untuk menemani minuman',
                'image' => NULL,
                'is_active' => 1,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Makanan Berat',
                'description' => 'Menu makanan utama yang mengenyangkan',
                'image' => NULL,
                'is_active' => 1,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Dessert',
                'description' => 'Makanan penutup manis untuk melengkapi pengalaman kuliner',
                'image' => NULL,
                'is_active' => 1,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
        ));
        
        
    }
}
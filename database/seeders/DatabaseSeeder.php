<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Kafe',
            'email' => 'admin@kafe.com',
            'password' => bcrypt('password'),
        ]);

        // Seed categories, products, and tables
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            TableSeeder::class,
            SettingSeeder::class,
        ]);
    }
}

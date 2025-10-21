<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            [
                'number' => '01',
                'capacity' => 2,
                'status' => 'available',
                'location' => 'Dekat jendela',
            ],
            [
                'number' => '02',
                'capacity' => 4,
                'status' => 'available',
                'location' => 'Area tengah',
            ],
            [
                'number' => '03',
                'capacity' => 2,
                'status' => 'available',
                'location' => 'Pojok kiri',
            ],
            [
                'number' => '04',
                'capacity' => 6,
                'status' => 'available',
                'location' => 'Area VIP',
            ],
            [
                'number' => '05',
                'capacity' => 4,
                'status' => 'available',
                'location' => 'Dekat bar',
            ],
            [
                'number' => '06',
                'capacity' => 2,
                'status' => 'available',
                'location' => 'Teras luar',
            ],
            [
                'number' => '07',
                'capacity' => 8,
                'status' => 'available',
                'location' => 'Ruang keluarga',
            ],
            [
                'number' => '08',
                'capacity' => 4,
                'status' => 'available',
                'location' => 'Area tengah',
            ],
            [
                'number' => '09',
                'capacity' => 2,
                'status' => 'available',
                'location' => 'Pojok kanan',
            ],
            [
                'number' => '10',
                'capacity' => 4,
                'status' => 'available',
                'location' => 'Dekat entrance',
            ],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}

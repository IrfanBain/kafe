<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TablesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tables')->delete();
        
        \DB::table('tables')->insert(array (
            0 => 
            array (
                'id' => 1,
                'number' => '01',
                'name' => NULL,
                'qr_code' => '60c69b7e-5bea-484c-bd84-977c4ac06504',
                'uuid' => NULL,
                'capacity' => 2,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Dekat jendela',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            1 => 
            array (
                'id' => 2,
                'number' => '02',
                'name' => NULL,
                'qr_code' => 'd8042de2-b8f3-4694-9924-31b11af0483a',
                'uuid' => NULL,
                'capacity' => 4,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Area tengah',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            2 => 
            array (
                'id' => 3,
                'number' => '03',
                'name' => NULL,
                'qr_code' => 'd558abe8-0e0c-49d5-aedf-e951ccb76ee9',
                'uuid' => NULL,
                'capacity' => 2,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Pojok kiri',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            3 => 
            array (
                'id' => 4,
                'number' => '04',
                'name' => NULL,
                'qr_code' => '6de4c95c-7686-4d60-8ac5-c37e804da98f',
                'uuid' => NULL,
                'capacity' => 6,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Area VIP',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            4 => 
            array (
                'id' => 5,
                'number' => '05',
                'name' => NULL,
                'qr_code' => '036921b4-84ea-43aa-bd61-90f2039fb56d',
                'uuid' => NULL,
                'capacity' => 4,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Dekat bar',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            5 => 
            array (
                'id' => 6,
                'number' => '06',
                'name' => NULL,
                'qr_code' => 'e6620413-acf4-49be-94fa-d8b78b3b5857',
                'uuid' => NULL,
                'capacity' => 2,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Teras luar',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            6 => 
            array (
                'id' => 7,
                'number' => '07',
                'name' => NULL,
                'qr_code' => 'a1d557c6-5a53-4767-9a26-2342d85b1668',
                'uuid' => NULL,
                'capacity' => 8,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Ruang keluarga',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            7 => 
            array (
                'id' => 8,
                'number' => '08',
                'name' => NULL,
                'qr_code' => '82de98d6-4757-48a1-92bd-6ff52256563e',
                'uuid' => NULL,
                'capacity' => 4,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Area tengah',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            8 => 
            array (
                'id' => 9,
                'number' => '09',
                'name' => NULL,
                'qr_code' => 'babc6650-97dc-4cc7-9417-a752092ff6c3',
                'uuid' => NULL,
                'capacity' => 2,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Pojok kanan',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            9 => 
            array (
                'id' => 10,
                'number' => '10',
                'name' => NULL,
                'qr_code' => '8a00758b-73e8-4f9f-b48d-f423f0442a5e',
                'uuid' => NULL,
                'capacity' => 4,
                'status' => 'available',
                'is_available' => 1,
                'location' => 'Dekat entrance',
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
        ));
        
        
    }
}
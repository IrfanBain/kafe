<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin Kafe',
                'email' => 'admin@kafe.com',
                'email_verified_at' => '2025-11-13 14:15:12',
                'password' => '$2y$12$/gagoxiLA7nXWYlyMRGqMeF.5AGP4bk26qcmS7S/fAjK67RNQ6hMK',
                'remember_token' => NULL,
                'created_at' => '2025-10-01 03:54:28',
                'updated_at' => '2025-10-01 03:54:28',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'kafe',
                'email' => 'kafe@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$r3qeaTpTU98w41ySkYMRSepvgvCMyApQtHc4tRxDa8ONAlkbfJoWm',
                'remember_token' => NULL,
                'created_at' => '2025-11-13 07:27:55',
                'updated_at' => '2025-11-13 07:27:55',
            ),
        ));
        
        
    }
}
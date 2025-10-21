<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'store_name',
                'value' => 'Kafe Digital',
                'type' => 'string',
                'description' => 'Nama toko/kafe yang akan ditampilkan di website'
            ],
            [
                'key' => 'store_logo',
                'value' => null,
                'type' => 'image',
                'description' => 'Logo toko yang akan ditampilkan di header website'
            ],
            [
                'key' => 'store_description',
                'value' => 'Selamat datang di Kafe Digital! Kami menyajikan kopi terbaik dan makanan lezat dengan pelayanan yang ramah.',
                'type' => 'text',
                'description' => 'Deskripsi singkat tentang toko/kafe'
            ],
            [
                'key' => 'store_address',
                'value' => 'Jl. Contoh No. 123, Jakarta',
                'type' => 'text',
                'description' => 'Alamat lengkap toko/kafe'
            ],
            [
                'key' => 'store_phone',
                'value' => '021-12345678',
                'type' => 'string',
                'description' => 'Nomor telepon toko/kafe'
            ],
            [
                'key' => 'store_email',
                'value' => 'info@kafedigital.com',
                'type' => 'string',
                'description' => 'Email kontak toko/kafe'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

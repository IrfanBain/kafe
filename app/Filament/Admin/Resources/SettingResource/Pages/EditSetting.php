<?php

namespace App\Filament\Admin\Resources\SettingResource\Pages; // <-- Sesuaikan namespace ini jika berbeda

use App\Filament\Admin\Resources\SettingResource; // <-- Sesuaikan namespace ini jika berbeda
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache; // Pastikan ini ada
use Illuminate\Support\Facades\Log;   // Tambahkan ini jika ingin pakai Log

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class; // <-- Sesuaikan resource ini jika berbeda

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            // Mungkin ada action lain di sini
        ];
    }

    /**
     * Jalankan setelah data berhasil disimpan.
     */
    protected function afterSave(): void
    {
        // Hapus cache pengaturan global agar data baru diambil
        Cache::forget('store_settings_global'); // <-- KUNCI CACHE YANG BENAR

        // Opsional: Catat di log bahwa cache sudah dihapus
        Log::info('Cache pengaturan toko (store_settings_global) telah dihapus setelah update.');
    }

    // Method mutateFormDataBeforeFill dan mutateFormDataBeforeSave Anda yang sudah ada
    // (Tidak perlu diubah, biarkan seperti sebelumnya)
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Special handling untuk image fields
        if ($data['type'] === 'image') {
            // Set image_value dari value yang ada
            $data['image_value'] = $data['value'] ? [$data['value']] : [];
        }

        // Handle boolean values untuk display
        if ($data['type'] === 'boolean' && $data['value'] !== null) {
            $data['value'] = (bool) $data['value'];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle boolean values
        if ($data['type'] === 'boolean' && isset($data['value'])) {
            $data['value'] = $data['value'] ? '1' : '0';
        }

        // Handle image_value untuk image type
        if ($data['type'] === 'image') {
            if (isset($data['image_value']) && !empty($data['image_value'])) {
                // FileUpload sudah memproses file, ambil path yang benar
                $data['value'] = is_array($data['image_value']) ? $data['image_value'][0] : $data['image_value'];
            } elseif (!isset($data['image_value']) || empty($data['image_value'])) {
                // Jika tidak ada upload baru, pertahankan value yang ada atau set null
                if (!isset($data['value']) || empty($data['value'])) {
                    $data['value'] = null;
                }
                // Jika value sudah ada (tidak diubah), biarkan saja
                // Tidak perlu else if di sini
            }

            // Hapus image_value dari data yang akan disimpan
            unset($data['image_value']);
        }

        return $data;
    }
}
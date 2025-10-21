<?php

namespace App\Filament\Admin\Resources\SettingResource\Pages;

use App\Filament\Admin\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
            } else {
                $data['value'] = null;
            }
            
            // Hapus image_value dari data yang akan disimpan
            unset($data['image_value']);
        }

        return $data;
    }
}

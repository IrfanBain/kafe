<?php

namespace App\Filament\Admin\Resources\SettingResource\Pages;

use App\Filament\Admin\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Clear cache when setting is updated
        Cache::forget("setting.{$this->record->key}");
        
        // Clear all store settings cache
        Cache::forget('store_settings');
    }

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
            }
            
            // Hapus image_value dari data yang akan disimpan
            unset($data['image_value']);
        }

        return $data;
    }
}

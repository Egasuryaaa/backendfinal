<?php

namespace App\Filament\Resources\SellerLocationResource\Pages;

use App\Filament\Resources\SellerLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSellerLocation extends EditRecord
{
    protected static string $resource = SellerLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Pastikan latitude dan longitude ada, jika tidak set default Lamongan
        if (empty($data['latitude']) || $data['latitude'] === null) {
            $data['latitude'] = -7.1192;
        }
        
        if (empty($data['longitude']) || $data['longitude'] === null) {
            $data['longitude'] = 112.4186;
        }
        
        // Konversi jam operasional array jika ada
        if (isset($data['jam_operasional']) && is_array($data['jam_operasional'])) {
            $data['jam_operasional'] = array_filter($data['jam_operasional'], function($schedule) {
                return !empty($schedule['hari']) && !empty($schedule['jam_buka']) && !empty($schedule['jam_tutup']);
            });
        }
        
        return $data;
    }
}
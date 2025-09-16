<?php

namespace App\Filament\Resources\FishFarmResource\Pages;

use App\Filament\Resources\FishFarmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFishFarm extends EditRecord
{
    protected static string $resource = FishFarmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

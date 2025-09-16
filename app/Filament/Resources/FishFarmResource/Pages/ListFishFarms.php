<?php

namespace App\Filament\Resources\FishFarmResource\Pages;

use App\Filament\Resources\FishFarmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFishFarms extends ListRecords
{
    protected static string $resource = FishFarmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

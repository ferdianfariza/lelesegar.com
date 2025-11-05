<?php

namespace App\Filament\Resources\RawMaterialUsageResource\Pages;

use App\Filament\Resources\RawMaterialUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRawMaterialUsages extends ListRecords
{
    protected static string $resource = RawMaterialUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

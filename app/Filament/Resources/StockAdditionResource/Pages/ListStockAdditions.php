<?php

namespace App\Filament\Resources\StockAdditionResource\Pages;

use App\Filament\Resources\StockAdditionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockAdditions extends ListRecords
{
    protected static string $resource = StockAdditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

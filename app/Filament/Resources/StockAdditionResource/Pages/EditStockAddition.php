<?php

namespace App\Filament\Resources\StockAdditionResource\Pages;

use App\Filament\Resources\StockAdditionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockAddition extends EditRecord
{
    protected static string $resource = StockAdditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

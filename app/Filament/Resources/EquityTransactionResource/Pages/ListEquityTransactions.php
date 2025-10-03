<?php

namespace App\Filament\Resources\EquityTransactionResource\Pages;

use App\Filament\Resources\EquityTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEquityTransactions extends ListRecords
{
    protected static string $resource = EquityTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\EquityTransactionResource\Pages;

use App\Filament\Resources\EquityTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquityTransaction extends EditRecord
{
    protected static string $resource = EquityTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

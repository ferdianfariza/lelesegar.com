<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceivableResource\Pages;
use App\Models\Receivable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReceivableResource extends Resource
{
    protected static ?string $model = Receivable::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return 'Piutang';
    }

    public static function getModelLabel(): string
    {
        return 'Piutang';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Piutang';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Piutang')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('receivable_date')
                    ->label('Tanggal Piutang')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0),
                Forms\Components\Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Tunai',
                        'bank_transfer' => 'Transfer Bank',
                        'other' => 'Lainnya',
                    ])
                    ->required()
                    ->default('cash'),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'unpaid' => 'Belum Lunas',
                        'paid' => 'Lunas',
                    ])
                    ->required()
                    ->default('unpaid')
                    ->reactive(),
                Forms\Components\DatePicker::make('paid_date')
                    ->label('Tanggal Pelunasan')
                    ->visible(fn (Forms\Get $get) => $get('status') === 'paid'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Piutang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receivable_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'Tunai',
                        'bank_transfer' => 'Transfer',
                        'other' => 'Lainnya',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid' => 'Belum Lunas',
                        'paid' => 'Lunas',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'unpaid' => 'danger',
                        'paid' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('paid_date')
                    ->label('Tanggal Pelunasan')
                    ->date('d M Y')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'unpaid' => 'Belum Lunas',
                        'paid' => 'Lunas',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('receivable_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceivables::route('/'),
            'create' => Pages\CreateReceivable::route('/create'),
            'edit' => Pages\EditReceivable::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockAdditionResource\Pages;
use App\Models\StockAddition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockAdditionResource extends Resource
{
    protected static ?string $model = StockAddition::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return 'Penambahan Bahan Baku';
    }

    public static function getModelLabel(): string
    {
        return 'Penambahan Bahan Baku';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Penambahan Bahan Baku';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah Stok')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->suffix(function (Forms\Get $get) {
                        if ($get('product_id')) {
                            $product = \App\Models\Product::find($get('product_id'));
                            return $product ? $product->unit : '';
                        }
                        return '';
                    }),
                Forms\Components\DatePicker::make('addition_date')
                    ->label('Tanggal Penambahan')
                    ->required()
                    ->default(now()),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->suffix(fn ($record) => ' ' . $record->product->unit)
                    ->sortable(),
                Tables\Columns\TextColumn::make('addition_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('addition_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockAdditions::route('/'),
            'create' => Pages\CreateStockAddition::route('/create'),
            'edit' => Pages\EditStockAddition::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;
    protected static bool $shouldRegisterNavigation = false; // Hide from navigation

    public static function getNavigationLabel(): string
    {
        return 'Persediaan';
    }

    public static function getModelLabel(): string
    {
        return 'Persediaan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Persediaan';
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
                    ->disabled(fn ($record) => $record !== null),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah Stok')
                    ->required()
                    ->numeric()
                    ->suffix(fn ($get) => $get('unit') ?? 'unit')
                    ->default(0),
                Forms\Components\TextInput::make('minimum_stock')
                    ->label('Stok Minimum')
                    ->required()
                    ->numeric()
                    ->suffix(fn ($get) => $get('unit') ?? 'unit')
                    ->default(0)
                    ->helperText('Jumlah minimum stok yang harus ada'),
                Forms\Components\TextInput::make('unit')
                    ->label('Satuan')
                    ->required()
                    ->default('kg'),
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
                Tables\Columns\TextColumn::make('product.code')
                    ->label('Kode Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Stok Saat Ini')
                    ->numeric()
                    ->sortable()
                    ->suffix(fn ($record) => ' ' . $record->unit)
                    ->color(fn ($record) => $record->quantity <= $record->minimum_stock ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('minimum_stock')
                    ->label('Stok Minimum')
                    ->numeric()
                    ->sortable()
                    ->suffix(fn ($record) => ' ' . $record->unit)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Satuan')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_low_stock')
                    ->label('Status')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->quantity > $record->minimum_stock)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('low_stock')
                    ->label('Stok Rendah')
                    ->query(fn (Builder $query): Builder => $query->whereRaw('quantity <= minimum_stock')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('product.name', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}

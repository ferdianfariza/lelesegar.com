<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Setup Awal';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Produk';
    }

    public static function getModelLabel(): string
    {
        return 'Produk';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Produk';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Produk')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('selling_price')
                    ->label('Harga Jual')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                Forms\Components\TextInput::make('cost_price')
                    ->label('Harga Beli/Modal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                Forms\Components\TextInput::make('unit')
                    ->label('Satuan')
                    ->required()
                    ->default('kg'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('selling_price')
                    ->label('Harga Jual')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Harga Beli')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Satuan')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

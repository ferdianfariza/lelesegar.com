<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RawMaterialResource\Pages;
use App\Filament\Resources\RawMaterialResource\RelationManagers;
use App\Models\RawMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RawMaterialResource extends Resource
{
    protected static ?string $model = RawMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return 'Bahan Baku';
    }

    public static function getModelLabel(): string
    {
        return 'Bahan Baku';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Bahan Baku';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Bahan Baku')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Bahan Baku')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('unit')
                    ->label('Satuan')
                    ->required()
                    ->default('pcs')
                    ->helperText('Contoh: pcs, kg, liter, pack'),
                Forms\Components\TextInput::make('price_per_unit')
                    ->label('Harga per Satuan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                Forms\Components\TextInput::make('beginning_stock')
                    ->label('Stok Awal')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->suffix(fn ($get) => $get('unit') ?? 'pcs'),
                Forms\Components\TextInput::make('current_stock')
                    ->label('Stok Saat Ini')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->suffix(fn ($get) => $get('unit') ?? 'pcs'),
                Forms\Components\TextInput::make('minimum_stock')
                    ->label('Stok Minimum')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->suffix(fn ($get) => $get('unit') ?? 'pcs')
                    ->helperText('Peringatan akan muncul jika stok dibawah nilai ini'),
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
                    ->label('Nama Bahan Baku')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_unit')
                    ->label('Harga/Satuan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_stock')
                    ->label('Stok Saat Ini')
                    ->numeric()
                    ->sortable()
                    ->suffix(fn ($record) => ' ' . $record->unit)
                    ->color(fn ($record) => $record->current_stock <= $record->minimum_stock ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('minimum_stock')
                    ->label('Stok Minimum')
                    ->numeric()
                    ->sortable()
                    ->suffix(fn ($record) => ' ' . $record->unit)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('stock_value')
                    ->label('Nilai Stok')
                    ->money('IDR')
                    ->getStateUsing(fn ($record) => $record->current_stock * $record->price_per_unit)
                    ->sortable(),
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
                Tables\Filters\Filter::make('low_stock')
                    ->label('Stok Rendah')
                    ->query(fn (Builder $query): Builder => $query->whereRaw('current_stock <= minimum_stock')),
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
            ->defaultSort('name', 'asc');
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
            'index' => Pages\ListRawMaterials::route('/'),
            'create' => Pages\CreateRawMaterial::route('/create'),
            'edit' => Pages\EditRawMaterial::route('/{record}/edit'),
        ];
    }
}

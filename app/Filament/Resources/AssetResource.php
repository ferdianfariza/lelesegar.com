<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Setup Awal';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return 'Aset';
    }

    public static function getModelLabel(): string
    {
        return 'Aset';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Aset';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Aset')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Aset')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('asset_type')
                    ->label('Jenis Aset')
                    ->options([
                        'bangunan' => 'Bangunan',
                        'peralatan' => 'Peralatan',
                        'kendaraan' => 'Kendaraan',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required()
                    ->default('bangunan'),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('purchase_date')
                    ->label('Tanggal Pembelian')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('purchase_price')
                    ->label('Harga Beli')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                Forms\Components\TextInput::make('current_value')
                    ->label('Nilai Saat Ini')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->helperText('Nilai aset saat ini setelah penyusutan'),
                Forms\Components\TextInput::make('useful_life_years')
                    ->label('Umur Ekonomis (Tahun)')
                    ->required()
                    ->numeric()
                    ->default(5)
                    ->suffix('tahun'),
                Forms\Components\TextInput::make('depreciation_rate')
                    ->label('Tingkat Penyusutan')
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->default(0)
                    ->helperText('Persentase penyusutan per tahun'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('depreciation_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('useful_life_years')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RawMaterialUsageResource\Pages;
use App\Filament\Resources\RawMaterialUsageResource\RelationManagers;
use App\Models\RawMaterialUsage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RawMaterialUsageResource extends Resource
{
    protected static ?string $model = RawMaterialUsage::class;

    protected static ?string $navigationIcon = 'heroicon-o-minus-circle';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return 'Pemakaian Bahan Baku';
    }

    public static function getModelLabel(): string
    {
        return 'Pemakaian Bahan Baku';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Pemakaian Bahan Baku';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('raw_material_id')
                    ->label('Bahan Baku')
                    ->relationship('rawMaterial', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                        if ($state) {
                            $rawMaterial = \App\Models\RawMaterial::find($state);
                            if ($rawMaterial) {
                                $set('price_per_unit', $rawMaterial->price_per_unit);
                            }
                        }
                    }),
                Forms\Components\DatePicker::make('usage_date')
                    ->label('Tanggal Pemakaian')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah Dipakai')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->reactive()
                    ->suffix(function (Forms\Get $get) {
                        if ($get('raw_material_id')) {
                            $rawMaterial = \App\Models\RawMaterial::find($get('raw_material_id'));
                            return $rawMaterial ? $rawMaterial->unit : '';
                        }
                        return '';
                    })
                    ->helperText(function (Forms\Get $get) {
                        if ($get('raw_material_id')) {
                            $rawMaterial = \App\Models\RawMaterial::find($get('raw_material_id'));
                            return $rawMaterial ? 'Stok tersedia: ' . $rawMaterial->current_stock . ' ' . $rawMaterial->unit : '';
                        }
                        return '';
                    }),
                Forms\Components\TextInput::make('price_per_unit')
                    ->label('Harga per Satuan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Placeholder::make('total_cost_display')
                    ->label('Total Biaya')
                    ->content(function (Forms\Get $get) {
                        $quantity = $get('quantity') ?? 0;
                        $price = $get('price_per_unit') ?? 0;
                        $total = $quantity * $price;
                        return 'Rp ' . number_format($total, 0, ',', '.');
                    }),
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
                Tables\Columns\TextColumn::make('usage_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rawMaterial.name')
                    ->label('Bahan Baku')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->suffix(fn ($record) => ' ' . $record->rawMaterial->unit)
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_unit')
                    ->label('Harga/Satuan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cost')
                    ->label('Total Biaya')
                    ->money('IDR')
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
            ->defaultSort('usage_date', 'desc');
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
            'index' => Pages\ListRawMaterialUsages::route('/'),
            'create' => Pages\CreateRawMaterialUsage::route('/create'),
            'edit' => Pages\EditRawMaterialUsage::route('/{record}/edit'),
        ];
    }
}

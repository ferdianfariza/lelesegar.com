<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquityTransactionResource\Pages;
use App\Filament\Resources\EquityTransactionResource\RelationManagers;
use App\Models\EquityTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EquityTransactionResource extends Resource
{
    protected static ?string $model = EquityTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Setup Awal';
    protected static ?int $navigationSort = 3;
    protected static bool $shouldRegisterNavigation = false; // Hide from navigation

    public static function getNavigationLabel(): string
    {
        return 'Modal & Prive';
    }

    public static function getModelLabel(): string
    {
        return 'Transaksi Modal';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Transaksi Modal';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('transaction_code')
                    ->label('Kode Transaksi')
                    ->default(fn () => 'EQ-' . date('Ymd') . '-' . str_pad(EquityTransaction::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\DatePicker::make('transaction_date')
                    ->label('Tanggal')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('equity_type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'initial_capital' => 'Modal Awal',
                        'additional_capital' => 'Tambah Modal',
                        'owner_withdrawal' => 'Prive (Pengambilan Pemilik)',
                    ])
                    ->required()
                    ->default('initial_capital'),
                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('equity_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
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
            'index' => Pages\ListEquityTransactions::route('/'),
            'create' => Pages\CreateEquityTransaction::route('/create'),
            'edit' => Pages\EditEquityTransaction::route('/{record}/edit'),
        ];
    }
}

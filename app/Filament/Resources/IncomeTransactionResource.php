<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomeTransactionResource\Pages;
use App\Filament\Resources\IncomeTransactionResource\RelationManagers;
use App\Models\IncomeTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IncomeTransactionResource extends Resource
{
    protected static ?string $model = IncomeTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Pemasukan';
    }

    public static function getModelLabel(): string
    {
        return 'Transaksi Pemasukan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Transaksi Pemasukan';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('transaction_code')
                    ->label('Kode Transaksi')
                    ->default(fn () => 'INC-' . date('Ymd') . '-' . str_pad(IncomeTransaction::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\DatePicker::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('income_type')
                    ->label('Jenis Pemasukan')
                    ->options([
                        'sales' => 'Penjualan',
                        'initial_capital' => 'Modal Awal',
                        'capital' => 'Tambah Modal',
                        'other' => 'Lainnya',
                    ])
                    ->required()
                    ->default('sales')
                    ->reactive(),
                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required(fn (Forms\Get $get) => $get('income_type') === 'sales')
                    ->visible(fn (Forms\Get $get) => $get('income_type') === 'sales')
                    ->reactive(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Kuantitas')
                    ->numeric()
                    ->required(fn (Forms\Get $get) => $get('income_type') === 'sales')
                    ->visible(fn (Forms\Get $get) => $get('income_type') === 'sales')
                    ->suffix(function (Forms\Get $get) {
                        if ($get('product_id')) {
                            $product = \App\Models\Product::find($get('product_id'));
                            return $product ? $product->unit : '';
                        }
                        return '';
                    })
                    ->reactive()
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                        if ($get('product_id') && $state) {
                            $product = \App\Models\Product::find($get('product_id'));
                            if ($product) {
                                $set('amount', $product->selling_price * $state);
                            }
                        }
                    }),
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
                Forms\Components\Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Tunai',
                        'bank_transfer' => 'Transfer Bank',
                        'other' => 'Lainnya',
                    ])
                    ->required()
                    ->default('cash'),
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
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('income_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sales' => 'Penjualan',
                        'initial_capital' => 'Modal Awal',
                        'capital' => 'Tambah Modal',
                        'other' => 'Lainnya',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'sales' => 'success',
                        'initial_capital' => 'primary',
                        'capital' => 'info',
                        'other' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Bayar')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'Tunai',
                        'bank_transfer' => 'Transfer',
                        'other' => 'Lainnya',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('order.customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('income_type')
                    ->label('Jenis')
                    ->options([
                        'sales' => 'Penjualan',
                        'initial_capital' => 'Modal Awal',
                        'capital' => 'Tambah Modal',
                        'other' => 'Lainnya',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Tunai',
                        'bank_transfer' => 'Transfer Bank',
                        'other' => 'Lainnya',
                    ]),
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
            ->defaultSort('transaction_date', 'desc');
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
            'index' => Pages\ListIncomeTransactions::route('/'),
            'create' => Pages\CreateIncomeTransaction::route('/create'),
            'edit' => Pages\EditIncomeTransaction::route('/{record}/edit'),
        ];
    }
}

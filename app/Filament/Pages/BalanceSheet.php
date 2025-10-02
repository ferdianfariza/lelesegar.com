<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Asset;
use App\Models\Inventory;
use App\Models\EquityTransaction;
use App\Models\IncomeTransaction;
use App\Models\ExpenseTransaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

class BalanceSheet extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.balance-sheet';

    public ?array $data = [];
    public $as_of_date;

    public static function getNavigationLabel(): string
    {
        return 'Laporan Posisi Keuangan';
    }

    public function getTitle(): string
    {
        return 'Laporan Posisi Keuangan (Neraca)';
    }

    public function mount(): void
    {
        $this->as_of_date = now()->format('Y-m-d');
        $this->form->fill([
            'as_of_date' => $this->as_of_date,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('as_of_date')
                    ->label('Per Tanggal')
                    ->required()
                    ->reactive()
                    ->default(now()),
            ])
            ->columns(1)
            ->statePath('data');
    }

    public function getData()
    {
        $asOfDate = $this->data['as_of_date'] ?? $this->as_of_date;

        // Calculate cash balance (income - expenses)
        $totalIncome = IncomeTransaction::where('transaction_date', '<=', $asOfDate)->sum('amount');
        $totalExpenses = ExpenseTransaction::where('transaction_date', '<=', $asOfDate)->sum('amount');
        $cash = $totalIncome - $totalExpenses;

        // Get inventory value
        $inventoryValue = Inventory::with('product')
            ->get()
            ->sum(function ($inventory) {
                return $inventory->quantity * $inventory->product->cost_price;
            });

        // Get assets value
        $assetsValue = Asset::where('purchase_date', '<=', $asOfDate)->sum('current_value');

        $totalAssets = $cash + $inventoryValue + $assetsValue;

        // Calculate equity
        $equity = EquityTransaction::whereIn('equity_type', ['initial_capital', 'additional_capital'])
            ->where('transaction_date', '<=', $asOfDate)
            ->sum('amount');
        
        $ownerWithdrawals = EquityTransaction::where('equity_type', 'owner_withdrawal')
            ->where('transaction_date', '<=', $asOfDate)
            ->sum('amount');

        $retainedEarnings = $totalIncome - $totalExpenses;
        $totalEquity = $equity - $ownerWithdrawals;

        // For now, liabilities = 0 (you can expand this)
        $totalLiabilities = 0;

        return [
            'cash' => $cash,
            'inventory_value' => $inventoryValue,
            'assets_value' => $assetsValue,
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'equity' => $equity,
            'owner_withdrawals' => $ownerWithdrawals,
            'retained_earnings' => $retainedEarnings,
            'total_equity' => $totalEquity,
            'as_of_date' => $asOfDate,
        ];
    }
}

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

        // Get initial capital from income transactions
        $initialCapital = IncomeTransaction::where('income_type', 'initial_capital')
            ->where('transaction_date', '<=', $asOfDate)
            ->sum('amount');

        // Get total income and expenses
        $totalIncome = IncomeTransaction::where('transaction_date', '<=', $asOfDate)->sum('amount');
        $totalExpenses = ExpenseTransaction::where('transaction_date', '<=', $asOfDate)->sum('amount');
        
        // Get raw material purchases (total value of raw materials purchased)
        $rawMaterialPurchases = \App\Models\RawMaterial::where('created_at', '<=', $asOfDate)
            ->sum(\DB::raw('beginning_stock * price_per_unit'));

        // Cash = total income - total expenses - raw material purchases
        $cash = $totalIncome - $totalExpenses - $rawMaterialPurchases;

        // Get raw material inventory value (ending stock value)
        $rawMaterialInventoryValue = \App\Models\RawMaterial::where('created_at', '<=', $asOfDate)
            ->get()
            ->sum(function ($material) {
                return $material->current_stock * $material->price_per_unit;
            });

        // Get fixed assets value from expense transactions (building, vehicle, equipment categories)
        $fixedAssetsFromExpenses = ExpenseTransaction::whereHas('expenseCategory', function($query) {
                $query->whereIn('code', ['PERALATAN', 'BANGUNAN', 'KENDARAAN']);
            })
            ->where('transaction_date', '<=', $asOfDate)
            ->sum('amount');

        // Get assets value from Asset table (for depreciation tracking)
        $assetsValue = Asset::where('purchase_date', '<=', $asOfDate)->sum('current_value');

        // Total assets = Cash + Raw Material Inventory + Fixed Assets
        $totalAssets = $cash + $rawMaterialInventoryValue + $fixedAssetsFromExpenses;

        // Calculate equity from Equity Statement ending equity
        // Get sales revenue and expenses for the period
        $salesRevenue = IncomeTransaction::where('income_type', 'sales')
            ->where('transaction_date', '<=', $asOfDate)
            ->sum('amount');
        
        $allExpenses = ExpenseTransaction::where('transaction_date', '<=', $asOfDate)->sum('amount');
        $rawMaterialUsage = \App\Models\RawMaterialUsage::where('usage_date', '<=', $asOfDate)->sum('total_cost');
        $totalExpensesForEquity = $allExpenses + $rawMaterialUsage;
        
        $netIncome = $salesRevenue - $totalExpensesForEquity;
        $equity = $initialCapital + $netIncome;

        // Calculate liabilities (debts)
        $totalLiabilities = \App\Models\Debt::where('status', 'unpaid')
            ->where('debt_date', '<=', $asOfDate)
            ->sum('amount');

        $totalEquity = $equity;

        return [
            'cash' => $cash,
            'raw_material_inventory_value' => $rawMaterialInventoryValue,
            'fixed_assets_value' => $fixedAssetsFromExpenses,
            'assets_value' => $assetsValue,
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'initial_capital' => $initialCapital,
            'net_income' => $netIncome,
            'equity' => $equity,
            'total_equity' => $totalEquity,
            'as_of_date' => $asOfDate,
        ];
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\IncomeTransaction;
use App\Models\ExpenseTransaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

class IncomeStatement extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.income-statement';

    public ?array $data = [];
    public $start_date;
    public $end_date;

    public static function getNavigationLabel(): string
    {
        return 'Laporan Laba Rugi';
    }

    public function getTitle(): string
    {
        return 'Laporan Laba Rugi';
    }

    public function mount(): void
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
        $this->form->fill([
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->reactive(),
                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->required()
                    ->reactive(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function getData()
    {
        $startDate = $this->data['start_date'] ?? $this->start_date;
        $endDate = $this->data['end_date'] ?? $this->end_date;

        // Calculate total revenue from sales only
        $salesRevenue = IncomeTransaction::where('income_type', 'sales')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalRevenue = $salesRevenue;

        // Calculate production expenses from expense transactions (beban produksi category)
        $productionExpenses = ExpenseTransaction::whereHas('expenseCategory', function($query) {
                $query->where('code', 'BEBAN_PRODUKSI');
            })
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        // Calculate production expenses from raw material usage
        $rawMaterialExpenses = \App\Models\RawMaterialUsage::whereBetween('usage_date', [$startDate, $endDate])
            ->sum('total_cost');

        // Total production expenses
        $totalProductionExpenses = $productionExpenses + $rawMaterialExpenses;

        // Get raw material usage details for display
        $rawMaterialUsageDetails = \App\Models\RawMaterialUsage::with('rawMaterial')
            ->whereBetween('usage_date', [$startDate, $endDate])
            ->get()
            ->groupBy('raw_material_id')
            ->map(function ($group) {
                return [
                    'name' => $group->first()->rawMaterial->name,
                    'quantity' => $group->sum('quantity'),
                    'unit' => $group->first()->rawMaterial->unit,
                    'amount' => $group->sum('total_cost'),
                ];
            });

        // Calculate expenses by category (excluding beban produksi as it's shown separately)
        $expenses = ExpenseTransaction::with('expenseCategory')
            ->whereHas('expenseCategory', function($query) {
                $query->where('code', '!=', 'BEBAN_PRODUKSI');
            })
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get()
            ->groupBy('expense_category_id')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->expenseCategory->name,
                    'amount' => $group->sum('amount'),
                ];
            });

        $totalExpenses = $expenses->sum('amount') + $totalProductionExpenses;
        $netIncome = $totalRevenue - $totalExpenses;

        return [
            'sales_revenue' => $salesRevenue,
            'total_revenue' => $totalRevenue,
            'production_expenses' => $productionExpenses,
            'raw_material_expenses' => $rawMaterialExpenses,
            'total_production_expenses' => $totalProductionExpenses,
            'raw_material_usage_details' => $rawMaterialUsageDetails,
            'expenses' => $expenses,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}

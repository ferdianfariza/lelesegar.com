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

        // Calculate total revenue from sales
        $salesRevenue = IncomeTransaction::where('income_type', 'sales')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        // Calculate other revenue
        $otherRevenue = IncomeTransaction::whereIn('income_type', ['other'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalRevenue = $salesRevenue + $otherRevenue;

        // Calculate expenses by category
        $expenses = ExpenseTransaction::with('expenseCategory')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get()
            ->groupBy('expense_category_id')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->expenseCategory->name,
                    'amount' => $group->sum('amount'),
                ];
            });

        $totalExpenses = $expenses->sum('amount');
        $netIncome = $totalRevenue - $totalExpenses;

        return [
            'sales_revenue' => $salesRevenue,
            'other_revenue' => $otherRevenue,
            'total_revenue' => $totalRevenue,
            'expenses' => $expenses,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}

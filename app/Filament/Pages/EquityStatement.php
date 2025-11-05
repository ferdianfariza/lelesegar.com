<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\EquityTransaction;
use App\Models\IncomeTransaction;
use App\Models\ExpenseTransaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

class EquityStatement extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.equity-statement';

    public ?array $data = [];
    public $start_date;
    public $end_date;

    public static function getNavigationLabel(): string
    {
        return 'Laporan Perubahan Ekuitas';
    }

    public function getTitle(): string
    {
        return 'Laporan Perubahan Ekuitas';
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

        // Get initial capital from income transactions with type 'initial_capital' (before period)
        $initialCapital = IncomeTransaction::where('income_type', 'initial_capital')
            ->where('transaction_date', '<', $startDate)
            ->sum('amount');

        // Get additional capital from income transactions with type 'initial_capital' (during period)
        $additionalCapital = IncomeTransaction::where('income_type', 'initial_capital')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        // Calculate net income for the period from Income Statement
        $salesRevenue = IncomeTransaction::where('income_type', 'sales')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        // Calculate all expenses including production expenses and raw material usage
        $expenses = ExpenseTransaction::whereBetween('transaction_date', [$startDate, $endDate])->sum('amount');
        $rawMaterialExpenses = \App\Models\RawMaterialUsage::whereBetween('usage_date', [$startDate, $endDate])->sum('total_cost');
        $totalExpenses = $expenses + $rawMaterialExpenses;
        
        $netIncome = $salesRevenue - $totalExpenses;

        $endingEquity = $initialCapital + $additionalCapital + $netIncome;

        return [
            'initial_capital' => $initialCapital,
            'additional_capital' => $additionalCapital,
            'net_income' => $netIncome,
            'ending_equity' => $endingEquity,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}

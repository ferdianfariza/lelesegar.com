<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\IncomeTransaction;
use App\Models\ExpenseTransaction;
use App\Models\Debt;

class DashboardOverview extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-overview';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = -1;

    public function getData(): array
    {
        // Calculate cash balance
        $totalIncome = IncomeTransaction::sum('amount');
        $totalExpenses = ExpenseTransaction::sum('amount');
        $cashBalance = $totalIncome - $totalExpenses;

        // Calculate unpaid debts
        $unpaidDebts = Debt::where('status', 'unpaid')->sum('amount');

        return [
            'cashBalance' => $cashBalance,
            'unpaidDebts' => $unpaidDebts,
        ];
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\Finance\BookingStatusChart;
use App\Filament\Widgets\Finance\FinanceStats;
use App\Filament\Widgets\Finance\MonthlyRevenueChart;

class Dashboard extends BaseDashboard
{
    public function getHeaderWidgets(): array
    {
        return [
            FinanceStats::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int
    {
        return 4;
    }

    public function getWidgets(): array
    {
        return [
            MonthlyRevenueChart::class,
            BookingStatusChart::class,
        ];
    }
}

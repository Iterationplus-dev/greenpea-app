<?php

namespace App\Filament\Pages;

use App\Filament\Components\CompactModeAction;
use Filament\Facades\Filament;
use App\Filament\Widgets\Finance\FinanceStats;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\Finance\BookingStatusChart;
use App\Filament\Widgets\Finance\MonthlyRevenueChart;
use App\Filament\Widgets\RecentBookingsTable;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            CompactModeAction::make(),
        ];
    }
    public function getHeaderWidgets(): array
    {
        return [
            FinanceStats::class,
            BookingStatusChart::class,
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
            RecentBookingsTable::class,
        ];
    }

    public function getTitle(): string|HtmlString
    {
        $admin = Filament::auth()->user();

        // $role = ucfirst($admin->role ?? $admin->type ?? 'User');
        $role = ucfirst($admin->type->value ?? 'User');

         return new HtmlString(
        "Dashboard: <span class='text-sm text-primary-500'>{$role} ({$admin->name})</span>"
    );
    }
}

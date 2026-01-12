<?php

namespace App\Filament\Widgets\Finance;

use App\Models\Booking;
use App\Models\BookingPayment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceStats extends StatsOverviewWidget
{
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        // Totals
        $totalRevenue = BookingPayment::where('status', 'success')->sum('amount');
        $platformFees = BookingPayment::where('status', 'success')->sum('platform_fee');
        $ownerEarnings = BookingPayment::where('status', 'success')
            ->sum(DB::raw('amount - platform_fee'));
        $totalBookings = Booking::where('status', '!=', 'cancelled')->count();

        // Last 7 days trend data
        $revenueTrend = BookingPayment::where('status', 'success')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->pluck('total')
            ->toArray();

        $bookingsTrend = Booking::whereDate('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total')
            ->toArray();

        return [
            Stat::make('Total Revenue', '₦' . number_format($totalRevenue, 2))
                ->description('All successful payments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->icon('heroicon-o-users')
                // ->url(route('filament.app.resources.booking-payments.index'))
                ->chart($revenueTrend),

            Stat::make('Platform Fees', '₦' . number_format($platformFees, 2))
                ->description('Platform earnings')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info')
                ->icon('heroicon-o-users')
                // ->url(route('filament.app.resources.invoices.index'))
                ->chart($revenueTrend),

            Stat::make('Total Bookings', number_format($totalBookings))
                ->description('All confirmed bookings')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->icon('heroicon-o-users')
                // ->url(route('filament.app.resources.bookings.index'))
                ->chart($bookingsTrend),

            Stat::make('Owner Earnings', '₦' . number_format($ownerEarnings, 2))
                ->description('Money owed to property owners')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success')
                ->icon('heroicon-o-users')
                // ->url(route('filament.app.pages.owner-payouts'))
                ->chart($revenueTrend),
        ];
    }
}

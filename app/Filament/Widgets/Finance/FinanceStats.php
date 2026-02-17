<?php

namespace App\Filament\Widgets\Finance;

use App\Enums\PaymentStatus;
use App\Models\Apartment;
use App\Models\Booking;
use App\Models\BookingPayment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

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
        $availableApartments = Apartment::where('is_available', 1)->count();
        $platformFees = BookingPayment::where('status', 'success')->sum('platform_fee');
        $totalBookingAmounts = Booking::where('status', '!=', 'cancelled')->sum('amount');
        $totalPaid = BookingPayment::query()
            ->whereIn('status', [PaymentStatus::SUCCESS->value, PaymentStatus::PAID->value])
            ->sum('amount');
        $totalDebt = max($totalBookingAmounts - $totalPaid, 0);
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
            Stat::make('Total Revenue', '₦'.number_format($totalRevenue, 2))
                ->description('All successful payments')
                ->descriptionIcon('heroicon-m-banknotes', IconPosition::Before)
                ->color('success')
                // ->icon('heroicon-o-users')
                // ->url(route('filament.app.resources.booking-payments.index'))
                ->chart($revenueTrend),

            Stat::make("Debt's", '₦'.number_format($totalDebt, 2))
                ->description('Money owed '.config('app.name'))
                ->descriptionIcon('heroicon-m-arrow-path-rounded-square', IconPosition::Before)
                ->color('success')
               // ->icon('heroicon-o-users')
               // ->url(route('filament.app.pages.owner-payouts'))
                ->chart($revenueTrend),

            Stat::make('Apartments', value: number_format($availableApartments, 0))
                ->description('Total apartments listed available')
                ->descriptionIcon('heroicon-m-home-modern', IconPosition::Before)
                ->color('info')
                // ->icon('heroicon-o-users')
                // ->url(route('filament.app.resources.invoices.index'))
                ->chart($revenueTrend),

            // return for multi-users
            // Stat::make('Platform Fees', '₦' . number_format($platformFees, 2))
            //     ->description('Platform earnings')
            //     ->descriptionIcon('heroicon-m-currency-dollar')
            //     ->color('info')
            //     // ->icon('heroicon-o-users')
            //     // ->url(route('filament.app.resources.invoices.index'))
            //     ->chart($revenueTrend),

            Stat::make('Total Bookings', number_format($totalBookings))
                ->description('All confirmed bookings')
                ->descriptionIcon('heroicon-m-calendar-days', IconPosition::Before)
                ->color('primary')
                // ->icon('heroicon-o-users')
                // ->url(route('filament.app.resources.bookings.index'))
                ->chart($bookingsTrend),

        ];
    }
}

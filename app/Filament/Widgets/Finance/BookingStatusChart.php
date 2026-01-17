<?php

namespace App\Filament\Widgets\Finance;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatusChart extends StatsOverviewWidget
{
    // protected ?string $heading = 'Finance Overview';
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        return [
            Stat::make('Pending Bookings', Booking::where('status', BookingStatus::PENDING->value)->count())
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->extraAttributes([
                    'class' => '
            bg-gradient-to-r from-yellow-200 to-amber-300
            text-white shadow-lg hover:scale-[1.02]
            transition-all duration-200 relative

            [&_.fi-icon]:absolute
            [&_.fi-icon]:right-3
            [&_.fi-icon]:top-3
            [&_.fi-icon]:opacity-40
            [&_.fi-icon]:w-14
            [&_.fi-icon]:h-14',
                ]),

            Stat::make(
                'Approved Bookings',
                Booking::where('status', BookingStatus::APPROVED->value)->count()
            )
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->extraAttributes([
                    'class' => '
                    bg-gradient-to-r from-green-300 to-emerald-400
                    text-white shadow-lg hover:scale-[1.02]
                    transition-all duration-200 relative

                    [&_.fi-wi-stats-overview-stat-icon]:absolute
                    [&_.fi-wi-stats-overview-stat-icon]:right-4
                    [&_.fi-wi-stats-overview-stat-icon]:top-4
                    [&_.fi-wi-stats-overview-stat-icon]:opacity-40
                    [&_.fi-wi-stats-overview-stat-icon]:w-14
                    [&_.fi-wi-stats-overview-stat-icon]:h-14
                ',
                ]),

            Stat::make(
                'Refunded Bookings',
                Booking::where('status', BookingStatus::REFUNDED->value)->count()
            )
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('danger')
                ->extraAttributes([
                    'class' => '
                    bg-gradient-to-r from-red-300 to-rose-400
                    text-white shadow-lg hover:scale-[1.02]
                    transition-all duration-200 relative

                    [&_.fi-wi-stats-overview-stat-icon]:absolute
                    [&_.fi-wi-stats-overview-stat-icon]:right-4
                    [&_.fi-wi-stats-overview-stat-icon]:top-4
                    [&_.fi-wi-stats-overview-stat-icon]:opacity-40
                    [&_.fi-wi-stats-overview-stat-icon]:w-14
                    [&_.fi-wi-stats-overview-stat-icon]:h-14
                ',
                ]),
        ];
    }
}

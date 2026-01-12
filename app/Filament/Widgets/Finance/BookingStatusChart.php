<?php

namespace App\Filament\Widgets\Finance;

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
            Stat::make('Pending Bookings', Booking::where('status', 'pending')->count())
                ->color('warning'),

            Stat::make('Approved Bookings', Booking::where('status', 'approved')->count())
                ->color('success'),

            Stat::make('Refunded Bookings', Booking::where('status', 'refunded')->count())
                ->color('danger'),
        ];
    }
}

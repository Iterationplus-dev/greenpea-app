<?php

namespace App\Filament\Guest\Widgets;

use App\Models\Booking;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class GuestOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $userId = Auth::id();
        $email = Auth::user()->email;

        $baseQuery = fn () => Booking::query()->where(function ($q) use ($userId, $email) {
            $q->where('user_id', $userId)
                ->orWhere('guest_email', $email);
        });

        $totalBookings = $baseQuery()->count();
        $activeBookings = $baseQuery()->where('status', 'approved')->count();
        $pendingBookings = $baseQuery()->where('status', 'pending')->count();

        $wallet = Wallet::where('user_id', $userId)->first();
        $walletBalance = $wallet ? $wallet->balance : 0;

        return [
            Stat::make('Total Bookings', $totalBookings)
                ->description('All your bookings')
                ->icon('heroicon-o-calendar-days')
                ->color('primary'),

            Stat::make('Active Stays', $activeBookings)
                ->description('Approved bookings')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Pending', $pendingBookings)
                ->description('Awaiting payment')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Wallet Balance', '₦'.number_format($walletBalance))
                ->description('Available funds')
                ->icon('heroicon-o-wallet')
                ->color('gray'),
        ];
    }
}

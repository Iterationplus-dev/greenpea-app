<?php

namespace App\Filament\Guest\Widgets;

use App\Models\Booking;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class RecentBookings extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Bookings';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->where(function ($q) {
                        $q->where('user_id', Auth::id())
                            ->orWhere('guest_email', Auth::user()->email);
                    })
                    ->with(['apartment.property'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('apartment.name')
                    ->label('Apartment')
                    ->searchable(),

                TextColumn::make('apartment.property.city')
                    ->label('City'),

                TextColumn::make('start_date')
                    ->label('Check-in')
                    ->date('M d, Y'),

                TextColumn::make('end_date')
                    ->label('Check-out')
                    ->date('M d, Y'),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('NGN')
                    ->alignRight(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        'paid' => 'success',
                        default => 'gray',
                    }),
            ])
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('No bookings yet')
            ->emptyStateDescription('Browse apartments and make your first booking!')
            ->paginated(false);
    }
}

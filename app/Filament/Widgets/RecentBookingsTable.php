<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Widgets\TableWidget;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Bookings\BookingResource;

class RecentBookingsTable extends TableWidget
{
    protected static ?int $sort = 9;
    // protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Recent Bookings';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn(): Builder => Booking::query()
                    ->latest()
                    ->limit(4)
            )
            ->striped()
            ->poll('60s')
            ->paginated(false)
            ->defaultSort('created_at', 'desc')
            ->extraAttributes([
                 'class' => 'recent-bookings-compact',
            ])
            ->columns([
                TextColumn::make('reference')
                    ->label('Ref#')
                    ->size('sm')
                    ->weight('medium')
                    ->formatStateUsing(fn ($state) => substr($state, -6)),

                // TextColumn::make('user.name')
                //     ->label('Guest')
                //     ->size('sm')
                //     ->limit(16)
                //     ->placeholder('Guest'),

                TextColumn::make('apartment.name')
                    ->label('Apartment')
                    ->size('sm')
                    ->limit(18),

                TextColumn::make('start_date')
                    ->label('In')
                    ->date('d M')
                    ->size('sm'),

                TextColumn::make('end_date')
                    ->label('Out')
                    ->date('d M')
                    ->size('sm'),

                TextColumn::make('total_amount')
                    ->label('Amt')
                    ->money('NGN')
                    ->size('sm')
                    ->alignEnd(),



                TextColumn::make('created_at')
                    ->label('Date')
                    ->since()
                    ->size('sm'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->url(
                        fn(Booking $record) =>
                        BookingResource::getUrl('view', ['record' => $record])
                    )
                    ->openUrlInNewTab(),
            ])

            ->emptyStateHeading('No recent bookings')
            ->emptyStateDescription('New bookings will appear here automatically.')
            ->emptyStateIcon('heroicon-o-calendar')
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\OwnerEarnings\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;

class OwnerEarningsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('earned_at', 'desc')
            ->emptyStateIcon('heroicon-o-currency-dollar')
            ->emptyStateHeading('No earnings found')
            ->emptyStateDescription('Owner earnings will appear here once bookings are completed.')
            ->paginatedWhileReordering()
            ->deferLoading()
            ->columns([
                TextColumn::make('earned_at')
                    ->label('Date Earned')
                    ->date()
                    ->sortable(),

                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: auth()->user()?->canManageFinance()),

                TextColumn::make('booking.reference')
                    ->label('Booking Ref')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('gross_amount')
                    ->label('Gross Amount')
                    ->money('NGN')
                    ->sortable()
                    ->alignEnd()
                    ->summarize(
                        Sum::make()
                            ->label('Total Gross')
                            ->money('NGN')
                    ),

                TextColumn::make('platform_fee')
                    ->label('Platform Fee')
                    ->money('NGN')
                    ->sortable()
                    ->color('danger')
                    ->alignEnd()
                    ->summarize(
                        Sum::make()
                            ->label('Total Fees')
                            ->money('NGN')
                    ),

                TextColumn::make('net_amount')
                    ->label('Net Amount')
                    ->money('NGN')
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd()
                    ->summarize(
                        Sum::make()
                            ->label('Total Net')
                            ->money('NGN')
                    ),

                TextColumn::make('created_at')
                    ->label('Recorded At')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn($query, $date) => $query->whereDate('earned_at', '>=', $date)
                            )
                            ->when(
                                $data['to'],
                                fn($query, $date) => $query->whereDate('earned_at', '<=', $date)
                            );
                    }),

                SelectFilter::make('owner')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn() => auth()->user()?->isAdmin()),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn() => auth()->user()?->isAdmin()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()?->isAdmin()),
                ]),
            ])
            ->headerActions([])

            // ->footerActions([])

            ->persistFiltersInSession()

            ->recordUrl(null)

            ->poll('60s')

            ->groups([
                Group::make('earned_at')
                    ->date()
                    ->label('Earnings Date'),
            ]);
    }
}

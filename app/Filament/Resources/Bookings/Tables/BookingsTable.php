<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Enums\UserRole;
use App\Models\Booking;
use Filament\Tables\Table;
use App\Enums\BookingStatus;
use Filament\Actions\Action;
use App\Events\BookingApproved;
use App\Services\RefundService;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\DB;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        $admin = auth('admin')->user();
        return $table
            ->striped()
            ->defaultSort('created_at', 'asc')
            // ->modifyQueryUsing(fn(Builder $query) => $query)
            ->modifyQueryUsing(function (Builder $query) {
                $admin = auth('admin')->user();

                if ($admin->type->value === 'owner') {
                    $query->whereHas('apartment.property', function ($q) use ($admin) {
                        $q->where('owner_id', $admin->id);
                    });
                }
            })
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('No bookings found!')
            ->emptyStateDescription('You don\'t have bookings yet. Click the button to add a booking.')
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->paginatedWhileReordering()

            ->columns([
                TextColumn::make('reference')
                    ->searchable()
                    ->label('Reference')
                    ->weight(FontWeight::Bold)
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
                TextColumn::make('guest_name')
                    ->searchable()
                    ->label('Guest')
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
                TextColumn::make('apartment.name')
                    ->label('Apartment')
                    ->extraAttributes(['class' => 'custom-padding-left-column']),
                TextColumn::make('amount')->money('NGN')
                    ->alignRight()
                    ->extraAttributes(['class' => 'custom-padding-right-column']),
                TextColumn::make('status')
                    // ->badge()
                    ->alignCenter()
                    ->color(function ($state) {
                        $state = $state instanceof BookingStatus ? $state : BookingStatus::from($state);

                        return match ($state) {
                            BookingStatus::APPROVED => "success",
                            BookingStatus::PENDING => "warning",
                            BookingStatus::CANCELLED => "danger",
                            BookingStatus::REFUNDED => "info",
                            default => 'gray',
                        };
                    })
                    ->icon(function ($state) {
                        $state = $state instanceof BookingStatus ? $state : BookingStatus::from($state);

                        return match ($state) {
                            BookingStatus::APPROVED => 'heroicon-o-check-circle',
                            BookingStatus::PENDING => 'heroicon-o-arrow-path',
                            BookingStatus::REFUNDED => 'heroicon-o-arrow-uturn-left',
                            BookingStatus::CANCELLED => 'heroicon-o-times',
                            default => null,
                        };
                    }),
                TextColumn::make('created_at')
                    ->date()
                    ->label('Date'),
            ])
            ->deferLoading()
            ->filters([
                //
            ])
            ->recordActions([

                /* APPROVE */
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Booking $record) => $record->status === BookingStatus::PENDING)
                    ->action(function (Booking $record) {

                        if ($record->approved_at) {
                            return;
                        }

                        DB::transaction(function () use ($record) {
                            $record->update([
                                'status' => BookingStatus::APPROVED,
                                'approved_at' => now(),
                            ]);

                            event(new BookingApproved($record->fresh()));
                        });
                    }),

                /* REFUND */
                Action::make('refund')
                    ->label('Refund')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger')
                    ->requiresConfirmation()
                    // ->visible(
                    //     fn(Booking $record) =>
                    //     $record->canBeRefunded()
                    //         && auth()->user()->can('bookings.refund')
                    // )

                    ->visible(
                        fn(Booking $record) =>
                        $record->canBeRefunded()
                            && $admin->canManageFinance()
                    )
                    ->action(
                        fn(Booking $record) =>
                        app(RefundService::class)->refundBooking($record)
                    ),

                /* DOWNLOAD INVOICE */
                Action::make('invoice')
                    ->label('Download Invoice')
                    ->icon('heroicon-o-document-arrow-down')
                    ->openUrlInNewTab()
                    ->url(fn(Booking $record) => $record->invoice?->pdf_url)
                    ->visible(
                        fn(Booking $record) =>
                        $record->invoice !== null &&
                            $record->invoice->pdf_url !== null
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}

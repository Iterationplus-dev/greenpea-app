<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Bookings\BookingResource;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('approve')
                ->label('Approve Booking')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(
                    fn(Booking $record) =>
                    $record->status === 'pending'
                        && auth()->user()->can('bookings.approve')
                )
                ->action(fn(Booking $record) => $record->approve()),

            Action::make('refund')
                ->label('Refund Booking')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(
                    fn(Booking $record) =>
                    $record->status === 'approved'
                        && auth()->user()->can('bookings.refund')
                )
                ->action(fn(Booking $record) => $record->refund()),


            Action::make('invoice')
                ->label('Download Invoice')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn(Booking $record) => $record->invoice?->pdf_url)
                ->openUrlInNewTab()
                ->visible(fn(Booking $record) => filled($record->invoice)),
        ];
    }
}

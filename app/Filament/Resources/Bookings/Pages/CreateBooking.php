<?php

namespace App\Filament\Resources\Bookings\Pages;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Services\BookingService;
use App\Filament\Resources\Bookings\BookingResource;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Create Booking');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Booking Created')
            ->body('The booking was created successfully.')
            ->success();
    }

    /**
     * Centralized booking creation via BookingService
     */
    protected function handleRecordCreation(array $data): Model
    {
        return app(BookingService::class)->create([
            'apartment_id' => $data['apartment_id'],
            'user_id'      => $data['user_id'] ?? null,
            'guest_name'   => $data['guest_name']
                ?? optional($data['user'])->name
                ?? 'Guest',
            'guest_email'  => $data['guest_email']
                ?? optional($data['user'])->email
                ?? null,
            'start_date'   => $data['start_date'],
            'end_date'     => $data['end_date'],
            'amount'       => $data['amount'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('list');
    }
}

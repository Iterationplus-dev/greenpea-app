<?php

namespace App\Filament\Resources\Bookings\Pages;

use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
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


    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['slug'] = Str::slug($data['name']);

    //     return $data;
    // }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Booking Created')
            ->body('New booking added successfully')
            ->success();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('list');
    }
}

<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use App\Enums\GroupLabel;
use App\Models\User;
use App\Models\Booking;

use Filament\Pages\Page;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Filament\Actions\Action;

use App\Models\BookingPayment;
use Filament\Support\Icons\Heroicon;

use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

use App\Filament\Resources\Bookings\BookingResource;
use App\Filament\Resources\Bookings\Schemas\BookingForm;
use App\Services\BookingService;

class WalkInBookingWizard extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.walk-in-booking';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCursorArrowRays;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::OutlinedCursorArrowRipple;
    protected static string | UnitEnum | null $navigationGroup = GroupLabel::BOOKINGS;
    protected static ?string $navigationLabel = 'Walk-In Guest Booking';
    protected static ?int $navigationSort = 1;

    public array $data = [];

    public bool $processing = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getHeading(): string
    {
        return 'Walk-In Guest Booking';
    }

    protected function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return BookingForm::configure($schema)
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewConflict')
                ->label('View Conflicting Booking')
                ->color('danger')
                ->icon('heroicon-o-eye')
                ->visible(function () {
                    $data = $this->data ?? [];
                    return BookingForm::getConflictingBooking($data) !== null;
                })
                ->url(function () {
                    $data = $this->data ?? [];
                    $conflict = BookingForm::getConflictingBooking($data);

                    if (! $conflict) {
                        return null;
                    }

                    return BookingResource::getUrl('view', [
                        'record' => $conflict->id
                    ]);
                })
                ->openUrlInNewTab(),
        ];
    }

    public function submit(): void
    {
        if ($this->processing) {
            return;
        }
        $this->processing = true;

        try {

            $data = $this->data ?? [];

            if (empty($data['apartment'])) {
                Notification::make()
                    ->title('Apartment is required')
                    ->body('Please select an apartment to proceed.')
                    ->danger()
                    ->send();
                return;
            }

            $conflict = BookingForm::getConflictingBooking($data);

            if ($conflict) {

                $message =
                    "CONFLICT DETECTED\n" .
                    "Reference: {$conflict->reference}\n" .
                    "Dates: {$conflict->start_date} → {$conflict->end_date}";

                $this->data['conflict_details'] = $message;

                $this->data['occupied_calendar'] =
                    implode("\n", BookingForm::getOccupiedRanges($data));

                Notification::make()
                    ->title('Booking Conflict')
                    ->body('This apartment is already booked for the selected dates.')
                    ->danger()
                    ->send();

                return;
            }

            if (!empty($data['user_id'])) {
                $user = User::find($data['user_id']);
            } else {
                $user = User::create([
                    'name' => $data['guest_name'],
                    'email' => $data['guest_email'],
                    'phone' => $data['guest_phone'],
                    // 'password' => bcrypt(str()->random(10)),
                    'password' => bcrypt('password123'),
                ]);
            }

            if (isset($data['calculated_amount']) && $data['amount_received'] < $data['calculated_amount']) {
                Notification::make()
                    ->title('Insufficient Payment')
                    ->body('Amount received is less than required booking amount.')
                    ->danger()
                    ->send();
                return;
            }

            $amount = $data['calculated_amount'] = str_replace(',', '', $data['calculated_amount']);
            $booking = Booking::create([
                'user_id' => $user->id,
                'guest_name' => $user->name,
                'guest_email' => $user->email,
                'apartment_id' => $data['apartment'],
                'start_date' => $data['start_date'],
                'reference' => bookingReference(),
                'end_date' => $data['end_date'],
                'amount' => $amount,
                'total_amount' => $amount,
                'net_amount' => $amount,
                'status' => BookingStatus::APPROVED,
            ]);

            $received = $data['amount_received'] = str_replace(',', '', $data['amount_received']);
            BookingPayment::create([
                'booking_id' => $booking->id,
                'amount' => $received,
                'payment_method' => $data['payment_method'],
                'status' => PaymentStatus::SUCCESS->value,
                'reference' => paymentReference(),
                'response' => $data['admin_note'] ?? null,
            ]);

            Notification::make()
                ->title('Booking Completed')
                ->body('Walk-in booking completed successfully')
                ->success()
                ->send();

            $this->redirect(BookingResource::getUrl('index', [
                'record' => $booking->id
            ]));

            // return $this->getResource()::getUrl('index', ['record' => $this->record->id]);

        } finally {
            $this->processing = false;
        }
    }
}

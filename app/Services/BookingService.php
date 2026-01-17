<?php

namespace App\Services;

use App\Exceptions\BookingUnavailableException;
use App\Models\Booking;
use App\Models\Apartment;
use Illuminate\Support\Str;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\DB;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Create a booking with centralized availability validation.
     */
    public function create(array $data): Booking
    {

        return DB::transaction(function () use ($data) {

            // dd(setting('platform_fee_percentage'));

            $this->validateAvailability(
                apartmentId: $data['apartment_id'],
                start: $data['start_date'],
                end: $data['end_date'],
                ignoreBookingId: $data['ignore_booking_id'] ?? null
            );
            // logger()->info('Availability check passed');

            // dd($data['reference']);
            return Booking::create([
                'apartment_id' => $data['apartment_id'],
                'user_id'      => $data['user_id'] ?? null,
                'guest_name'   => $data['guest_name'],
                'guest_email'  => $data['guest_email'],
                'start_date'   => $data['start_date'],
                'end_date'     => $data['end_date'],
                'amount'       => $data['amount'],
                'total_amount' => $data['amount'],
                'net_amount'   => $data['amount'],
                'status'       => BookingStatus::PENDING->value ?? 'pending',
                'reference'    => $data['reference'] ?? bookingReference(),
            ]);
        });
    }

    /**
     * Central availability validation.
     */
    protected function validateAvailability(
        int $apartmentId,
        string $start,
        string $end,
        ?int $ignoreBookingId = null
    ): void {
        $conflictQuery = Booking::where('apartment_id', $apartmentId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            });

        if ($ignoreBookingId) {
            $conflictQuery->where('id', '!=', $ignoreBookingId);
        }

        if ($conflictQuery->exists()) {
            // logger()->info('Availability check passed');
            throw new BookingUnavailableException(
                'This apartment is already booked for the selected dates!'
            );
        }
    }
}

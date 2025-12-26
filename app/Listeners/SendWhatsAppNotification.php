<?php

namespace App\Listeners;

use App\Events\BookingPaid;
use App\Services\WhatsAppService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWhatsAppNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    // public function handle(object $event): void
    // {

    // }

    public function handle(BookingPaid $event)
    {
        // WhatsAppService::send(
        //     $event->booking->guest->phone,
        //     "Your booking is fully paid 🎉"
        // );

        $booking = $event->booking;
        $guest = $booking->guest;

        if (! $guest->phone) {
            return;
        }

        WhatsAppService::send(
            $guest->phone,
            "🎉 Your booking is fully paid!\n\n"
                . "Apartment: {$booking->apartment->name}\n"
                . "Amount: ₦" . number_format($booking->net_amount, 2)
        );
    }
}

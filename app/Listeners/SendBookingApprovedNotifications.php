<?php

namespace App\Listeners;

use App\Events\BookingApproved;
use App\Mail\BookingApprovedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingApprovedNotifications
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
    public function handle(BookingApproved $event): void
    {
        //email
        //whatsapp
        //invoice

        $booking = $event->booking;

        // Email guest
        Mail::to($booking->user->email)
            ->send(new BookingApprovedMail($booking));

        // Email admin
        Mail::to(config('mail.admin_address'))
            ->send(new BookingApprovedMail($booking));
    }
}

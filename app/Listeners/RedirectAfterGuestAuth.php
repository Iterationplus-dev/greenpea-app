<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RedirectAfterGuestAuth
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
    public function handle(object $event): void
    {
        // if (session()->has('booking_intent')) {
        //     redirect()->route('booking.confirm')->send();
        // }

        // Only act for web users (guest panel)
        if (auth()->guard('web')->check() && session()->has('booking_intent')) {
            redirect()->route('booking.confirm')->send();
        }
    }
}

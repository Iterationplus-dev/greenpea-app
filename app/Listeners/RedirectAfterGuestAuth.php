<?php

namespace App\Listeners;

use Filament\Facades\Filament;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        /*
        // Only for frontend users
        if (! auth()->guard('web')->check()) {
            return;
        }

        // Redis-safe: pull intent from previous session
        $intent = session()->get('booking.intent');

        if (! $intent) {
            return;
        }

        // Re-store intent on the NEW session ID
        session()->put('booking.intent', $intent);
        session()->save();

        redirect()->route('booking.confirm')->send();
        */
        if (! auth()->guard('web')->check()) {
            return;
        }

        if (! session()->has('booking.intent')) {
            return;
        }

        session()->save();

        redirect()->to(
            Filament::getPanel('guest')->getUrl() . '/continue-booking'
        )->send();
    }
}

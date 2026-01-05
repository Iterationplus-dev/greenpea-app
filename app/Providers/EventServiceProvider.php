<?php

namespace App\Providers;

use App\Events\BookingApproved;
// use Filament\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use App\Listeners\RedirectAfterGuestAuth;
use App\Listeners\ProcessBookingApproval;
use App\Listeners\SendBookingApprovedNotifications;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            RedirectAfterGuestAuth::class,
        ],
        Login::class => [
            RedirectAfterGuestAuth::class,
        ],

        BookingApproved::class => [
            ProcessBookingApproval::class,
            SendBookingApprovedNotifications::class,
        ],

        \App\Events\PaymentReceived::class => [
            \App\Listeners\ProcessPaymentReceived::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}

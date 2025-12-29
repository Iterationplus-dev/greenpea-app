<?php

namespace App\Providers;

use App\Events\BookingApproved;
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

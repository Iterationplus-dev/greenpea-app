<?php

namespace App\Providers;

use App\Events\BookingPaid;
use App\Listeners\SendWhatsAppNotification;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Refund;
use App\Models\Wallet;
use App\Observers\AuditObserver;
use App\Observers\BookingObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\BookingPaymentObserver;
use Symfony\Contracts\EventDispatcher\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BookingPayment::observe(BookingPaymentObserver::class);
        Booking::observe(BookingObserver::class);
        // Booking::observe(AuditObserver::class);
        // BookingPayment::observe(AuditObserver::class);
        // Refund::observe(AuditObserver::class);
        // Wallet::observe(AuditObserver::class);

        //
        // Event::listen(
        //    BookingPaid::class,
        //     SendWhatsAppNotification::class
        // );

    }

}

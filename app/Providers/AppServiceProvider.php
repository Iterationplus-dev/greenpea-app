<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\BookingPayment;
use Filament\Facades\Filament;
use App\Observers\BookingObserver;
use Illuminate\Support\Facades\Auth;
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

        // ───────── COMPACT MODE SUPPORT ─────────
        Filament::serving(function () {
            $admin = Auth::guard('admin')->user();

            if ($admin?->compact_tables) {
                Filament::registerRenderHook(
                    'body.start',
                    fn() => '<script>document.body.classList.add("compact-mode")</script>'
                );
            }
        });

        //
        // Event::listen(
        //    BookingPaid::class,
        //     SendWhatsAppNotification::class
        // );

    }
}

<?php

namespace App\Listeners;

use InvoiceService;
use OwnerEarningService;
use App\Events\BookingPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordOwnerEarning implements ShouldQueue
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

    public function handle(BookingPaid $event): void
    {
        OwnerEarningService::record($event->booking);
        InvoiceService::generate($event->booking);
    }
}

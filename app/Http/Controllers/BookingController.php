<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function show(Apartment $apartment)
    {
        $apartment->load(['images', 'property', 'amenities']);

        return view('booking.show', compact('apartment'));
    }

    public function store(Request $request, Apartment $apartment)
    {
        $booking = Booking::create([
            'apartment_id' => $apartment->id,
            'guest_name' => $request->name,
            'guest_email' => $request->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
            'amount' => $apartment->price_per_month,
        ]);

        return redirect()->route('booking.thankyou');
    }

    public function pay(Booking $booking)
    {
        return redirect($booking->payment_link);
    }
}

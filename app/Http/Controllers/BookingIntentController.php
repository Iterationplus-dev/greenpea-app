<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingIntentController extends Controller
{
    public function store(Request $request, Apartment $apartment)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
        ]);

        session([
            'booking_intent' => [
                'apartment_id' => $apartment->id,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
            ],
        ]);

        //auth()->check()
        if (! Auth::check()) {
            // return redirect()->to('guest/register')
            return redirect()->route('filament.guest.auth.register')
                ->with('info', 'Create an account to continue your booking.');
        }

        return redirect()->route('booking.confirm');
    }
}

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
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        // session([
        //     'booking_intent' => [
        //         'apartment_id' => $apartment->id,
        //         'start_date'   => $request->start_date,
        //         'end_date'     => $request->end_date,
        //     ],
        // ]);

        session()->put('booking.intent', [
            'apartment_id' => $apartment->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        session()->save();

        if (! Auth::check()) {
            return redirect()->route('filament.guest.auth.register')
                ->with('info', 'Create an account to continue your booking.');
        }

        return redirect()->to('/guest/continue-booking');
    }

    public function confirm()
    {
        $intent = session('booking.intent');
        abort_if(! $intent, 404);

        return view('booking.confirm', [
            'apartment' => Apartment::findOrFail($intent['apartment_id']),
            'intent' => $intent,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Apartment;

class HomeController extends Controller
{
    public function index()
    {
        $cities = ['Abuja', 'Lagos', 'Port Harcourt', 'Enugu'];

        // $apartments = Apartment::with(['featuredImage', 'property'])
        //     ->where('is_available', true)
        //     ->get()
        //     ->groupBy(fn($a) => $a->property->city);

        $apartments = Apartment::with(['featuredImage', 'images', 'property'])
            ->where('is_available', true)
            ->get()
            ->groupBy(fn ($a) => $a->property->city);

        return view('home', compact('cities', 'apartments'));
    }
}

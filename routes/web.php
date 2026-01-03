<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/paystack/webhook', [\App\Http\Controllers\PaystackWebhookController::class, 'handle'])
    ->name('paystack.webhook');


Route::domain('admin.greenpea-app.test')->group(function () {
    // Filament auto registers its routes here
});

// Route::middleware(['auth', 'role:super_admin'])->group(function () {
//     Route::get('/admin', fn () => 'Admin Area');
// });

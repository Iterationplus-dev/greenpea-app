<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingIntentController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\PaystackCallbackController;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/apartments/{apartment}', [\App\Http\Controllers\BookingController::class, 'show']);
Route::post('/book/{apartment}', [\App\Http\Controllers\BookingController::class, 'store']);
Route::get('/booking/{booking}/pay', [\App\Http\Controllers\BookingController::class, 'pay']);

Route::post('/booking/intent/{apartment}', [BookingIntentController::class, 'store'])
    ->name('booking.intent');


Route::post('/paystack/webhook', [\App\Http\Controllers\PaystackWebhookController::class, 'handle'])
    ->name('paystack.webhook');


Route::domain('admin.greenpea-app.test')->group(function () {
    // Filament auto registers its routes here
});

// Route::middleware(['auth', 'role:super_admin'])->group(function () {
//     Route::get('/admin', fn () => 'Admin Area');
// });

Route::get('/bookings/{booking}/pay', [BookingPaymentController::class, 'pay'])
    ->middleware('auth')
    ->name('bookings.pay');
Route::get('/payments/paystack/callback', [PaystackCallbackController::class, 'handle'])
    ->name('paystack.callback');

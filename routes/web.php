<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingIntentController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaystackCallbackController;
use App\Http\Controllers\PaystackWebhookController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::view('/faqs', 'faqs')->name('faqs');
Route::get('/apartments/{apartment}', [BookingController::class, 'show']);
Route::post('/book/{apartment}', [BookingController::class, 'store']);
Route::get('/booking/{booking}/pay', [BookingController::class, 'pay']);

Route::post('/booking/intent/{apartment}', [BookingIntentController::class, 'store'])
    ->name('booking.intent');

Route::post('/paystack/webhook', [PaystackWebhookController::class, 'handle'])
    ->name('paystack.webhook');

Route::domain(config('app.admin_domain'))->group(function () {
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

Route::get('/wallet/paystack/init', [WalletController::class, 'init'])
    ->name('wallet.paystack.init');

Route::get('/wallet/paystack/callback', [WalletController::class, 'callback'])
    ->name('wallet.paystack.callback');

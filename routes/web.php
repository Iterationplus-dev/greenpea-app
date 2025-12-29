<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/paystack/webhook', [\App\Http\Controllers\PaystackWebhookController::class, 'handle'])
    ->name('paystack.webhook');


// Route::middleware(['auth', 'role:super_admin'])->group(function () {
//     Route::get('/admin', fn () => 'Admin Area');
// });

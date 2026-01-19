<?php

use Filament\Facades\Filament;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    // ->withMiddleware(function (Middleware $middleware): void {
    //     //
    // })

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function () {
            return Filament::getLoginUrl();
        });
    })

    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('reminders:send')->dailyAt('09:00');
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

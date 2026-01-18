<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Handler extends Exception
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {

            if (Auth::guard('admin')->check()) {
                Auth::guard('admin')->logout();
            }

            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if (str_starts_with($request->path(), 'admin')) {
                return redirect()->route('filament.admin.auth.login')
                    ->with('error', 'Session expired. Please login again.');
            }

            return redirect()->route('filament.web.auth.login')
                ->with('error', 'Session expired. Please login again.');
        }

        return parent::render($request, $exception);
    }
}

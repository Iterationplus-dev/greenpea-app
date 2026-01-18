<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;

class HandleSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $exception) {

            // Logout both guards safely
            if (Auth::guard('admin')->check()) {
                Auth::guard('admin')->logout();
            }

            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }

            // Invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Determine which panel user was on
            $path = $request->path();

            if (str_starts_with($path, 'admin')) {
                return redirect()
                    ->route('filament.admin.auth.login')
                    ->with('error', 'Your session expired. Please login again.');
            }
            // Default to guest panel
            return redirect()
                ->route('filament.web.auth.login')
                ->with('error', 'Your session expired. Please login again.');
        }

    }
}

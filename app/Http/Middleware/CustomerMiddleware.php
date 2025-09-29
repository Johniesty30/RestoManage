<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->isCustomer()) {
            return $next($request);
        }

        // Jika staff mencoba akses customer portal, redirect ke staff dashboard
        if ($user->isStaff()) {
            return redirect()->route('staff.dashboard');
        }

        abort(403, 'Unauthorized access.');
    }
}

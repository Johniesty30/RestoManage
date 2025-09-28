<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $staffRoles = ['admin', 'manager', 'chef', 'waiter', 'cashier'];

        if (in_array($request->user()->role, $staffRoles)) {
            return $next($request);
        }

        abort(403, 'Staff access required.');
    }
}

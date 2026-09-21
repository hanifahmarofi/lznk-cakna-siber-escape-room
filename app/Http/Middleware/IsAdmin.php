<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in AND has admin clearance
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Kick unauthorized agents back to the dashboard with a lore-friendly warning
        return redirect()->route('dashboard')->with('error', 'UNAUTHORIZED ACCESS: Director Level Clearance Required.');
    }
}

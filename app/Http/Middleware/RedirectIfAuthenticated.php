<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If user already has API token, redirect to dashboard
        if (session('api_token')) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}

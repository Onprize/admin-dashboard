<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiTokenCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user has API token in session
        if (!session('api_token')) {
            return redirect('/login');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MfaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('mfa_user_id')) {
            // If they are on the MFA routes, let them through
            if ($request->routeIs('auth.mfa.*')) {
                return $next($request);
            }

            // Otherwise, redirect them to the MFA verification page
            return redirect()->route('auth.mfa.show');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        try {
            $expectedRole = UserRole::fromSlug($role);
        } catch (\ValueError) {
            abort(403, 'Unknown role');
        }

        if (Auth::user()->role_id !== $expectedRole->value) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $roleIds = [
            'admin' => 1,
            'staff' => 2,
            'customer' => 3,
            'vendor' => 4,
        ];

        if (!isset($roleIds[$role]) || Auth::user()->role_id !== $roleIds[$role]) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
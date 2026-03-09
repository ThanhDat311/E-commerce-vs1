<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class Currency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var CurrencyService $currencyService */
        $currencyService = app(CurrencyService::class);
        $currency = $request->get('currency', session('currency', 'VND'));

        if ($currencyService->isSupported($currency)) {
            session(['currency' => strtoupper($currency)]);
        } else {
            $currency = 'VND'; // Fallback
        }

        View::share('activeCurrency', strtoupper($currency));

        return $next($request);
    }
}

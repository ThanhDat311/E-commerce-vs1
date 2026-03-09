<?php

use App\Services\CurrencyService;

if (! function_exists('format_price')) {
    /**
     * Convert and format a base price (VND) to the active session currency.
     *
     * @param  float  $amount  Base amount in VND
     * @return string Formatted price string with symbol
     */
    function format_price(float $amount): string
    {
        /** @var CurrencyService $currencyService */
        $currencyService = app(CurrencyService::class);
        $activeCurrency = $currencyService->getActiveCurrency();

        $converted = $currencyService->convert($amount, 'VND', $activeCurrency);

        return $currencyService->format($converted, $activeCurrency);
    }
}

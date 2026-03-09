<?php

namespace App\Services;

class CurrencyService
{
    /**
     * Supported currencies and their symbols.
     */
    protected array $symbols = [
        'VND' => '₫',
        'USD' => '$',
        'EUR' => '€',
    ];

    /**
     * Static exchange rates (Base: VND).
     * In a production app, these would come from an API or database.
     */
    protected array $rates = [
        'VND' => 1.0,
        'USD' => 0.000040,      // e.g., 1 USD = 25,000 VND
        'EUR' => 0.000037,      // e.g., 1 EUR = 27,000 VND
    ];

    /**
     * Get the active currency from the session.
     */
    public function getActiveCurrency(): string
    {
        return session('currency', 'VND');
    }

    /**
     * Check if a currency code is supported.
     */
    public function isSupported(string $currency): bool
    {
        return array_key_exists(strtoupper($currency), $this->rates);
    }

    /**
     * Convert an amount from one currency to another.
     */
    public function convert(float $amount, string $from = 'VND', ?string $to = null): float
    {
        $to = $to ?? $this->getActiveCurrency();
        $from = strtoupper($from);
        $to = strtoupper($to);

        if (! $this->isSupported($from) || ! $this->isSupported($to)) {
            return $amount; // Fallback to original if unsupported
        }

        if ($from === $to) {
            return $amount;
        }

        // Convert base to VND first (if not VND)
        $amountInVnd = $from === 'VND' ? $amount : $amount / $this->rates[$from];

        // Convert VND to target
        return $amountInVnd * $this->rates[$to];
    }

    /**
     * Format a converted amount with its proper symbol.
     */
    public function format(float $amount, string $currency = 'VND'): string
    {
        $currency = strtoupper($currency);
        $symbol = $this->symbols[$currency] ?? $currency;

        if ($currency === 'VND') {
            return number_format($amount, 0, ',', '.').' '.$symbol;
        }

        return $symbol.number_format($amount, 2, '.', ',');
    }
}

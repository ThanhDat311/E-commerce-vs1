<?php

use App\Services\CurrencyService;

beforeEach(function () {
    $this->service = app(CurrencyService::class);
    session()->put('currency', 'VND');
});

it('defaults to VND active currency', function () {
    expect($this->service->getActiveCurrency())->toBe('VND');
});

it('can check if currency is supported', function () {
    expect($this->service->isSupported('usd'))->toBeTrue();
    expect($this->service->isSupported('xyz'))->toBeFalse();
});

it('can convert currencies', function () {
    // 1 VND = 1 VND
    expect($this->service->convert(1000, 'VND', 'VND'))->toBe(1000.0);

    // Convert 100,000 VND to USD (rate 0.000040)
    expect($this->service->convert(100000, 'VND', 'USD'))->toBe(4.0);

    // Convert 4 USD to EUR (first 4 USD -> 100000 VND, then * EUR rate 0.000037)
    expect(round($this->service->convert(4, 'USD', 'EUR'), 2))->toBe(3.7);
});

it('formats currencies correctly', function () {
    expect($this->service->format(150000, 'VND'))->toBe('150.000 ₫');
    expect($this->service->format(6.25, 'USD'))->toBe('$6.25');
    // Default fallback to requested code if supported but no explicit symbol mapped
    expect($this->service->format(10, 'EUR'))->toBe('€10.00');
});

it('global helper format_price works with active currency', function () {
    session()->put('currency', 'USD');
    // Global helper gets active currency (USD) and converts base (VND)
    // 50,000 VND -> 50000 * 0.000040 = 2 USD -> $2.00
    expect(format_price(50000))->toBe('$2.00');
});

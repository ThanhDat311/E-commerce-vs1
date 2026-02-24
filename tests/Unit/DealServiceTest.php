<?php

use App\Services\PriceCalculatorService;

// ────────────────────────────────────────────────────────────────────────────
// PriceCalculatorService – Pure unit tests (no DB needed)
// ────────────────────────────────────────────────────────────────────────────

test('percent discount calculated correctly', function () {
    $calc = new PriceCalculatorService;
    expect($calc->calculatePercent(100, 20))->toBe(80.0);
});

test('percent discount cannot go below zero', function () {
    $calc = new PriceCalculatorService;
    expect($calc->calculatePercent(10, 200))->toBe(0.0);
});

test('fixed discount calculated correctly', function () {
    $calc = new PriceCalculatorService;
    expect($calc->calculateFixed(100, 15))->toBe(85.0);
});

test('fixed discount cannot go below zero', function () {
    $calc = new PriceCalculatorService;
    expect($calc->calculateFixed(10, 50))->toBe(0.0);
});

test('bogo discount correct for even quantity', function () {
    $calc = new PriceCalculatorService;
    $items = [['price' => 50.0, 'quantity' => 4]];
    // 4 items → 2 pairs → 2 free at $50 = $100 discount
    expect($calc->calculateBOGO($items))->toBe(100.0);
});

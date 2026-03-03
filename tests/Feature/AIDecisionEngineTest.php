<?php

use App\Services\AIDecisionEngine;
use Illuminate\Support\Facades\Cache;

test('assess fraud risk for guest checkout with high value and round amount', function () {
    Cache::forget('risk_rules');
    Cache::put('risk_rules', [
        'guest_checkout' => 20,
        'high_value_5000' => 50,
        'round_amount' => 10,
    ]);

    $engine = new AIDecisionEngine();
    $orderData = ['total' => 6000, 'quantity' => 2];
    $userData = ['id' => null];
    $contextData = ['hour' => 14];

    $result = $engine->assessFraudRisk($orderData, $userData, $contextData);

    // 20 + 50 + 10 = 80 -> BLOCK
    expect($result['score'])->toBe(80)
        ->and($result['decision'])->toBe('BLOCK');
});

test('assess fraud risk for suspicious timing', function () {
    Cache::forget('risk_rules');
    Cache::put('risk_rules', [
        'suspicious_time' => 30,
    ]);

    $engine = new AIDecisionEngine();
    $orderData = ['total' => 501, 'quantity' => 1];
    $userData = ['id' => 1, 'created_at' => now()->subDays(1)];
    $contextData = ['hour' => 2];

    $result = $engine->assessFraudRisk($orderData, $userData, $contextData);

    // 30 -> APPROVE (threshold 50)
    expect($result['score'])->toBe(30)
        ->and($result['decision'])->toBe('APPROVE');
});

test('assess inventory risk for critical level', function () {
    $engine = new AIDecisionEngine();
    $productData = ['stock_quantity' => 2];
    $demandData = [];

    $result = $engine->assessInventoryRisk($productData, $demandData);

    expect($result['score'])->toBe(60)
        ->and($result['decision'])->toBe('URGENT_RESTOCK');
});

test('suggest dynamic price for high demand', function () {
    $engine = new AIDecisionEngine();
    $productData = ['price' => 100, 'cost_price' => 70];
    $marketData = ['high_demand' => true];

    $result = $engine->suggestDynamicPrice($productData, $marketData);

    expect($result['decision'])->toBe(110.0);
});

test('suggest dynamic price respects cost margin', function () {
    $engine = new AIDecisionEngine();
    $productData = ['price' => 100, 'cost_price' => 90]; // Min margin 108
    $marketData = ['competitor_lower_price' => true, 'competitor_price' => 70];

    $result = $engine->suggestDynamicPrice($productData, $marketData);

    expect($result['decision'])->toBe(108.0);
});

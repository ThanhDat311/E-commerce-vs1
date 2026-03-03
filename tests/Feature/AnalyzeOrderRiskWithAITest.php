<?php

use App\Models\AiFeatureStore;
use App\Models\Order;
use App\Jobs\AnalyzeOrderRiskWithAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

test('it handles fallback simulation when no api key is present', function () {
    // Ensure API key is empty
    Config::set('services.openai.key', null);

    // Create an order and a feature log
    $order = Order::factory()->create(['total' => 1200]);
    $log = AiFeatureStore::create([
        'order_id' => $order->id,
        'ip_address' => '127.0.0.1',
        'risk_score' => 0.65,
        'reasons' => ['Medium value order ($1200)']
    ]);

    // Run the job synchronously
    (new AnalyzeOrderRiskWithAI($log->id))->handle();

    // Refresh and assert
    $log->refresh();
    expect($log->ai_insight)->toContain('System Simulation Mode')
        ->and($log->ai_insight)->toContain('algorithmic risk score is 65');
});

test('it calls openai api and saves insight when api key is present', function () {
    // Set a fake API key
    Config::set('services.openai.key', 'fake-key');

    // Mock the HTTP response
    Http::fake([
        'api.openai.com/*' => Http::response([
            'choices' => [
                [
                    'message' => [
                        'content' => json_encode([
                            'insight' => 'This order appears moderately risky due to the high amount but from a registered user.',
                            'recommendation' => 'REVIEW'
                        ])
                    ]
                ]
            ],
            'usage' => [
                'prompt_tokens' => 120,
                'completion_tokens' => 30,
                'total_tokens' => 150
            ],
            'model' => 'gpt-3.5-turbo'
        ], 200)
    ]);

    // Create an order and a feature log
    $order = Order::factory()->create(['total' => 1200]);
    $log = AiFeatureStore::create([
        'order_id' => $order->id,
        'ip_address' => '192.168.1.1',
        'risk_score' => 0.45,
        'reasons' => ['Medium value order ($1200)']
    ]);

    // Run the job
    (new AnalyzeOrderRiskWithAI($log->id))->handle();

    // Refresh and assert
    $log->refresh();
    expect($log->ai_insight)->toBe('[REVIEW] This order appears moderately risky due to the high amount but from a registered user.');

    // Verify HTTP call was made
    Http::assertSent(fn($request) => $request->hasHeader('Authorization', 'Bearer fake-key'));
});

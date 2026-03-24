<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * AI Microservice Client
 *
 * HTTP client để giao tiếp với E-commerce-AI FastAPI microservice.
 * Tuân thủ Fail-Open principle: nếu service không khả dụng, trả về null.
 * Controller/Service nhận null phải tự áp dụng fallback logic.
 *
 * Endpoints:
 *   POST /api/v1/predict-login-risk
 *   POST /api/v1/predict-transaction-fraud
 */
class AiMicroserviceClient
{
    private string $baseUrl;

    private string $apiKey;

    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ai_microservice.url', 'http://localhost:8000'), '/');
        $this->apiKey = config('services.ai_microservice.api_key', '');
        $this->timeout = (int) config('services.ai_microservice.timeout', 3);
    }

    /**
     * Dự đoán rủi ro đăng nhập (Login Risk Assessment).
     *
     * @param  string  $ip  IP address của request
     * @param  string  $userAgent  User-Agent header
     * @param  string  $deviceType  'desktop' | 'mobile' | 'tablet'
     * @param  string|null  $country  Mã quốc gia (ISO 2-letter), ví dụ 'VN', 'US'
     * @param  int|null  $rttMs  Round-trip time estimate (ms), tuỳ chọn
     * @return array|null ['risk_score' => float, 'auth_decision' => string, 'reasons' => array]
     *                    null nếu AI service không khả dụng hoặc timeout.
     */
    public function predictLoginRisk(
        int $userId,
        string $ip,
        string $userAgent,
        string $deviceType = 'desktop',
        ?string $country = null,
        ?int $rttMs = null,
        ?string $timestamp = null,
        ?string $city = null,
        ?string $deviceId = null,
        ?bool $isNewDevice = null,
        ?int $recentFailedAttempts = null
    ): ?array {
        try {
            $payload = [
                'user_id' => $userId,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'device_type' => $deviceType,
                'country' => $country,
                'rtt_ms' => $rttMs,
                'timestamp' => $timestamp,
                'city' => $city,
                'device_id' => $deviceId,
                'is_new_device' => $isNewDevice,
                'recent_failed_attempts' => $recentFailedAttempts,
            ];

            Log::info('[AiMicroserviceClient] Sending Login Risk Request', [
                'url' => "{$this->baseUrl}/api/v1/predict-login-risk",
                'payload' => $payload,
            ]);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-API-KEY' => $this->apiKey,
                ])
                ->post("{$this->baseUrl}/api/v1/predict-login-risk", $payload);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['data'])) {
                    Log::debug('[AiMicroserviceClient] Login risk assessed.', [
                        'user_id' => $userId,
                        'risk_score' => $body['data']['risk_score'] ?? null,
                        'decision' => $body['data']['auth_decision'] ?? null,
                    ]);

                    return $body['data'];
                }
            }

            Log::warning('[AiMicroserviceClient] Login risk endpoint returned non-success.', [
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::warning('[AiMicroserviceClient] predictLoginRisk failed (service down?): '.$e->getMessage());

            return null;
        }
    }

    /**
     * Dự đoán gian lận giao dịch (Transaction Fraud Detection).
     *
     * @param  string  $paymentMethod  'card' | 'paypal' | 'crypto' | 'wallet'
     * @return array|null ['risk_score' => float, 'decision' => string, 'reasons' => array]
     *                    null nếu AI service không khả dụng hoặc timeout.
     */
    public function predictTransactionFraud(
        int $userId,
        int $orderId,
        float $totalAmount,
        string $paymentMethod = 'card',
        ?string $productCategory = null,
        ?int $customerAge = null
    ): ?array {
        try {
            $payload = [
                'user_id' => $userId,
                'order_id' => $orderId,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'product_category' => $productCategory,
                'customer_age' => $customerAge,
            ];

            Log::info('[AiMicroserviceClient] Sending Transaction Fraud Request', [
                'url' => "{$this->baseUrl}/api/v1/predict-transaction-fraud",
                'payload' => $payload,
            ]);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-API-KEY' => $this->apiKey,
                ])
                ->post("{$this->baseUrl}/api/v1/predict-transaction-fraud", $payload);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['data'])) {
                    Log::debug('[AiMicroserviceClient] Transaction fraud assessed.', [
                        'order_id' => $orderId,
                        'risk_score' => $body['data']['risk_score'] ?? null,
                        'decision' => $body['data']['decision'] ?? null,
                    ]);

                    return $body['data'];
                }
            }

            Log::warning('[AiMicroserviceClient] Transaction fraud endpoint returned non-success.', [
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::warning('[AiMicroserviceClient] predictTransactionFraud failed (service down?): '.$e->getMessage());

            return null;
        }
    }

    /**
     * Generate a product description using the AI microservice.
     *
     * @param  array  $productData  ['name' => string, 'price' => float, 'category' => string]
     * @return string|null The generated description, or null if unavailable.
     */
    public function generateProductDescription(array $productData): ?string
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['X-API-KEY' => $this->apiKey])
                ->post("{$this->baseUrl}/api/v1/generate-description", $productData);

            if ($response->successful()) {
                return $response->json('data.description') ?? null;
            }

            Log::warning('[AiMicroserviceClient] generateProductDescription returned non-success.', [
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::warning('[AiMicroserviceClient] generateProductDescription failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Kiểm tra xem AI service có đang hoạt động không.
     */
    public function isHealthy(): bool
    {
        try {
            $response = Http::timeout(2)->get("{$this->baseUrl}/");

            return $response->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }
}

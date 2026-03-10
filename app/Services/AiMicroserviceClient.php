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
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ai_microservice.url', 'http://localhost:8000'), '/');
        $this->timeout = (int) config('services.ai_microservice.timeout', 3);
    }

    /**
     * Dự đoán rủi ro đăng nhập (Login Risk Assessment).
     *
     * @param int         $userId
     * @param string      $ip          IP address của request
     * @param string      $userAgent   User-Agent header
     * @param string      $deviceType  'desktop' | 'mobile' | 'tablet'
     * @param string|null $country     Mã quốc gia (ISO 2-letter), ví dụ 'VN', 'US'
     * @param int|null    $rttMs       Round-trip time estimate (ms), tuỳ chọn
     *
     * @return array|null ['risk_score' => float, 'auth_decision' => string, 'reasons' => array]
     *                    null nếu AI service không khả dụng hoặc timeout.
     */
    public function predictLoginRisk(
        int $userId,
        string $ip,
        string $userAgent,
        string $deviceType = 'desktop',
        ?string $country = null,
        ?int $rttMs = null
    ): ?array {
        try {
            $payload = [
                'user_id'     => $userId,
                'ip_address'  => $ip,
                'user_agent'  => $userAgent,
                'device_type' => $deviceType,
                'country'     => $country,
                'rtt_ms'      => $rttMs,
            ];

            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/predict-login-risk", $payload);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['data'])) {
                    Log::debug('[AiMicroserviceClient] Login risk assessed.', [
                        'user_id'     => $userId,
                        'risk_score'  => $body['data']['risk_score'] ?? null,
                        'decision'    => $body['data']['auth_decision'] ?? null,
                    ]);
                    return $body['data'];
                }
            }

            Log::warning('[AiMicroserviceClient] Login risk endpoint returned non-success.', [
                'status' => $response->status(),
            ]);
            return null;
        } catch (\Throwable $e) {
            Log::warning('[AiMicroserviceClient] predictLoginRisk failed (service down?): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Dự đoán gian lận giao dịch (Transaction Fraud Detection).
     *
     * @param int    $userId
     * @param int    $orderId
     * @param float  $totalAmount
     * @param string $paymentMethod  'card' | 'paypal' | 'crypto' | 'wallet'
     * @param string|null $productCategory
     * @param int|null    $customerAge
     *
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
                'user_id'          => $userId,
                'order_id'         => $orderId,
                'total_amount'     => $totalAmount,
                'payment_method'   => $paymentMethod,
                'product_category' => $productCategory,
                'customer_age'     => $customerAge,
            ];

            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/predict-transaction-fraud", $payload);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['data'])) {
                    Log::debug('[AiMicroserviceClient] Transaction fraud assessed.', [
                        'order_id'   => $orderId,
                        'risk_score' => $body['data']['risk_score'] ?? null,
                        'decision'   => $body['data']['decision'] ?? null,
                    ]);
                    return $body['data'];
                }
            }

            Log::warning('[AiMicroserviceClient] Transaction fraud endpoint returned non-success.', [
                'status' => $response->status(),
            ]);
            return null;
        } catch (\Throwable $e) {
            Log::warning('[AiMicroserviceClient] predictTransactionFraud failed (service down?): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Kiểm tra xem AI service có đang hoạt động không.
     *
     * @return bool
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

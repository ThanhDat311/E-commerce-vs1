<?php

namespace Tests\Feature;

use App\Services\AiMicroserviceClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiMicroserviceClientTest extends TestCase
{
    private AiMicroserviceClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        // Cấu hình môi trường Test để không gọi API thật
        config([
            'services.ai_microservice.url' => 'http://localhost:8000',
            'services.ai_microservice.api_key' => 'test-secret-key',
            'services.ai_microservice.timeout' => 1,
        ]);

        $this->client = new AiMicroserviceClient;
    }

    /**
     * Test Fail-Open: API bị quá tải hoặc sập (Time-out, Error 500)
     */
    public function test_predict_login_risk_fail_open_when_service_down()
    {
        // Giả lập Http bị lỗi Server 500
        Http::fake([
            '*/api/v1/predict-login-risk' => Http::response(null, 500),
        ]);

        $result = $this->client->predictLoginRisk(1, '127.0.0.1', 'test_agent');

        // Khẳng định: Hệ thống không crash mà trả về null (Fail-Open Principle)
        $this->assertNull($result);
    }

    /**
     * Test Phản hồi thành công (Normal Case)
     */
    public function test_predict_login_risk_success()
    {
        // Giả lập HTTP trả kết quả mẫu từ Python Microservice
        Http::fake([
            '*/api/v1/predict-login-risk' => Http::response([
                'status' => 'success',
                'data' => [
                    'risk_score' => 0.05,
                    'auth_decision' => 'passive_auth_allow',
                    'reasons' => ['Safe device'],
                ],
            ], 200),
        ]);

        $result = $this->client->predictLoginRisk(1, '127.0.0.1', 'test_agent');

        $this->assertNotNull($result);
        $this->assertEquals(0.05, $result['risk_score']);
        $this->assertEquals('passive_auth_allow', $result['auth_decision']);
    }

    /**
     * Test Giao dịch mua hàng bị lỗi (Time-out/Down)
     */
    public function test_predict_transaction_fraud_fail_open()
    {
        Http::fake([
            '*/api/v1/predict-transaction-fraud' => Http::response(null, 500),
        ]);

        $result = $this->client->predictTransactionFraud(1, 1001, 200.50);

        $this->assertNull($result);
    }

    /**
     * Test Giao dịch được phát hiện thành công
     */
    public function test_predict_transaction_fraud_success()
    {
        Http::fake([
            '*/api/v1/predict-transaction-fraud' => Http::response([
                'status' => 'success',
                'data' => [
                    'risk_score' => 0.85,
                    'decision' => 'block',
                    'reasons' => ['High value', 'New IP'],
                ],
            ], 200),
        ]);

        $result = $this->client->predictTransactionFraud(1, 1001, 200.50);

        $this->assertNotNull($result);
        $this->assertEquals(0.85, $result['risk_score']);
        $this->assertEquals('block', $result['decision']);
    }
}

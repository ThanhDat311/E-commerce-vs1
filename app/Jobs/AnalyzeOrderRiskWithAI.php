<?php

namespace App\Jobs;

use App\Models\AiFeatureStore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyzeOrderRiskWithAI implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $backoff = [10, 30, 60];

    protected $featureLogId;

    /**
     * Create a new job instance.
     */
    public function __construct($featureLogId)
    {
        $this->featureLogId = $featureLogId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $log = AiFeatureStore::with('order.user', 'order.items.product')->find($this->featureLogId);

        if (! $log || ! $log->order) {
            return;
        }

        $order = $log->order;
        $reasons = is_array($log->reasons) ? implode(', ', $log->reasons) : $log->reasons;
        $riskScore = $log->risk_score * 100;

        // Bảo mật: Ẩn danh hóa dữ liệu. Không gửi IP Raw.
        $ipHash = substr(hash('sha256', $log->ip_address), 0, 8);

        // Bảo mật: Chuyển Model Prompt sang JSON chặt chẽ để AI tuân thủ cấu trúc & giảm thiểu prompt injection
        $systemPrompt = 'You are an E-commerce Fraud Analyst API. You must evaluate risk parameters and ALWAYS respond in strictly valid JSON format matching this schema: {"insight": "A brief 2 sentence analysis", "recommendation": "APPROVE or REVIEW"}.';

        $userPrompt = json_encode([
            'total_value' => $order->total,
            'account_status' => $order->user_id ? 'Registered' : 'Guest Checkout',
            'ip_fingerprint' => $ipHash,
            'algorithmic_score' => $riskScore,
            'system_flags' => $reasons,
        ]);

        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            // Fallback for demo purposes if no key is set
            $log->ai_insight = "System Simulation Mode: The algorithmic risk score is {$riskScore}. Based on the system flags ({$reasons}), no manual intervention is required at this time. To activate real Generative AI analysis, configure OPENAI_API_KEY in the `.env` file.";
            $log->save();

            return;
        }

        try {
            // Gọi API
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->retry(3, 100)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo', // Update Model hiệu suất cao / chi phí thấp
                    'response_format' => ['type' => 'json_object'], // Ép trả về JSON
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'max_tokens' => 150,
                    'temperature' => 0.2, // Hạ nhiệt độ để tăng tính ổn định & phán đoán logic
                ]);

            // Quăng Exception nếu response có HTTP Status báo lỗi (429, 50x,...)
            $response->throw();

            $data = $response->json();
            $aiResponse = json_decode($data['choices'][0]['message']['content'] ?? '{}', true);
            $usage = $data['usage'] ?? [];

            Log::info('OpenAI Risk Analysis Success', [
                'order_id' => $order->id,
                'tokens_used' => $usage,
                'model' => $data['model'] ?? 'unknown',
            ]);

            if (! empty($aiResponse['insight'])) {
                $recommendationText = isset($aiResponse['recommendation']) ? "[{$aiResponse['recommendation']}] " : '';
                $log->ai_insight = $recommendationText.trim($aiResponse['insight']);
                $log->save();
            }
        } catch (\Exception $e) {
            Log::error('Exception in AI Risk Analysis Job: '.$e->getMessage());

            // Cập nhật giá trị insight mặc định nếu đã hết số lần Retry
            if ($this->attempts() >= $this->tries) {
                $log->ai_insight = 'Error connecting to AI service. Fallback analysis: Proceed with caution.';
                $log->save();
            }

            // Ném lỗi để Laravel Queue Worker tiến hành Retry lại
            throw $e;
        }
    }
}

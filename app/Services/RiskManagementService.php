<?php

namespace App\Services;

use App\Models\AiFeatureStore;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Risk Management Service
 *
 * Wrapper service để tích hợp AI microservice VÀ AIDecisionEngine với Feature Store logging.
 * Tuân thủ Fail-Open principle:
 *   - Nếu AI microservice khả dụng → dùng ML model (chính xác hơn)
 *   - Nếu offline → fallback về AIDecisionEngine (rule-based)
 *   - Nếu cả hai đều lỗi → vẫn cho phép đặt hàng
 *
 * @see doc/AI_RULES.md
 * @see doc/ELECTRO-SECURITY.md
 */
class RiskManagementService
{
    protected AIDecisionEngine $aiEngine;
    protected AiMicroserviceClient $aiClient;

    public function __construct(AIDecisionEngine $aiEngine, AiMicroserviceClient $aiClient)
    {
        $this->aiEngine = $aiEngine;
        $this->aiClient = $aiClient;
    }

    /**
     * Phân tích rủi ro và GHI LOG vào Feature Store.
     *
     * @param array    $orderData ['total' => float, 'quantity' => int, 'id' => int|null, 'payment_method' => string|null]
     * @param int|null $userId
     * @return array ['allowed' => bool, 'score' => int, 'reason' => string, 'log_id' => int|null, 'decision' => string, 'ai_source' => string]
     */
    public function assessOrderRisk(array $orderData, ?int $userId): array
    {
        try {
            // --- 1. Try AI microservice first ---
            $aiResult = $this->callAiMicroservice($orderData, $userId);

            if ($aiResult !== null) {
                return $this->buildResultFromAi($aiResult, $orderData);
            }

            // --- 2. Fallback: Rule-based AIDecisionEngine ---
            Log::warning('[RiskManagement] AI microservice unavailable, falling back to rule-based engine.');
            return $this->callRuleBasedEngine($orderData, $userId);
        } catch (\Exception $e) {
            // Fail-Open Principle: If both AI and rules fail, allow the order
            Log::error('Risk Management Service Error: ' . $e->getMessage());

            return [
                'allowed'    => true,
                'score'      => 0,
                'reason'     => 'Risk assessment unavailable',
                'log_id'     => null,
                'decision'   => 'APPROVE',
                'ai_source'  => 'error_fallback',
            ];
        }
    }

    /**
     * Gọi AI microservice để đánh giá fraud.
     */
    private function callAiMicroservice(array $orderData, ?int $userId): ?array
    {
        if ($userId === null) {
            return null; // Guest checkout: microservice cần user_id
        }

        $orderId       = $orderData['id'] ?? 0;
        $totalAmount   = (float) ($orderData['total'] ?? 0);
        $paymentMethod = $orderData['payment_method'] ?? 'card';

        // Lấy tuổi user nếu có
        $customerAge = null;
        try {
            $user        = \App\Models\User::find($userId);
            $customerAge = $user?->created_at
                ? (int) $user->created_at->diffInYears(now())
                : null;
        } catch (\Exception $e) {
            // Ignore
        }

        return $this->aiClient->predictTransactionFraud(
            userId: $userId,
            orderId: $orderId,
            totalAmount: $totalAmount,
            paymentMethod: strtolower($paymentMethod),
            productCategory: null,
            customerAge: $customerAge,
        );
    }

    /**
     * Build result array từ AI microservice response và log vào Feature Store.
     */
    private function buildResultFromAi(array $aiResult, array $orderData): array
    {
        $aiDecision = $aiResult['decision']    ?? 'allow';
        $riskScore  = (float) ($aiResult['risk_score'] ?? 0.0);
        $reasons    = $aiResult['reasons']     ?? [];

        // Map AI decision → business decision
        $isBlocked = ($aiDecision === 'block');
        $label     = match ($aiDecision) {
            'block'  => 'block',
            'review' => 'flag',
            default  => 'allow',
        };

        // Laravel score convention: 0-100 int
        $scoreInt = (int) round($riskScore * 100);

        // Log to Feature Store
        $featureLogId = null;
        try {
            $featureLog = AiFeatureStore::create([
                'order_id'    => $orderData['id'] ?? null,
                'total_amount' => $orderData['total'] ?? 0,
                'ip_address'  => request()->ip(),
                'risk_score'  => $riskScore, // already 0.0–1.0 from microservice
                'reasons'     => $reasons,
                'label'       => $label,
            ]);
            $featureLogId = $featureLog->id;
        } catch (\Exception $e) {
            Log::error('[RiskManagement] Failed to store AI features: ' . $e->getMessage());
        }

        if ($isBlocked) {
            Log::warning("[RiskManagement] Fraud Detected (AI Microservice): Order blocked. Score: {$riskScore}. Decision: {$aiDecision}");
        } elseif ($aiDecision === 'review') {
            Log::info("[RiskManagement] Order flagged for review (AI Microservice). Score: {$riskScore}");
        }

        return [
            'allowed'   => !$isBlocked,
            'score'     => $scoreInt,
            'reason'    => implode(', ', $reasons),
            'log_id'    => $featureLogId,
            'decision'  => strtoupper($label === 'flag' ? 'FLAG' : ($isBlocked ? 'BLOCK' : 'APPROVE')),
            'ai_source' => 'microservice',
        ];
    }

    /**
     * Rule-based fallback: Legacy AIDecisionEngine.
     */
    private function callRuleBasedEngine(array $orderData, ?int $userId): array
    {
        // Prepare user data
        $userData = ['id' => $userId];
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $userData['created_at'] = $user->created_at;
            }
        }

        // Prepare context data
        $contextData = [
            'hour' => now()->hour,
            'ip'   => request()->ip(),
        ];

        // Call rule-based engine
        $fraudResult = $this->aiEngine->assessFraudRisk($orderData, $userData, $contextData);

        $riskScore = $fraudResult['score'];    // 0-100
        $decision  = $fraudResult['decision']; // APPROVE, FLAG, BLOCK
        $reasons   = $fraudResult['reasons'] ?? [];

        $isBlocked = ($decision === 'BLOCK');
        $label     = $isBlocked ? 'block' : ($decision === 'FLAG' ? 'flag' : 'allow');

        // Store to Feature Store
        $featureLogId = null;
        try {
            $featureLog = AiFeatureStore::create([
                'total_amount' => $orderData['total'] ?? 0,
                'ip_address'   => $contextData['ip'],
                'risk_score'   => $riskScore / 100.0,
                'reasons'      => $reasons,
                'label'        => $label,
            ]);
            $featureLogId = $featureLog->id;
        } catch (\Exception $e) {
            Log::error('[RiskManagement] Failed to store rule-based features: ' . $e->getMessage());
        }

        if ($isBlocked) {
            Log::warning("[RiskManagement] Fraud Detected (Rule-based): Score: {$riskScore}/100. Decision: {$decision}");
        } elseif ($decision === 'FLAG') {
            Log::info("[RiskManagement] Order flagged for review (Rule-based). Score: {$riskScore}/100");
        }

        return [
            'allowed'   => !$isBlocked,
            'score'     => $riskScore,
            'reason'    => implode(', ', $reasons),
            'log_id'    => $featureLogId,
            'decision'  => $decision,
            'ai_source' => 'rule_based_fallback',
        ];
    }
}

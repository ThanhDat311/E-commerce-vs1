<?php

namespace App\Services;

use App\Models\AiFeatureStore;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Risk Management Service
 * 
 * Wrapper service để tích hợp AIDecisionEngine với Feature Store logging
 * Tuân thủ Fail-Open principle: Nếu AI lỗi, vẫn cho phép đặt hàng
 * 
 * @see doc/AI_RULES.md
 * @see doc/ELECTRO-SECURITY.md
 */
class RiskManagementService
{
    protected AIDecisionEngine $aiEngine;

    public function __construct(AIDecisionEngine $aiEngine)
    {
        $this->aiEngine = $aiEngine;
    }

    /**
     * Phân tích rủi ro và GHI LOG vào Feature Store
     * 
     * Sử dụng AIDecisionEngine với score 0-100
     * 
     * @param array $orderData ['total' => float, 'quantity' => int]
     * @param int|null $userId
     * @return array ['allowed' => bool, 'score' => int, 'reason' => string, 'log_id' => int|null, 'decision' => string]
     */
    public function assessOrderRisk(array $orderData, ?int $userId): array
    {
        try {
            // 1. Prepare user data
            $userData = ['id' => $userId];
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $userData['created_at'] = $user->created_at;
                }
            }

            // 2. Prepare context data
            $contextData = [
                'hour' => now()->hour,
                'ip' => request()->ip(),
            ];

            // 3. Call AI Decision Engine (Stateless)
            $fraudResult = $this->aiEngine->assessFraudRisk($orderData, $userData, $contextData);

            $riskScore = $fraudResult['score']; // 0-100
            $decision = $fraudResult['decision']; // APPROVE, FLAG, BLOCK
            $reasons = $fraudResult['reasons'] ?? [];

            // 4. Convert decision to boolean (BLOCK = not allowed)
            $isBlocked = ($decision === 'BLOCK');
            $label = $isBlocked ? 'block' : ($decision === 'FLAG' ? 'flag' : 'allow');

            // 5. Store to Feature Store (Fail-Open: Don't block if logging fails)
            $featureLogId = null;
            try {
                $featureLog = AiFeatureStore::create([
                    'total_amount' => $orderData['total'] ?? 0,
                    'ip_address' => $contextData['ip'],
                    'risk_score' => $riskScore / 100.0, // Store as 0.0-1.0 in DB (for compatibility)
                    'reasons' => $reasons,
                    'label' => $label,
                    // order_id will be updated after order creation
                ]);
                $featureLogId = $featureLog->id;
            } catch (\Exception $e) {
                // Fail-Open: Log error but don't block order
                Log::error("Failed to store AI features: " . $e->getMessage());
            }

            if ($isBlocked) {
                Log::warning("Fraud Detected: Order blocked. Score: {$riskScore}/100. Decision: {$decision}");
            } elseif ($decision === 'FLAG') {
                Log::info("Order flagged for review. Score: {$riskScore}/100");
            }

            return [
                'allowed' => !$isBlocked,
                'score' => $riskScore, // 0-100
                'reason' => implode(', ', $reasons),
                'log_id' => $featureLogId,
                'decision' => $decision, // APPROVE, FLAG, BLOCK
            ];

        } catch (\Exception $e) {
            // Fail-Open Principle: If AI fails, allow the order
            Log::error("Risk Management Service Error: " . $e->getMessage());
            
            return [
                'allowed' => true, // Fail-open: Allow order if AI fails
                'score' => 0,
                'reason' => 'Risk assessment unavailable',
                'log_id' => null,
                'decision' => 'APPROVE',
            ];
        }
    }
}
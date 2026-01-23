<?php

namespace App\Services;

use App\Models\RiskRule;

/**
 * AI Decision Engine - Stateless Service
 * 
 * Quy tắc Vàng: Stateless - Không lưu trạng thái, không query DB trực tiếp
 * Chỉ nhận dữ liệu -> Xử lý -> Trả kết quả
 * 
 * Rules are loaded from database and cached
 * 
 * @see doc/AI_DECISION_ENGINE.md
 * @see doc/ELECTRO-AI-ENGINE.md
 */
class AIDecisionEngine
{
    /**
     * Get Risk Rules from Cache
     * 
     * Score: 0-100 (0 = No risk, 100 = Maximum risk)
     * 
     * @return array
     */
    private function getRiskRules(): array
    {
        return RiskRule::getRules();
    }

    /**
     * Decision Thresholds
     */
    private const THRESHOLD_BLOCK = 80;  // Score >= 80: BLOCK
    private const THRESHOLD_FLAG = 50;   // Score 50-79: FLAG
    // Score < 50: APPROVE

    /**
     * 1. Fraud Risk Assessment
     * 
     * Đánh giá rủi ro gian lận dựa trên đơn hàng, user và context
     * 
     * @param array $orderData ['total' => float, 'quantity' => int]
     * @param array $userData ['id' => int|null, 'created_at' => Carbon|null]
     * @param array $contextData ['hour' => int, 'ip' => string]
     * @return array ['decision' => string, 'score' => int, 'reasons' => array]
     */
    public function assessFraudRisk(array $orderData, array $userData, array $contextData = []): array
    {
        $score = 0;
        $reasons = [];
        $rules = $this->getRiskRules();

        // 1. Guest Checkout
        if (empty($userData['id'])) {
            $score += $rules['guest_checkout'] ?? 0;
            $reasons[] = 'Guest checkout increases risk';
        }

        // 2. New User (< 24 hours)
        if (!empty($userData['created_at'])) {
            $userAge = now()->diffInHours($userData['created_at']);
            if ($userAge < 24) {
                $score += $rules['new_user_24h'] ?? 0;
                $reasons[] = 'New user account (< 24h)';
            }
        }

        // 3. High Value Orders
        $totalAmount = $orderData['total'] ?? 0;
        if ($totalAmount > 5000) {
            $score += $rules['high_value_5000'] ?? 0;
            $reasons[] = "High value order (\${$totalAmount})";
        } elseif ($totalAmount > 1000) {
            $score += $rules['high_value_1000'] ?? 0;
            $reasons[] = "Medium value order (\${$totalAmount})";
        }

        // 4. Suspicious Timing (12:00 AM - 4:00 AM)
        $hour = $contextData['hour'] ?? now()->hour;
        if ($hour >= 0 && $hour <= 4) {
            $score += $rules['suspicious_time'] ?? 0;
            $reasons[] = 'Order placed during suspicious hours (12AM-4AM)';
        }

        // 5. Large Quantities
        $quantity = $orderData['quantity'] ?? 1;
        if ($quantity > 10) {
            $score += $rules['large_quantity'] ?? 0;
            $reasons[] = "Large quantity ordered ({$quantity} items)";
        }

        // 6. Round Number Amounts (suspicious pattern)
        if ($totalAmount > 0 && $totalAmount % 100 == 0) {
            $score += $rules['round_amount'] ?? 0;
            $reasons[] = 'Round number amount (suspicious pattern)';
        }

        // Determine decision
        $decision = $this->getDecisionFromScore($score);

        return [
            'decision' => $decision,
            'score' => min(100, $score), // Cap at 100
            'reasons' => $reasons,
        ];
    }

    /**
     * 2. Inventory Risk Assessment
     * 
     * Đánh giá rủi ro tồn kho dựa trên stock level và demand patterns
     * 
     * @param array $productData ['stock_quantity' => int, 'product_id' => int]
     * @param array $demandData ['seasonal_spike' => bool, 'supplier_delay' => bool, 'recent_sales' => int]
     * @return array ['decision' => string, 'score' => int, 'reasons' => array]
     */
    public function assessInventoryRisk(array $productData, array $demandData = []): array
    {
        $score = 0;
        $reasons = [];
        $stockQuantity = $productData['stock_quantity'] ?? 0;

        // 1. Critical Stock Level
        if ($stockQuantity <= 0) {
            $score = 100;
            $reasons[] = 'Out of stock';
            return [
                'decision' => 'CRITICAL_RESTOCK',
                'score' => $score,
                'reasons' => $reasons,
            ];
        } elseif ($stockQuantity <= 3) {
            $score += 60;
            $reasons[] = 'Critical stock level (≤3 items)';
        } elseif ($stockQuantity <= 10) {
            $score += 30;
            $reasons[] = 'Low stock level (≤10 items)';
        }

        // 2. Seasonal Demand Spike
        if (!empty($demandData['seasonal_spike'])) {
            $score += 25;
            $reasons[] = 'Seasonal demand spike detected';
        }

        // 3. Supplier Delay Risk
        if (!empty($demandData['supplier_delay'])) {
            $score += 20;
            $reasons[] = 'Supplier delay risk';
        }

        // 4. High Recent Sales (demand pattern)
        $recentSales = $demandData['recent_sales'] ?? 0;
        if ($recentSales > 50 && $stockQuantity < 20) {
            $score += 15;
            $reasons[] = 'High recent sales with low stock';
        }

        // Determine decision
        $decision = match (true) {
            $score >= 80 => 'CRITICAL_RESTOCK',
            $score >= 50 => 'URGENT_RESTOCK',
            $score >= 30 => 'MONITOR_STOCK',
            default => 'STOCK_OK',
        };

        return [
            'decision' => $decision,
            'score' => min(100, $score),
            'reasons' => $reasons,
        ];
    }

    /**
     * 3. Dynamic Pricing Suggestions
     * 
     * Đề xuất điều chỉnh giá dựa trên điều kiện thị trường
     * 
     * @param array $productData ['price' => float, 'cost_price' => float, 'stock_quantity' => int]
     * @param array $marketData ['high_demand' => bool, 'competitor_lower_price' => bool, 'competitor_price' => float]
     * @return array ['decision' => float, 'score' => int, 'reasons' => array]
     */
    public function suggestDynamicPrice(array $productData, array $marketData = []): array
    {
        $currentPrice = $productData['price'] ?? 0;
        $costPrice = $productData['cost_price'] ?? 0;
        $stockQuantity = $productData['stock_quantity'] ?? 0;
        $suggestedPrice = $currentPrice;
        $score = 0;
        $reasons = [];

        // 1. High Demand - Increase Price
        if (!empty($marketData['high_demand'])) {
            $increase = $currentPrice * 0.1; // 10% increase
            $suggestedPrice += $increase;
            $score += 40;
            $reasons[] = 'High demand justifies price increase';
        }

        // 2. Low Stock - Increase Price
        if ($stockQuantity <= 5 && $stockQuantity > 0) {
            $increase = $currentPrice * 0.05; // 5% increase
            $suggestedPrice += $increase;
            $score += 20;
            $reasons[] = 'Low stock level suggests price increase';
        }

        // 3. Competitor Lower Price - Decrease Price
        if (!empty($marketData['competitor_lower_price']) && !empty($marketData['competitor_price'])) {
            $competitorPrice = $marketData['competitor_price'];
            if ($competitorPrice < $currentPrice) {
                $decrease = ($currentPrice - $competitorPrice) * 0.5; // Match 50% of difference
                $suggestedPrice -= $decrease;
                $score += 30;
                $reasons[] = 'Competitor pricing pressure - suggest price decrease';
            }
        }

        // 4. Ensure minimum margin (20% above cost)
        $minPrice = $costPrice * 1.2;
        if ($suggestedPrice < $minPrice) {
            $suggestedPrice = $minPrice;
            $reasons[] = 'Adjusted to maintain minimum margin (20%)';
        }

        // Round to 2 decimal places
        $suggestedPrice = round($suggestedPrice, 2);

        return [
            'decision' => $suggestedPrice,
            'score' => min(100, $score),
            'reasons' => $reasons,
        ];
    }

    /**
     * 4. Order Decision Making
     * 
     * Quyết định cuối cùng dựa trên tổng hợp các risk scores
     * 
     * @param array $orderData
     * @param array $userData
     * @param array $riskResults Array of risk assessment results
     * @return array ['decision' => string, 'score' => int, 'reasons' => array]
     */
    public function decideOrder(array $orderData, array $userData, array $riskResults = []): array
    {
        // Aggregate scores from all risk assessments
        $totalScore = 0;
        $allReasons = [];

        foreach ($riskResults as $result) {
            if (isset($result['score'])) {
                $totalScore += $result['score'];
            }
            if (isset($result['reasons']) && is_array($result['reasons'])) {
                $allReasons = array_merge($allReasons, $result['reasons']);
            }
        }

        // Average the scores (or use max, depending on business logic)
        $avgScore = count($riskResults) > 0 ? ($totalScore / count($riskResults)) : 0;
        $finalScore = min(100, (int)round($avgScore));

        // Final decision
        $decision = $this->getDecisionFromScore($finalScore);

        return [
            'decision' => $decision,
            'score' => $finalScore,
            'reasons' => array_unique($allReasons),
        ];
    }

    /**
     * Get Rules Configuration
     * 
     * @param string|null $category 'risk' | null for all
     * @return array
     */
    public function getRules(?string $category = null): array
    {
        if ($category === 'risk') {
            return $this->getRiskRules();
        }

        return [
            'risk' => $this->getRiskRules(),
            'thresholds' => [
                'block' => self::THRESHOLD_BLOCK,
                'flag' => self::THRESHOLD_FLAG,
            ],
        ];
    }

    /**
     * Update Rule (for dynamic configuration)
     * 
     * Note: In production, this should persist to config/database
     * 
     * @param string $category
     * @param string $ruleName
     * @param int $value
     * @return bool
     */
    public function updateRule(string $category, string $ruleName, int $value): bool
    {
        // For now, rules are constants. In production, load from config/database
        // This is a placeholder for future dynamic rule configuration
        return false;
    }

    /**
     * Convert score to decision
     * 
     * @param int $score 0-100
     * @return string 'APPROVE' | 'FLAG' | 'BLOCK' | 'REQUIRE_MFA'
     */
    private function getDecisionFromScore(int $score): string
    {
        if ($score >= self::THRESHOLD_BLOCK) {
            return 'BLOCK';
        } elseif ($score >= self::THRESHOLD_FLAG) {
            return 'FLAG';
        } else {
            return 'APPROVE';
        }
    }
}

# AI Decision Engine Documentation

## Overview

The AI Decision Engine is a rule-based + score-based (0-100) decision system designed for e-commerce applications. It's built to be production-ready, extensible, and ML-ready while currently using deterministic rules.

## Architecture

- **Stateless**: Pure functions, no side effects
- **Security-focused**: Input validation, no external dependencies
- **Extensible**: Configuration-driven rules
- **ML-ready**: Structured for future machine learning integration

## Core Components

### 1. Fraud Risk Assessment

Evaluates order and user risk factors.

**Input:**

```php
$orderData = ['total' => 5000, 'quantity' => 1];
$userData = ['id' => null]; // Guest user
$contextData = ['hour' => 3]; // 3 AM
```

**Output:**

```php
[
    'decision' => 'HIGH_RISK',
    'score' => 75,
    'reasons' => [
        'Guest checkout increases risk',
        'High value order ($5000)',
        'Order placed during suspicious hours'
    ]
]
```

**Risk Factors:**

- Guest checkout (+20)
- New user account (+15)
- High value orders (+25)
- Suspicious timing (+30)
- Large quantities (+20)
- Round number amounts (+10)

### 2. Inventory Risk Assessment

Evaluates stock levels and demand patterns.

**Input:**

```php
$productData = ['stock_quantity' => 3];
$demandData = ['seasonal_spike' => true, 'supplier_delay' => true];
```

**Output:**

```php
[
    'decision' => 'CRITICAL_RESTOCK',
    'score' => 95,
    'reasons' => [
        'Critical stock level',
        'Seasonal demand spike detected',
        'Supplier delay risk'
    ]
]
```

### 3. Dynamic Pricing Suggestions

Recommends price adjustments based on market conditions.

**Input:**

```php
$productData = ['price' => 100, 'cost_price' => 50, 'stock_quantity' => 5];
$marketData = ['high_demand' => true, 'competitor_lower_price' => true];
```

**Output:**

```php
[
    'decision' => 110.00, // Suggested price
    'score' => 80,
    'reasons' => [
        'High demand justifies price increase',
        'Competitor pricing pressure'
    ]
]
```

### 4. Order Decision Making

Final approval decision based on aggregated risk scores.

**Decisions:**

- `APPROVE`: Low risk, proceed normally
- `FLAG`: Medium risk, requires review
- `REQUIRE_MFA`: High risk, needs additional verification

## Usage Examples

```php
use App\Services\AIDecisionEngine;

$engine = new AIDecisionEngine();

// Fraud assessment
$fraudResult = $engine->assessFraudRisk($orderData, $userData, $contextData);

// Inventory check
$inventoryResult = $engine->assessInventoryRisk($productData, $demandData);

// Pricing suggestion
$pricingResult = $engine->suggestDynamicPrice($productData, $marketData);

// Final decision
$finalDecision = $engine->decideOrder($orderData, $userData, [
    $fraudResult,
    $inventoryResult
]);
```

## Rule Configuration

Rules are defined as constants and can be accessed/modified:

```php
// Get all rules
$rules = $engine->getRules();

// Get specific category
$riskRules = $engine->getRules('risk');

// Update a rule (for dynamic configuration)
$engine->updateRule('risk', 'guest_checkout', 25); // Increase guest penalty
```

## Security Features

1. **Input Validation**: All inputs are validated and sanitized
2. **No External Dependencies**: Pure PHP, no network calls
3. **Stateless Operations**: No persistent state or side effects
4. **Configurable Rules**: Rules can be adjusted without code changes
5. **Audit Trail**: All decisions include detailed reasoning

## ML Integration Ready

The engine is structured for future ML integration:

- **Feature Extraction**: Clean input processing
- **Score Normalization**: 0-100 scale for easy ML mapping
- **Reason Logging**: Detailed explanations for training data
- **Rule-based Fallback**: Deterministic rules as safety net
- **Configuration Layer**: Easy switching between rule-based and ML-based decisions

## Performance

- **Fast Execution**: Pure functions, no I/O operations
- **Memory Efficient**: No persistent objects
- **Scalable**: Stateless design supports horizontal scaling
- **Configurable**: Rules can be cached for performance

## Testing

Comprehensive test suite covers:

- Risk assessment accuracy
- Inventory risk evaluation
- Pricing suggestions
- Order decision logic
- Edge cases and boundary conditions

Run tests: `php artisan test tests/Unit/AIDecisionEngineTest.php`

## Future Enhancements

1. **ML Model Integration**: Replace rules with trained models
2. **Real-time Learning**: Update rules based on outcomes
3. **A/B Testing**: Compare rule-based vs ML performance
4. **Advanced Features**: Behavioral analysis, network detection
5. **API Integration**: External risk intelligence services

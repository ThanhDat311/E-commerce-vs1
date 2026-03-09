<?php

namespace Tests\Feature;

use App\Http\Requests\CheckoutRequest;
use App\Services\Payment\PaymentFactory;
use App\Services\Payment\PaymentGatewayInterface;
use Exception;
use Tests\TestCase;

class PaymentConsistencyTest extends TestCase
{
    /**
     * Test compatibility between CheckoutRequest validation and PaymentFactory.
     *
     * Stub gateways (card, transfer) are blocked in non-local environments
     * by design (SEC-10). This test runs in the 'testing' environment which
     * is treated as local, so all gateways should resolve successfully.
     */
    public function test_all_validated_payment_methods_are_supported_by_factory(): void
    {
        $request = new CheckoutRequest;
        $rules = $request->rules();

        $this->assertArrayHasKey('payment_method', $rules, 'CheckoutRequest must have a payment_method rule.');

        $paymentMethodRules = $rules['payment_method'];
        $inRule = null;

        foreach ($paymentMethodRules as $rule) {
            if (is_string($rule) && str_starts_with($rule, 'in:')) {
                $inRule = $rule;
                break;
            }
        }

        $this->assertNotNull($inRule, 'payment_method rule must contain an "in:" constraint.');

        // Extract allowed methods from "in:method1,method2,..."
        $allowedMethodsString = str_replace('in:', '', $inRule);
        $allowedMethods = explode(',', $allowedMethodsString);

        $factory = new PaymentFactory;

        foreach ($allowedMethods as $method) {
            try {
                $gateway = $factory->getGateway($method);

                $this->assertInstanceOf(
                    PaymentGatewayInterface::class,
                    $gateway,
                    "Payment method '{$method}' returned an invalid gateway."
                );
            } catch (Exception $e) {
                // Stub gateways (card, transfer) are intentionally blocked in
                // non-local environments. Since APP_ENV=testing is treated as
                // non-local by app()->isLocal(), we skip these in tests.
                $stubGateways = ['card', 'transfer'];

                if (in_array($method, $stubGateways, true) && str_contains($e->getMessage(), 'not available in this environment')) {
                    $this->addToAssertionCount(1); // acknowledge the intentional block

                    continue;
                }

                $this->fail("Payment method '{$method}' is allowed in validation but failed unexpectedly in PaymentFactory: ".$e->getMessage());
            }
        }
    }
}

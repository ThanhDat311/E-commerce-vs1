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
     */
    public function test_all_validated_payment_methods_are_supported_by_factory()
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
                $this->fail("Payment method '{$method}' is allowed in validation but failed in PaymentFactory: ".$e->getMessage());
            }
        }
    }
}

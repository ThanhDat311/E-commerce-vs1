<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $paymentMethods = $user->paymentMethods()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return view('profile.payment-methods.index', compact('user', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cardholder_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'min:13', 'max:19'],
            'expiry_month' => ['required', 'integer', 'between:1,12'],
            'expiry_year' => ['required', 'integer', 'min:'.date('Y')],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $cardNumber = preg_replace('/\s+/', '', $validated['card_number']);
        $brand = $this->detectCardBrand($cardNumber);
        $lastFour = substr($cardNumber, -4);

        $userId = Auth::id();

        if (! empty($validated['is_default'])) {
            PaymentMethod::where('user_id', $userId)->update(['is_default' => false]);
        }

        // If first card, make it default
        $isFirst = PaymentMethod::where('user_id', $userId)->count() === 0;

        PaymentMethod::create([
            'user_id' => $userId,
            'cardholder_name' => $validated['cardholder_name'],
            'card_brand' => $brand,
            'last_four' => $lastFour,
            'expiry_month' => $validated['expiry_month'],
            'expiry_year' => $validated['expiry_year'],
            'is_default' => $isFirst || ! empty($validated['is_default']),
        ]);

        return redirect()->back()->with('success', 'Payment method added successfully.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'cardholder_name' => ['required', 'string', 'max:255'],
            'expiry_month' => ['required', 'integer', 'between:1,12'],
            'expiry_year' => ['required', 'integer', 'min:'.date('Y')],
        ]);

        $paymentMethod->update($validated);

        return redirect()->back()->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403);
        }

        if ($paymentMethod->is_default && PaymentMethod::where('user_id', Auth::id())->count() > 1) {
            return redirect()->back()->with('error', 'Cannot delete default card. Set another card as default first.');
        }

        $paymentMethod->delete();

        return redirect()->back()->with('success', 'Payment method removed.');
    }

    public function setDefault(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403);
        }

        PaymentMethod::where('user_id', Auth::id())->update(['is_default' => false]);
        $paymentMethod->update(['is_default' => true]);

        return redirect()->back()->with('success', 'Default payment method updated.');
    }

    private function detectCardBrand(string $number): string
    {
        if (preg_match('/^4/', $number)) {
            return 'visa';
        }
        if (preg_match('/^5[1-5]/', $number)) {
            return 'mastercard';
        }
        if (preg_match('/^3[47]/', $number)) {
            return 'amex';
        }
        if (preg_match('/^6(?:011|5)/', $number)) {
            return 'discover';
        }

        return 'visa';
    }
}

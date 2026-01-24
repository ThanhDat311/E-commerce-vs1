<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

class VnpayGateway implements PaymentGatewayInterface
{
    public function process(Order $order): array
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = config('services.vnpay.url');
        $vnp_Returnurl = route('payment.vnpay.callback');
        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');

        $vnp_TxnRef = $order->id;

        // [FIX 1] Bỏ ký tự đặc biệt # để tránh lỗi encode
        $vnp_OrderInfo = "Thanh toan don hang " . $order->id;

        $vnp_OrderType = "billpayment";
        $vnp_Amount = intval($order->total);
        $vnp_Locale = 'vn';

        // [FIX 2] QUAN TRỌNG: Ép cứng IPv4 cho môi trường Local
        // VNPay Sandbox thường từ chối IP ::1 của Laragon
        $vnp_IpAddr = '103.72.97.188';
        $startTime = date("YmdHis");

        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $expire
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        \Log::info('VNPay Payment Request', [
            'order_id' => $order->id,
            'amount' => $vnp_Amount,
            'url' => $vnp_Url,
            'input_data' => $inputData,
            'hash_data' => $hashdata,
            'secure_hash' => $vnpSecureHash ?? null
        ]);
        return [
            'success' => true,
            'is_redirect' => true,
            'redirect_url' => $vnp_Url
        ];
    }

    public function verify(Request $request): array
    {
        // Validate required parameters
        $requiredParams = ['vnp_TxnRef', 'vnp_Amount', 'vnp_ResponseCode'];
        foreach ($requiredParams as $param) {
            if (!$request->has($param) || $request->input($param) === null || $request->input($param) === '') {
                return [
                    'success' => false,
                    'message' => "Missing required parameter: {$param}"
                ];
            }
        }

        // Enforce strict types
        $txnRef = (string) $request->input('vnp_TxnRef');
        $amount = (int) $request->input('vnp_Amount');
        $responseCode = (string) $request->input('vnp_ResponseCode');

        if (!is_numeric($request->input('vnp_Amount'))) {
            return [
                'success' => false,
                'message' => 'Invalid amount format'
            ];
        }

        $inputData = $request->toArray();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

        if (isset($inputData['vnp_SecureHash'])) {
            unset($inputData['vnp_SecureHash']);
        }
        if (isset($inputData['vnp_SecureHashType'])) {
            unset($inputData['vnp_SecureHashType']);
        }

        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            return [
                'success' => false,
                'message' => 'Invalid signature'
            ];
        }

        if ($responseCode === '00') {
            return [
                'success' => true,
                'order_id' => $txnRef,
                'amount' => $amount / 100,
                'transaction_no' => $request->input('vnp_TransactionNo', ''),
                'message' => 'Transaction successful'
            ];
        } else {
            return [
                'success' => false,
                'order_id' => $txnRef,
                'amount' => $amount / 100,
                'response_code' => $responseCode,
                'message' => 'Transaction failed (Code: ' . $responseCode . ')'
            ];
        }
    }
}

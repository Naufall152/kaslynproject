<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends Controller
{
    private function midtransConfig(): void
    {
        Config::$serverKey = (string) config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production', false);
        Config::$isSanitized = (bool) config('midtrans.sanitize', true);
        Config::$is3ds = (bool) config('midtrans.is_3ds', true);

        // Optional (kadang membantu kalau error karena TLS/cURL environment)
        // Config::$curlOptions = [
        //     CURLOPT_SSL_VERIFYPEER => true,
        // ];
    }

    /**
     * Buat tagihan + Snap Token
     */
    public function pay(Request $request)
    {
        $data = $request->validate([
            'plan' => 'required|in:pro',
        ]);

        $user = $request->user();
        $plan = $data['plan'];
        $amount = $plan === 'pro' ? 50000 : 25000;

        $this->midtransConfig();

        // order id harus unik
        $orderId = 'KASLYN-' . $user->id . '-' . now()->format('YmdHis') . '-' . random_int(100, 999);

        // simpan payment dulu (pending)
        $payment = Payment::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'plan' => $plan,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'SUB-' . strtoupper($plan),
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Langganan Kaslyn - ' . strtoupper($plan),
                ]
            ],
            // QRIS saja (kalau mau lebih aman tampil channel, bisa aktifkan)
            // 'enabled_payments' => ['qris'],

            // opsional: set expiry agar tidak menggantung
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minutes',
                'duration' => 60,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $payment->update([
                'snap_token' => $snapToken,
            ]);

            return response()->json([
                'ok' => true,
                'order_id' => $orderId,
                'amount' => $amount,
                'snap_token' => $snapToken,
            ]);
        } catch (\Throwable $e) {
            // kalau gagal token, tandai failed biar tidak numpuk pending
            $payment->update([
                'status' => 'failed',
                'raw_response' => [
                    'message' => $e->getMessage(),
                ],
            ]);

            Log::error('Midtrans pay() failed', [
                'order_id' => $orderId,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Gagal membuat tagihan Midtrans. Cek konfigurasi server key / channel / sandbox.',
            ], 500);
        }
    }

    /**
     * Cek status transaksi ke Midtrans
     */
    public function checkStatus(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|string',
        ]);

        $this->midtransConfig();

        $payment = Payment::where('order_id', $data['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        Log::info('MIDTRANS STATUS RESULT', [
            'order_id' => $payment->order_id,
            'transaction_status' => $statusArr['transaction_status'] ?? null,
            'payment_type' => $statusArr['payment_type'] ?? null,
            'status_code' => $statusArr['status_code'] ?? null,
            'fraud_status' => $statusArr['fraud_status'] ?? null,
        ]);

        // Idempotent: kalau sudah sukses, jangan aktivasi lagi
        if ($payment->status === 'success') {
            return response()->json([
                'paid' => true,
                'message' => "Pembayaran sudah berhasil (Rp " . number_format($payment->amount, 0, ',', '.') . ")",
                'midtrans_status' => 'settlement',
            ]);

        }

        try {
            $statusObj = \Midtrans\Transaction::status($payment->order_id);

            Log::info('MIDTRANS STATUS RAW OBJ', [
                'class' => is_object($statusObj) ? get_class($statusObj) : gettype($statusObj),
                'json' => json_encode($statusObj),
            ]);

            // Normalisasi paling robust
            $statusArr = json_decode(json_encode($statusObj), true);
            if (!is_array($statusArr) || empty($statusArr)) {
                $statusArr = (array) $statusObj;
            }

            Log::info('MIDTRANS STATUS RAW ARR', [
                'order_id' => $payment->order_id,
                'raw' => $statusArr,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'paid' => false,
                'message' => 'Gagal cek status ke Midtrans. Pastikan order_id valid & server key benar.',
                'error' => $e->getMessage(),
            ], 500);
        }

        // normalisasi object -> array
        $statusArr = is_array($statusObj)
            ? $statusObj
            : json_decode(json_encode($statusObj), true);

        $trxStatus = $statusArr['transaction_status'] ?? 'pending';
        $paymentType = $statusArr['payment_type'] ?? null;

        // Simpan response mentah (JSON column -> simpan array)
        $payment->update([
            'payment_type' => $paymentType,
            'raw_response' => $statusArr,
        ]);

        if (in_array($trxStatus, ['settlement', 'capture'], true)) {
            $payment->update(['status' => 'success']);
            $this->activateSubscription($payment);

            return response()->json([
                'paid' => true,
                'message' => "Pembayaran berhasil (Rp " . number_format($payment->amount, 0, ',', '.') . ")",
                'midtrans_status' => $trxStatus,
            ]);
        }

        if ($trxStatus === 'expire') {
            $payment->update(['status' => 'expired']);

            return response()->json([
                'paid' => false,
                'message' => 'Pembayaran kedaluwarsa. Silakan buat tagihan baru.',
                'midtrans_status' => $trxStatus,
            ]);
        }

        if (in_array($trxStatus, ['cancel', 'deny'], true)) {
            $payment->update(['status' => 'failed']);

            return response()->json([
                'paid' => false,
                'message' => 'Pembayaran gagal/cancel.',
                'midtrans_status' => $trxStatus,
            ]);
        }

        // pending
        $payment->update(['status' => 'pending']);

        return response()->json([
            'paid' => false,
            'message' => 'Pembayaran belum tuntas (masih pending).',
            'midtrans_status' => $trxStatus,
        ]);
    }

    /**
     * Webhook Midtrans (dengan signature validation untuk production)
     */
    public function callback(Request $request)
    {
        $this->midtransConfig();

        $payload = $request->all();

        Log::info('MIDTRANS CALLBACK', $payload);

        // 🔒 VALIDASI SIGNATURE (penting untuk production)
        $signatureKey = $payload['signature_key'] ?? null;
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;

        if ($signatureKey && $orderId && $statusCode && $grossAmount) {
            $serverKey = config('midtrans.server_key');
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signatureKey !== $expectedSignature) {
                Log::warning('MIDTRANS CALLBACK: Invalid signature', [
                    'order_id' => $orderId,
                    'received_signature' => $signatureKey,
                    'expected_signature' => $expectedSignature,
                ]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid callback'], 400);
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // 🔒 idempotent
        if ($payment->status === 'success') {
            return response()->json(['message' => 'Already processed']);
        }

        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $payment->update([
                'status' => 'success',
                'payment_type' => $paymentType,
                'raw_response' => $payload,
            ]);

            $this->activateSubscription($payment);
        }

        if ($transactionStatus === 'expire') {
            $payment->update([
                'status' => 'expired',
                'raw_response' => $payload,
            ]);
        }

        if (in_array($transactionStatus, ['cancel', 'deny'])) {
            $payment->update([
                'status' => 'failed',
                'raw_response' => $payload,
            ]);
        }

        return response()->json(['message' => 'Callback processed']);
    }


    /**
     * Manual activate subscription (untuk testing/sandbox)
     */
    public function manualActivate(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|string',
        ]);

        $payment = Payment::where('order_id', $data['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($payment->status === 'success') {
            return response()->json([
                'message' => 'Subscription sudah aktif',
                'subscription' => $payment->user->activeSubscription(),
            ]);
        }

        // Force activate untuk testing
        $payment->update(['status' => 'success']);
        $this->activateSubscription($payment);

        Log::info('MANUAL SUBSCRIPTION ACTIVATION', [
            'order_id' => $payment->order_id,
            'user_id' => $payment->user_id,
            'plan' => $payment->plan,
        ]);

        return response()->json([
            'message' => 'Subscription berhasil diaktifkan secara manual',
            'subscription' => $payment->user->activeSubscription(),
        ]);
    }

    private function activateSubscription(Payment $payment): void
    {
        $userId = $payment->user_id;
        $plan = $payment->plan;
        $durationDays = 30;

        // Cek apakah user sudah punya subscription aktif dengan plan yang sama
        $existingActiveSub = Subscription::where('user_id', $userId)
            ->where('plan', $plan)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->first();

        if ($existingActiveSub) {
            // Extend subscription yang sudah ada
            $existingActiveSub->update([
                'ends_at' => $existingActiveSub->ends_at->addDays($durationDays),
            ]);

            Log::info('EXTENDED EXISTING SUBSCRIPTION', [
                'subscription_id' => $existingActiveSub->id,
                'user_id' => $userId,
                'plan' => $plan,
                'new_end_date' => $existingActiveSub->ends_at,
            ]);

            return;
        }

        // Expire semua subscription aktif sebelumnya (yang plan berbeda)
        Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        // Buat subscription baru
        $newSubscription = Subscription::create([
            'user_id' => $userId,
            'plan' => $plan,
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
            'status' => 'active',
        ]);

        Log::info('CREATED NEW SUBSCRIPTION', [
            'subscription_id' => $newSubscription->id,
            'user_id' => $userId,
            'plan' => $plan,
            'start_date' => $newSubscription->starts_at,
            'end_date' => $newSubscription->ends_at,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function plans()
    {
        // Opsional: buat ditampilkan di view (kalau view kamu hardcode, tetap aman)
        $plans = [
            [
                'key' => 'basic',
                'name' => 'Basic',
                'price' => 25000,
                'duration_days' => 30,
                'features' => ['Maks 50 transaksi/bulan', 'Laporan harian', 'Midtrans tidak tersedia'],
            ],
            [
                'key' => 'pro',
                'name' => 'Pro',
                'price' => 50000,
                'duration_days' => 30,
                'features' => ['Transaksi unlimited', 'Laporan bulanan & tahunan', 'Midtrans tersedia'],
            ],
        ];

        return view('subscriptions.plans', compact('plans'));
    }

    /**
     * ✅ BASIC: langsung aktif tanpa bayar
     * Route: POST /subscribe/basic  (name: subscriptions.basic)
     */
    public function subscribeBasic(Request $request)
    {
        $user = $request->user();
        $durationDays = 30;

        // Ambil subscription aktif (punya kamu: $user->activeSubscription())
        $active = $user->activeSubscription();

        // Kalau user sudah punya PRO aktif, jangan turunkan ke BASIC (opsional, tapi ini lebih aman)
        if ($active && $active->plan === 'pro') {
            return redirect()->route('dashboard')
                ->with('error', 'Kamu sudah memiliki paket PRO aktif. Tidak bisa downgrade ke BASIC saat masih aktif.');
        }

        if ($active) {
            // Kalau sudah BASIC aktif → perpanjang
            $newEndsAt = $active->ends_at->copy()->addDays($durationDays);

            $active->update([
                'plan' => 'basic',
                'ends_at' => $newEndsAt,
                'status' => 'active',
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Paket BASIC diperpanjang sampai ' . $newEndsAt->format('d M Y H:i'));
        }

        // Kalau belum ada subscription aktif → buat baru
        $startsAt = now();
        $endsAt = now()->addDays($durationDays);

        Subscription::create([
            'user_id' => $user->id,
            'plan' => 'basic',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Paket BASIC aktif sampai ' . $endsAt->format('d M Y H:i'));
    }

    /**
     * 🚫 FLOW LAMA: subscribe basic/pro langsung aktif
     * Kamu boleh SIMPAN tapi batasi untuk local/testing saja
     * (supaya user tidak bisa aktifkan PRO tanpa bayar Midtrans)
     */
    public function subscribe(Request $request)
    {
        if (!app()->isLocal()) {
            abort(404);
        }

        // Kalau masih mau testing, tetap boleh:
        $data = $request->validate([
            'plan' => 'required|in:basic,pro',
        ]);

        $user = $request->user();
        $durationDays = 30;

        $active = $user->activeSubscription();

        if ($active) {
            $newEndsAt = $active->ends_at->copy()->addDays($durationDays);

            $active->update([
                'plan' => $data['plan'],
                'ends_at' => $newEndsAt,
                'status' => 'active',
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Langganan (TEST) diperpanjang sampai ' . $newEndsAt->format('d M Y H:i'));
        }

        $startsAt = now();
        $endsAt = now()->addDays($durationDays);

        Subscription::create([
            'user_id' => $user->id,
            'plan' => $data['plan'],
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Langganan (TEST) aktif sampai ' . $endsAt->format('d M Y H:i'));
    }
}

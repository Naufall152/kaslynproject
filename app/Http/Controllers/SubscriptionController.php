<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function plans()
    {
        // Paket contoh (nanti bisa diambil dari DB)
        // Catatan: view kamu sekarang sudah hardcode fitur sesuai tabel,
        // tapi $plans ini tetap aman kalau masih dipakai.
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

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'plan' => 'required|in:basic,pro',
        ]);

        $user = $request->user();

        // Durasi langganan (bisa dibedakan per plan kalau mau)
        $durationDays = 30;

        // Ambil subscription yang benar-benar aktif (starts_at <= now < ends_at)
        $active = $user->activeSubscription();

        if ($active) {
            // Kalau sudah punya langganan aktif:
            // - update plan (upgrade/downgrade)
            // - perpanjang masa aktif dari ends_at saat ini
            $newEndsAt = $active->ends_at->copy()->addDays($durationDays);

            $active->update([
                'plan' => $data['plan'],
                'ends_at' => $newEndsAt,
                'status' => 'active',
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Langganan diperpanjang sampai ' . $newEndsAt->format('d M Y H:i'));
        }

        // Jika belum ada langganan aktif -> buat baru mulai sekarang
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
            ->with('success', 'Langganan aktif sampai ' . $endsAt->format('d M Y H:i'));
    }
}

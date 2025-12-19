<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(['key' => 'basic'], [
            'name' => 'Basic',
            'price' => 0, // sesuai revisi kamu: basic aktif tanpa bayar
            'duration_days' => 30,
            'is_active' => true,
            'features' => ['Maks 50 transaksi/bulan', 'Tren hari ini saja', 'Laporan harian'],
        ]);

        Plan::updateOrCreate(['key' => 'pro'], [
            'name' => 'Pro',
            'price' => 50000,
            'duration_days' => 30,
            'is_active' => true,
            'features' => ['Transaksi unlimited', 'Tren bulanan & tahunan', 'Export laporan'],
        ]);
    }
}

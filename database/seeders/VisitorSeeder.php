<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Visitor;
use Carbon\Carbon;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create dummy visitor data for the last 30 days
        $startDate = Carbon::now()->subDays(29);

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);

            // Generate random visits per day (between 5-50)
            $visitsPerDay = rand(5, 50);

            for ($j = 0; $j < $visitsPerDay; $j++) {
                Visitor::create([
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'url' => '/',
                    'referer' => null,
                    'session_id' => 'session_' . rand(1000, 9999) . '_' . $i,
                    'visit_date' => $date->format('Y-m-d'),
                    'visit_time' => Carbon::createFromTime(rand(0, 23), rand(0, 59), rand(0, 59)),
                ]);
            }
        }
    }
}

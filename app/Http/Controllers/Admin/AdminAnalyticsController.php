<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    /**
     * Get detailed visitor analytics
     */
    public function getDetailedAnalytics(Request $request)
    {
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days);

        $stats = Visitor::selectRaw('
                visit_date,
                COUNT(*) as total_visits,
                COUNT(DISTINCT session_id) as unique_sessions,
                COUNT(DISTINCT ip_address) as unique_ips
            ')
            ->where('visit_date', '>=', $startDate)
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get();

        return response()->json([
            'period' => $days . ' hari',
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d'),
            'stats' => $stats,
            'summary' => [
                'total_visits' => $stats->sum('total_visits'),
                'avg_daily_visits' => round($stats->avg('total_visits'), 1),
                'total_unique_sessions' => $stats->sum('unique_sessions'),
                'total_unique_ips' => $stats->sum('unique_ips'),
                'avg_unique_sessions' => round($stats->avg('unique_sessions'), 1),
                'avg_unique_ips' => round($stats->avg('unique_ips'), 1),
            ]
        ]);
    }

    /**
     * Get visitor statistics for chart
     */
    public function getVisitorStats(Request $request)
    {
        $days = $request->get('days', 30);
        $stats = Visitor::getVisitorStats($days);

        // Fill missing dates with 0
        $startDate = Carbon::now()->subDays($days - 1);
        $endDate = Carbon::now();

        $labels = [];
        $data = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('M j');
            $data[] = $stats->get($dateString, 0);
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'total' => array_sum($data),
            'unique' => Visitor::getUniqueVisitorsBySession($days), // Changed to session-based
            'today' => Visitor::getTodayVisitors(),
            'yesterday' => Visitor::getYesterdayVisitors(),
        ]);
    }
}

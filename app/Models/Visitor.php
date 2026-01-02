<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'url',
        'referer',
        'session_id',
        'visit_date',
        'visit_time',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'visit_time' => 'datetime',
    ];

    /**
     * Get visitor statistics for the last N days
     */
    public static function getVisitorStats($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        return self::selectRaw('visit_date, COUNT(*) as count')
            ->where('visit_date', '>=', $startDate)
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get()
            ->keyBy('visit_date')
            ->map(function ($item) {
                return $item->count;
            });
    }

    /**
     * Get total unique visitors for a period (based on IP + User Agent)
     */
    public static function getUniqueVisitors($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        return self::where('visit_date', '>=', $startDate)
            ->selectRaw('DISTINCT CONCAT(ip_address, "_", LEFT(user_agent, 100)) as unique_visitor')
            ->get()
            ->count();
    }

    /**
     * Get total unique visitors for a period (session-based - more accurate)
     */
    public static function getUniqueVisitorsBySession($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        return self::where('visit_date', '>=', $startDate)
            ->distinct('session_id')
            ->count('session_id');
    }

    /**
     * Get today's visitor count
     */
    public static function getTodayVisitors()
    {
        return self::where('visit_date', Carbon::today())->count();
    }

    /**
     * Get yesterday's visitor count
     */
    public static function getYesterdayVisitors()
    {
        return self::where('visit_date', Carbon::yesterday())->count();
    }
}

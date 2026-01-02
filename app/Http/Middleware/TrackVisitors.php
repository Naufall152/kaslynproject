<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visitor;
use Carbon\Carbon;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip tracking untuk:
        // - Admin routes
        // - API routes
        // - Static assets (css, js, images)
        // - AJAX requests
        // - Bot requests
        if ($this->shouldSkipTracking($request)) {
            return $response;
        }

        $this->trackVisitor($request);

        return $response;
    }

    /**
     * Check if we should skip tracking this request
     */
    private function shouldSkipTracking(Request $request): bool
    {
        // Skip admin routes
        if ($request->is('admin/*')) {
            return true;
        }

        // Skip API routes
        if ($request->is('api/*')) {
            return true;
        }

        // Skip static assets
        if ($request->is(['*.css', '*.js', '*.png', '*.jpg', '*.jpeg', '*.gif', '*.ico', '*.svg', '*.woff', '*.woff2'])) {
            return true;
        }

        // Skip AJAX requests
        if ($request->ajax()) {
            return true;
        }

        // Skip bots (basic check)
        $userAgent = strtolower($request->userAgent() ?? '');
        $bots = ['bot', 'crawler', 'spider', 'googlebot', 'bingbot', 'yahoo', 'duckduckbot'];
        foreach ($bots as $bot) {
            if (str_contains($userAgent, $bot)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Track the visitor
     */
    private function trackVisitor(Request $request): void
    {
        $ip = $request->ip();
        $sessionId = session()->getId();
        $today = Carbon::today();

        // Check if this session already visited today
        $existingVisit = Visitor::where('session_id', $sessionId)
            ->where('visit_date', $today)
            ->exists();

        if (!$existingVisit) {
            Visitor::create([
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'referer' => $request->header('referer'),
                'session_id' => $sessionId,
                'visit_date' => $today,
                'visit_time' => Carbon::now(),
            ]);
        }
    }
}

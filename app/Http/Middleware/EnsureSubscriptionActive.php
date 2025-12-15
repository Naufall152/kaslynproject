<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->hasActiveSubscription()) {
            return redirect()->route('subscriptions.plans')
                ->with('error', 'Langganan kamu tidak aktif. Silakan pilih paket dulu.');
        }

        return $next($request);
    }
}

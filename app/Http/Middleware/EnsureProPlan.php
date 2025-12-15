<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProPlan
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->hasActiveSubscription()) {
            return redirect()->route('subscriptions.plans')
                ->with('error', 'Silakan aktifkan langganan terlebih dahulu.');
        }

        if (!$user->isPro()) {
            return redirect()->route('subscriptions.plans')
                ->with('error', 'Fitur ini hanya untuk Paket Pro.');
        }

        return $next($request);
    }
}

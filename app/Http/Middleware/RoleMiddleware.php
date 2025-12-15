<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        // Kalau middleware tidak diberi parameter role, jangan crash
        // (Ini juga membantu kalau ada salah pemakaian middleware)
        if ($role === null) {
            return $next($request);
        }

        // Harus login
        if (!$request->user()) {
            abort(403, 'Unauthorized');
        }

        // Cek role
        if ($request->user()->role !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

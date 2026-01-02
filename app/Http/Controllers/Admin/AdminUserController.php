<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->with(['subscriptions' => function ($q) {
                $q->orderByDesc('ends_at'); // ambil yang terbaru dulu
            }])
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['subscriptions' => function ($q) {
            $q->orderByDesc('ends_at');
        }]);

        return view('admin.users.show', compact('user'));
    }
}

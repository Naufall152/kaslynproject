<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $subscription = $user->activeSubscription(); // kamu sudah punya method ini
        $latestTransactions = Transaction::where('user_id', $user->id)
            ->latest('transaction_date')
            ->take(20)
            ->get();

        return view('admin.users.show', compact('user', 'subscription', 'latestTransactions'));
    }
}

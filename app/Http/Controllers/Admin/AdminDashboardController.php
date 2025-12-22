<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total user UKM (exclude admin)
        $totalUsers = User::where('role', 'user')->count();

        // Subscription aktif saat ini
        $activeSubs = Subscription::where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->count();

        // Payment pending
        $pendingPayments = Payment::where('status', 'pending')->count();

        // Total transaksi (semua UKM)
        $totalTransactions = Transaction::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeSubs',
            'pendingPayments',
            'totalTransactions'
        ));
    }
}

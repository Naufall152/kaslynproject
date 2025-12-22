<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $transactions = Transaction::with('user')
            ->when($q, function ($query) use ($q) {
                $query->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                })
                ->orWhere('category', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
            })
            ->latest('transaction_date')
            ->paginate(15);

        return view('admin.transactions.index', compact('transactions'));
    }
}

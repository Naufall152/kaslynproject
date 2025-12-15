<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // ==================================================
        // LIMIT TRANSAKSI PAKET BASIC: MAKS 50 / BULAN
        // ==================================================
        if ($user->isBasic()) {
            $month = now()->format('Y-m');

            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            $countThisMonth = Transaction::where('user_id', $user->id)
                ->whereBetween('transaction_date', [$start, $end])
                ->count();

            if ($countThisMonth >= 50) {
                return back()
                    ->withInput()
                    ->with('error', 'Paket Basic dibatasi 50 transaksi per bulan. Upgrade ke Pro untuk unlimited.');
            }
        }

        // Validasi input
        $data = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
        ]);

        $data['user_id'] = $user->id;

        Transaction::create($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Request $request, Transaction $transaction)
    {
        $this->authorizeOwner($request, $transaction);

        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeOwner($request, $transaction);

        $data = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($data);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $this->authorizeOwner($request, $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function authorizeOwner(Request $request, Transaction $transaction): void
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }
    }
}

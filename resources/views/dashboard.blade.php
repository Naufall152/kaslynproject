{{-- resources/views/dashboard.blade.php --}}
@php
    use App\Models\Transaction;

    $userId = auth()->id();

    $income = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
    $expense = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
    $balance = $income - $expense;

    $latest = Transaction::where('user_id', $userId)
        ->orderByDesc('transaction_date')
        ->orderByDesc('id')
        ->take(5)
        ->get();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-widest text-emerald-600">Kaslyn</p>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                    Dashboard Keuangan
                </h2>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('transactions.create') }}"
                   class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                    + Tambah Transaksi
                </a>

                <a href="{{ route('transactions.index') }}"
                   class="px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-50 transition">
                    Lihat Transaksi
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Background theme --}}
            <div class="mb-6 rounded-2xl overflow-hidden border border-emerald-100">
                <div class="p-6 sm:p-8 bg-gradient-to-r from-emerald-500 to-emerald-300 text-white">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold">Selamat datang, {{ auth()->user()->name }} 👋</h3>
                            <p class="text-white/90 text-sm mt-1">
                                Pantau pemasukan & pengeluaran UKM kamu secara sederhana dan cepat.
                            </p>
                        </div>
                        <div class="bg-white/15 rounded-xl px-4 py-3">
                            <p class="text-xs uppercase tracking-widest text-white/80">Saldo Saat Ini</p>
                            <p class="text-xl font-extrabold">
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Total Pemasukan</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-700">
                        Rp {{ number_format($income, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-gray-400">Akumulasi seluruh pemasukan</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Total Pengeluaran</p>
                    <p class="mt-2 text-2xl font-bold text-red-600">
                        Rp {{ number_format($expense, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-gray-400">Akumulasi seluruh pengeluaran</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Saldo</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        Rp {{ number_format($balance, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-gray-400">Pemasukan - Pengeluaran</p>
                </div>
            </div>

            {{-- Latest transactions --}}
            <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h3>
                    <a href="{{ route('transactions.index') }}" class="text-sm font-semibold text-emerald-700 hover:underline">
                        Lihat semua
                    </a>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Tipe</th>
                                <th class="py-3">Kategori</th>
                                <th class="py-3">Deskripsi</th>
                                <th class="py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latest as $t)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-3">
                                        {{ \Carbon\Carbon::parse($t->transaction_date)->format('d M Y') }}
                                    </td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            {{ $t->type === 'income' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                    </td>
                                    <td class="py-3">{{ $t->category ?? '-' }}</td>
                                    <td class="py-3">{{ $t->description ?? '-' }}</td>
                                    <td class="py-3 text-right font-semibold">
                                        Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500">
                                        Belum ada transaksi. Mulai catat pemasukan/pengeluaran sekarang.
                                        <div class="mt-3">
                                            <a href="{{ route('transactions.create') }}"
                                               class="inline-flex px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                                                + Tambah Transaksi
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <p class="mt-4 text-xs text-gray-400">
                    Tip: Catat transaksi harian biar laporan UKM kamu rapi.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>

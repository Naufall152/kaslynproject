{{-- resources/views/dashboard.blade.php --}}
@php
    use App\Models\Transaction;
    use Carbon\Carbon;

    $userId = auth()->id();

    // =========================
    // FILTER BULAN (untuk ringkasan + laporan)
    // =========================
    $month = request('month', now()->format('Y-m')); // format: YYYY-MM
    try {
        [$year, $mon] = explode('-', $month);
        $start = Carbon::createFromDate((int) $year, (int) $mon, 1)->startOfDay();
        $end = $start->copy()->endOfMonth()->endOfDay();
    } catch (\Throwable $e) {
        $month = now()->format('Y-m');
        $start = now()->startOfMonth()->startOfDay();
        $end = now()->endOfMonth()->endOfDay();
    }

    // =========================
    // RINGKASAN (BULAN TERPILIH)
    // =========================
    $income = Transaction::where('user_id', $userId)
        ->where('type', 'income')
        ->whereBetween('transaction_date', [$start, $end])
        ->sum('amount');

    $expense = Transaction::where('user_id', $userId)
        ->where('type', 'expense')
        ->whereBetween('transaction_date', [$start, $end])
        ->sum('amount');

    $balance = $income - $expense;

    // =========================
    // TRANSAKSI TERBARU (tanpa filter bulan biar tetap ada)
    // =========================
    $latest = Transaction::where('user_id', $userId)
        ->orderByDesc('transaction_date')
        ->orderByDesc('id')
        ->take(5)
        ->get();

    // =========================
    // CHART: 6 BULAN TERAKHIR
    // (kalau controller belum nyediain, ini fallback)
    // =========================
    if (!isset($chartLabels, $chartIncome, $chartExpense)) {
        $months = collect(range(0, 5))->map(fn($i) => now()->subMonths(5 - $i));

        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];

        foreach ($months as $m) {
            $s = $m->copy()->startOfMonth()->startOfDay();
            $e = $m->copy()->endOfMonth()->endOfDay();

            $chartLabels[] = $m->format('M Y');

            $chartIncome[] = (float) Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('transaction_date', [$s, $e])
                ->sum('amount');

            $chartExpense[] = (float) Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$s, $e])
                ->sum('amount');
        }
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-widest text-emerald-600">Kaslyn</p>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                    Dashboard Keuangan
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Periode: <span class="font-semibold">{{ $start->format('M Y') }}</span>
                </p>
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

            {{-- HERO --}}
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
                            <p class="text-xs uppercase tracking-widest text-white/80">Saldo Periode Ini</p>
                            <p class="text-xl font-extrabold">
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER BULAN (untuk ringkasan) --}}
            <div class="mb-4 flex items-center justify-between">
                <p class="text-sm text-slate-600">
                    Ringkasan berdasarkan bulan yang dipilih.
                </p>

                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <input type="month" name="month" value="{{ $month }}"
                        class="rounded-lg border-gray-200 text-sm focus:border-emerald-400 focus:ring-emerald-400" />
                    <button
                        class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                        Terapkan
                    </button>
                </form>

            </div>

            {{-- SUMMARY --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Pemasukan (Periode)</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-700">
                        Rp {{ number_format($income, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-gray-400">Total pemasukan bulan ini</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Pengeluaran (Periode)</p>
                    <p class="mt-2 text-2xl font-bold text-red-600">
                        Rp {{ number_format($expense, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-gray-400">Total pengeluaran bulan ini</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Saldo (Periode)</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        Rp {{ number_format($balance, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-gray-400">Pemasukan - Pengeluaran</p>
                </div>
            </div>

            {{-- CHART: TREN 6 BULAN --}}
            {{-- CHART --}}
            <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $plan === 'basic' ? 'Tren Hari Ini' : 'Tren 6 Bulan Terakhir' }}
                    </h3>

                    @if ($plan !== 'basic')
                        <p class="text-xs text-slate-500">
                            (Grafik tidak terpengaruh filter bulan)
                        </p>
                    @else
                        <span class="text-xs text-slate-500">
                            Paket Basic hanya bisa lihat tren hari ini. Upgrade ke Pro untuk bulanan/tahunan.
                        </span>
                    @endif
                </div>

                <div class="mt-4">
                    <canvas id="trendChart" height="90"></canvas>
                </div>
            </div>


            {{-- LATEST TRANSACTIONS --}}
            <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h3>
                    <a href="{{ route('transactions.index') }}"
                        class="text-sm font-semibold text-emerald-700 hover:underline">
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
                                        <span
                                            class="px-2 py-1 rounded text-xs font-semibold
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

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('trendChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                        label: 'Pemasukan',
                        data: @json($chartIncome),
                        tension: 0.35
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($chartExpense),
                        tension: 0.35
                    },
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>

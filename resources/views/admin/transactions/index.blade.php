<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                Semua Transaksi UKM
            </h2>

            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <form method="GET" class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between mb-6">
                    <div class="flex gap-2 w-full md:w-2/3">
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Cari: nama user / email / kategori / deskripsi"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">

                        <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                            Cari
                        </button>

                        <a href="{{ route('admin.transactions.index') }}"
                           class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                            Reset
                        </a>
                    </div>

                    <div class="text-sm text-slate-500">
                        Total: <span class="font-semibold text-slate-900">{{ $transactions->total() }}</span>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b">
                                <th class="py-3 pr-4">Tanggal</th>
                                <th class="py-3 pr-4">User</th>
                                <th class="py-3 pr-4">Email</th>
                                <th class="py-3 pr-4">Jenis</th>
                                <th class="py-3 pr-4">Kategori</th>
                                <th class="py-3 pr-4">Jumlah</th>
                                <th class="py-3 pr-4">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-800">
                            @forelse($transactions as $trx)
                                <tr class="border-b">
                                    <td class="py-3 pr-4 whitespace-nowrap">
                                        {{ optional($trx->transaction_date)->format('d M Y H:i') ?? '-' }}
                                    </td>
                                    <td class="py-3 pr-4 whitespace-nowrap">
                                        {{ $trx->user->name ?? '-' }}
                                    </td>
                                    <td class="py-3 pr-4 whitespace-nowrap">
                                        {{ $trx->user->email ?? '-' }}
                                    </td>
                                    <td class="py-3 pr-4 whitespace-nowrap">
                                        {{ $trx->type ?? '-' }}
                                    </td>
                                    <td class="py-3 pr-4 whitespace-nowrap">
                                        {{ $trx->category ?? '-' }}
                                    </td>
                                    <td class="py-3 pr-4 whitespace-nowrap font-semibold">
                                        Rp {{ number_format((int)($trx->amount ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 pr-4">
                                        {{ $trx->description ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-slate-500">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

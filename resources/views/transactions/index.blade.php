<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Transaksi Keuangan
            </h2>

            <a href="{{ route('transactions.create') }}"
               class="px-4 py-2 rounded-md bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                + Tambah Transaksi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Tipe</th>
                                <th class="py-3">Kategori</th>
                                <th class="py-3">Deskripsi</th>
                                <th class="py-3 text-right">Jumlah</th>
                                <th class="py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                                <tr class="border-b">
                                    <td class="py-3">{{ $t->transaction_date->format('d M Y') }}</td>
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
                                    <td class="py-3 text-right">
                                        <a href="{{ route('transactions.edit', $t) }}"
                                           class="text-amber-700 hover:underline mr-3">Edit</a>

                                        <form action="{{ route('transactions.destroy', $t) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Yakin mau hapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-700 hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-500">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

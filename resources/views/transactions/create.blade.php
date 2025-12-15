<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Transaksi
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm text-gray-600">Tipe</label>
                        <select name="type" class="mt-1 w-full rounded border-gray-300">
                            <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Kategori</label>
                        <input name="category" value="{{ old('category') }}" class="mt-1 w-full rounded border-gray-300" placeholder="Contoh: Penjualan / Belanja bahan" />
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Deskripsi</label>
                        <input name="description" value="{{ old('description') }}" class="mt-1 w-full rounded border-gray-300" placeholder="Contoh: Penjualan hari ini" />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Jumlah (Rp)</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}"
                               class="mt-1 w-full rounded border-gray-300" placeholder="50000" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Tanggal</label>
                        <input type="date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}"
                               class="mt-1 w-full rounded border-gray-300" />
                        <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('transactions.index') }}"
                           class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
                            Batal
                        </a>
                        <button class="px-4 py-2 rounded bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

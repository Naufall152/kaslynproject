<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Transaksi
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm text-gray-600">Tipe</label>
                        <select name="type" class="mt-1 w-full rounded border-gray-300">
                            <option value="income" {{ old('type', $transaction->type) === 'income' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="expense" {{ old('type', $transaction->type) === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Kategori</label>
                        <input name="category" value="{{ old('category', $transaction->category) }}"
                               class="mt-1 w-full rounded border-gray-300" />
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Deskripsi</label>
                        <input name="description" value="{{ old('description', $transaction->description) }}"
                               class="mt-1 w-full rounded border-gray-300" />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Jumlah (Rp)</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}"
                               class="mt-1 w-full rounded border-gray-300" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Tanggal</label>
                        <input type="date" name="transaction_date"
                               value="{{ old('transaction_date', $transaction->transaction_date->toDateString()) }}"
                               class="mt-1 w-full rounded border-gray-300" />
                        <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('transactions.index') }}"
                           class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
                            Batal
                        </a>
                        <button class="px-4 py-2 rounded bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

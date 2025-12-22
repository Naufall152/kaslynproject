<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Tambah Paket</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <form method="POST" action="{{ route('admin.plans.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm text-slate-600">Code</label>
                        <input name="code" value="{{ old('code') }}" class="w-full rounded-lg border px-3 py-2" placeholder="basic / pro">
                        @error('code')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Nama</label>
                        <input name="name" value="{{ old('name') }}" class="w-full rounded-lg border px-3 py-2" placeholder="Paket BASIC / PRO">
                        @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-slate-600">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ old('price', 0) }}" class="w-full rounded-lg border px-3 py-2">
                            @error('price')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="text-sm text-slate-600">Durasi (hari)</label>
                            <input type="number" name="duration_days" value="{{ old('duration_days', 30) }}" class="w-full rounded-lg border px-3 py-2">
                            @error('duration_days')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Status</label>
                        <select name="is_active" class="w-full rounded-lg border px-3 py-2">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_active')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Features (opsional)</label>
                        <textarea name="features" rows="4" class="w-full rounded-lg border px-3 py-2"
                                  placeholder="Boleh isi teks biasa / JSON string">{{ old('features') }}</textarea>
                        @error('features')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                            Simpan
                        </button>
                        <a href="{{ route('admin.plans.index') }}"
                           class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

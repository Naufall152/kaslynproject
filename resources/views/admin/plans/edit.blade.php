<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Edit Paket</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm text-slate-600">Code</label>
                        <input name="key" value="{{ old('key', $plan->key) }}" class="w-full rounded-lg border px-3 py-2">
                        @error('key')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Nama</label>
                        <input name="name" value="{{ old('name', $plan->name) }}" class="w-full rounded-lg border px-3 py-2">
                        @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-slate-600">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ old('price', $plan->price) }}" class="w-full rounded-lg border px-3 py-2">
                            @error('price')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="text-sm text-slate-600">Durasi (hari)</label>
                            <input type="number" name="duration_days" value="{{ old('duration_days', $plan->duration_days) }}" class="w-full rounded-lg border px-3 py-2">
                            @error('duration_days')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Status</label>
                        <select name="is_active" class="w-full rounded-lg border px-3 py-2">
                            <option value="1" {{ old('is_active', (int)$plan->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', (int)$plan->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_active')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Features (opsional)</label>
                        <textarea name="features" rows="4" class="w-full rounded-lg border px-3 py-2">{{ old('features', is_array($plan->features) ? implode("\n", $plan->features) : $plan->features) }}</textarea>
                        @error('features')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                            Update
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

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">Manajemen Paket</h2>

            <a href="{{ route('admin.plans.create') }}"
               class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                + Tambah Paket
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b">
                            <th class="py-3 pr-4">Code</th>
                            <th class="py-3 pr-4">Nama</th>
                            <th class="py-3 pr-4">Harga</th>
                            <th class="py-3 pr-4">Durasi</th>
                            <th class="py-3 pr-4">Status</th>
                            <th class="py-3 pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-800">
                        @forelse($plans as $plan)
                            <tr class="border-b">
                                <td class="py-3 pr-4 font-semibold">{{ $plan->key }}</td>
                                <td class="py-3 pr-4">{{ $plan->name }}</td>
                                <td class="py-3 pr-4">
                                    Rp {{ number_format((int)$plan->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 pr-4">{{ $plan->duration_days }} hari</td>
                                <td class="py-3 pr-4">
                                    @if($plan->is_active)
                                        <span class="px-2 py-1 rounded-full text-xs bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs bg-slate-50 text-slate-700 border border-slate-200">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 whitespace-nowrap">
                                    <a href="{{ route('admin.plans.edit', $plan) }}"
                                       class="px-3 py-1.5 rounded-lg border border-emerald-200 text-emerald-700 text-xs font-semibold hover:bg-emerald-50">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin hapus paket ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1.5 rounded-lg border border-red-200 text-red-700 text-xs font-semibold hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-500">Belum ada paket.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                    Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">Detail User</h2>
            <a href="{{ route('admin.users.index') }}"
               class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="space-y-1">
                    <div class="text-slate-500 text-sm">Nama</div>
                    <div class="font-semibold text-slate-900">{{ $user->name }}</div>

                    <div class="text-slate-500 text-sm mt-4">Email</div>
                    <div class="text-slate-900">{{ $user->email }}</div>

                    <div class="text-slate-500 text-sm mt-4">Role</div>
                    <div class="text-slate-900">{{ $user->role ?? 'user' }}</div>
                </div>

                <hr class="my-6">

                <h3 class="font-semibold text-slate-900 mb-3">Riwayat Subscription</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b">
                                <th class="py-3 pr-4">Plan</th>
                                <th class="py-3 pr-4">Status</th>
                                <th class="py-3 pr-4">Mulai</th>
                                <th class="py-3 pr-4">Berakhir</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-800">
                            @forelse($user->subscriptions as $s)
                                <tr class="border-b">
                                    <td class="py-3 pr-4 font-semibold">{{ strtoupper($s->plan) }}</td>
                                    <td class="py-3 pr-4">{{ $s->status }}</td>
                                    <td class="py-3 pr-4">{{ \Carbon\Carbon::parse($s->starts_at)->format('d M Y H:i') }}</td>
                                    <td class="py-3 pr-4">{{ \Carbon\Carbon::parse($s->ends_at)->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-500">Belum ada subscription.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>

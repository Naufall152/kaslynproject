<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">Kelola User</h2>
            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b">
                            <th class="py-3 pr-4">Nama</th>
                            <th class="py-3 pr-4">Email</th>
                            <th class="py-3 pr-4">Role</th>
                            <th class="py-3 pr-4">Paket</th>
                            <th class="py-3 pr-4">Status</th>
                            <th class="py-3 pr-4">Berakhir</th>
                            <th class="py-3 pr-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-slate-800">
                        @forelse($users as $u)
                            @php
                                $latestSub = $u->subscriptions->first(); // karena sudah orderByDesc ends_at
                                $plan = $latestSub?->plan;
                                $status = $latestSub?->status;

                                // tentukan label status aktif / tidak
                                $isActive = false;
                                if ($latestSub) {
                                    $isActive = $latestSub->status === 'active'
                                        && $latestSub->starts_at <= now()
                                        && $latestSub->ends_at > now();
                                }
                            @endphp

                            <tr class="border-b">
                                <td class="py-3 pr-4 font-semibold">{{ $u->name }}</td>
                                <td class="py-3 pr-4">{{ $u->email }}</td>
                                <td class="py-3 pr-4">{{ $u->role ?? 'user' }}</td>

                                <td class="py-3 pr-4">
                                    @if($latestSub)
                                        <span class="px-2 py-1 rounded-full text-xs border border-slate-200 bg-slate-50 text-slate-700">
                                            {{ strtoupper($plan) }}
                                        </span>
                                    @else
                                        <span class="text-slate-500">Belum ada</span>
                                    @endif
                                </td>

                                <td class="py-3 pr-4">
                                    @if(!$latestSub)
                                        <span class="px-2 py-1 rounded-full text-xs border border-slate-200 bg-slate-50 text-slate-700">
                                            Tidak ada subscription
                                        </span>
                                    @elseif($isActive)
                                        <span class="px-2 py-1 rounded-full text-xs border border-emerald-200 bg-emerald-50 text-emerald-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs border border-yellow-200 bg-yellow-50 text-yellow-700">
                                            {{ $status ?? 'unknown' }}
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3 pr-4">
                                    {{ $latestSub?->ends_at ? \Carbon\Carbon::parse($latestSub->ends_at)->format('d M Y H:i') : '-' }}
                                </td>

                                <td class="py-3 pr-4">
                                    <a href="{{ route('admin.users.show', $u) }}"
                                       class="px-3 py-1.5 rounded-lg border border-emerald-200 text-emerald-700 text-xs font-semibold hover:bg-emerald-50">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-500">Belum ada user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

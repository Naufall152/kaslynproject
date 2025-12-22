<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                Admin Dashboard
            </h2>
            <div class="text-sm text-slate-500">
                Panel Admin Kaslyn
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-slate-500">Total UKM (User)</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalUsers }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-slate-500">Subscription Aktif</p>
                    <p class="mt-2 text-2xl font-extrabold text-emerald-700">{{ $activeSubs }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-slate-500">Payment Pending</p>
                    <p class="mt-2 text-2xl font-extrabold text-yellow-600">{{ $pendingPayments }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-slate-500">Total Transaksi</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalTransactions }}</p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                    Kelola User
                </a>
                <a href="{{ route('admin.transactions.index') }}"
                   class="px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-50">
                    Lihat Transaksi
                </a>
                <a href="{{ route('admin.plans.index') }}"
                   class="px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-50">
                    Manajemen Paket
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

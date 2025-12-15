<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-widest text-emerald-600">Kaslyn</p>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                    Pilih Paket Langganan
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Sesuaikan paket dengan kebutuhan UKM kamu.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="hidden sm:inline-flex px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-50 transition">
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert --}}
            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Plans --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- BASIC --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                Paket Basic
                            </span>
                            <h3 class="mt-3 text-xl font-extrabold text-slate-900">Basic</h3>
                            <p class="mt-1 text-sm text-slate-500">Untuk UKM yang baru mulai.</p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm text-slate-500">Mulai dari</p>
                            <p class="text-2xl font-extrabold text-emerald-700">
                                Rp 25.000<span class="text-sm font-semibold text-slate-500">/bln</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-widest text-slate-500 font-semibold">Fitur</p>
                        <ul class="mt-3 text-sm text-slate-700 space-y-2">
                            <li class="flex items-center gap-2">
                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs">✓</span>
                                Pencatatan transaksi <b>maks. 50 transaksi/bulan</b>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs">✓</span>
                                Laporan keuangan <b>harian</b>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-red-100 text-red-700 text-xs">✕</span>
                                Integrasi Midtrans <b>tidak tersedia</b>
                            </li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('subscriptions.subscribe') }}" class="mt-6">
                        @csrf
                        <input type="hidden" name="plan" value="basic">
                        <button
                            class="w-full px-4 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
                            Pilih Basic
                        </button>
                    </form>
                </div>

                {{-- PRO --}}
                <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-6 relative overflow-hidden">
                    {{-- Ribbon --}}
                    <div class="absolute -top-10 -right-10 w-36 h-36 bg-emerald-100 rounded-full"></div>

                    <div class="flex items-start justify-between relative">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                Rekomendasi
                            </span>
                            <h3 class="mt-3 text-xl font-extrabold text-slate-900">Pro</h3>
                            <p class="mt-1 text-sm text-slate-500">Untuk UKM yang butuh laporan lengkap.</p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm text-slate-500">Mulai dari</p>
                            <p class="text-2xl font-extrabold text-emerald-700">
                                Rp 50.000<span class="text-sm font-semibold text-slate-500">/bln</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 rounded-xl border border-emerald-100 bg-emerald-50/60 p-4 relative">
                        <p class="text-xs uppercase tracking-widest text-emerald-700 font-semibold">Fitur</p>
                        <ul class="mt-3 text-sm text-slate-700 space-y-2">
                            <li class="flex items-center gap-2">
                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-200 text-emerald-800 text-xs">✓</span>
                                Pencatatan transaksi <b>unlimited</b>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-200 text-emerald-800 text-xs">✓</span>
                                Laporan keuangan <b>bulanan & tahunan</b>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-200 text-emerald-800 text-xs">✓</span>
                                Integrasi Midtrans <b>tersedia</b>
                            </li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('subscriptions.subscribe') }}" class="mt-6">
                        @csrf
                        <input type="hidden" name="plan" value="pro">
                        <button
                            class="w-full px-4 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
                            Pilih Pro
                        </button>
                    </form>
                </div>

            </div>

            <p class="mt-6 text-xs text-slate-500">
                Catatan: Integrasi Midtrans hanya tersedia di paket Pro. Paket Basic dibatasi 50 transaksi/bulan.
            </p>

        </div>
    </div>
</x-app-layout>

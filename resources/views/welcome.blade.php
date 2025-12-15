<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kaslyn - SaaS Keuangan UKM</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-b from-lime-300 to-emerald-500">
        <!-- Navbar -->
        <header class="sticky top-0 z-20 bg-white/20 backdrop-blur border-b border-white/30">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-white/80 flex items-center justify-center shadow">
                        <span class="font-extrabold text-emerald-600">K</span>
                    </div>
                    <div class="leading-tight">
                        <p class="text-white font-extrabold tracking-wide text-lg">Kaslyn</p>
                        <p class="text-white/80 text-xs">Platform Keuangan UKM Sederhana</p>
                    </div>
                </div>

                <!-- Right nav -->
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-4 py-2 rounded-full bg-white text-emerald-700 font-semibold shadow hover:opacity-90 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 rounded-full bg-white/70 text-emerald-800 font-semibold hover:bg-white transition">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 rounded-full bg-gradient-to-r from-emerald-600 to-lime-500 text-white font-semibold shadow-lg shadow-emerald-600/30 hover:opacity-95 transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Hero -->
        <main class="max-w-7xl mx-auto px-6 pt-14 pb-16">
            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <!-- Left: text -->
                <div>
                    <p class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full border border-white/30">
                        <span class="h-2 w-2 rounded-full bg-white"></span>
                        SaaS berbasis Cloud • Pencatatan Keuangan UKM
                    </p>

                    <h1 class="mt-6 text-4xl md:text-5xl font-extrabold text-white leading-tight">
                        Kelola pemasukan & pengeluaran UKM <span class="text-white/90">tanpa ribet</span>.
                    </h1>

                    <p class="mt-5 text-white/90 text-base md:text-lg leading-relaxed max-w-xl">
                        <b>Kaslyn</b> membantu UKM non-akuntansi mencatat transaksi harian, melihat tren bulanan,
                        dan mendapatkan laporan sederhana seperti laba rugi minimalis. Semua aman, tersimpan di cloud,
                        dan bisa dipakai dengan sistem subscription.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="inline-flex justify-center px-6 py-3 rounded-full bg-white text-emerald-700 font-semibold shadow hover:opacity-90 transition">
                                Masuk ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                               class="inline-flex justify-center px-6 py-3 rounded-full bg-gradient-to-r from-emerald-600 to-lime-500 text-white font-semibold shadow-lg shadow-emerald-600/30 hover:opacity-95 transition">
                                Coba Gratis / Daftar
                            </a>
                            <a href="{{ route('login') }}"
                               class="inline-flex justify-center px-6 py-3 rounded-full bg-white/70 text-emerald-800 font-semibold hover:bg-white transition">
                                Saya sudah punya akun
                            </a>
                        @endauth
                    </div>

                    <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="bg-white/20 border border-white/30 rounded-xl p-4">
                            <p class="text-white font-bold text-lg">Harian</p>
                            <p class="text-white/80 text-sm">Input transaksi cepat</p>
                        </div>
                        <div class="bg-white/20 border border-white/30 rounded-xl p-4">
                            <p class="text-white font-bold text-lg">Instan</p>
                            <p class="text-white/80 text-sm">Grafik tren bulanan</p>
                        </div>
                        <div class="bg-white/20 border border-white/30 rounded-xl p-4">
                            <p class="text-white font-bold text-lg">SaaS</p>
                            <p class="text-white/80 text-sm">Subscription per bulan</p>
                        </div>
                        <div class="bg-white/20 border border-white/30 rounded-xl p-4">
                            <p class="text-white font-bold text-lg">Aman</p>
                            <p class="text-white/80 text-sm">Tersimpan di cloud</p>
                        </div>
                    </div>
                </div>

                <!-- Right: feature cards -->
                <div class="grid gap-4">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="font-bold text-emerald-700 text-lg">1) Pencatatan Transaksi Sederhana</h3>
                        <p class="mt-2 text-slate-600">
                            Catat pemasukan & pengeluaran harian dengan form yang ringan dan mudah dipahami.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="font-bold text-emerald-700 text-lg">2) Pelaporan Instan</h3>
                        <p class="mt-2 text-slate-600">
                            Lihat ringkasan dan grafik tren bulanan, termasuk tampilan laba-rugi versi minimalis.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="font-bold text-emerald-700 text-lg">3) Integrasi Pembayaran (Midtrans)</h3>
                        <p class="mt-2 text-slate-600">
                            Subscription bulanan dan pencatatan transaksi otomatis melalui pembayaran terintegrasi.
                        </p>
                    </div>

                    <div class="bg-white/20 border border-white/30 rounded-2xl p-6">
                        <p class="text-white font-semibold">Untuk siapa Kaslyn?</p>
                        <p class="mt-2 text-white/90">
                            UKM yang belum punya sistem akuntansi, tapi butuh pembukuan rapi & cepat untuk monitoring.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-16 text-center text-white/90 text-sm">
                © {{ date('Y') }} Kaslyn. All rights reserved.
            </footer>
        </main>
    </div>
</body>
</html>

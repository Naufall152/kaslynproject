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

            {{-- error validasi --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Plans --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- BASIC --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                Paket Basic
                            </span>
                            <h3 class="mt-3 text-xl font-extrabold text-slate-900">Basic</h3>
                            <p class="mt-1 text-sm text-slate-500">Untuk UKM yang baru mulai.</p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm text-slate-500">Mulai dari</p>
                            <p class="text-2xl font-extrabold text-emerald-700">
                                Rp 0<span class="text-sm font-semibold text-slate-500">/bln</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-widest text-slate-500 font-semibold">Fitur</p>
                        <ul class="mt-3 text-sm text-slate-700 space-y-2">
                            <li class="flex items-center gap-2">
                                <span
                                    class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs">✓</span>
                                Pencatatan transaksi <b>maks. 50 transaksi/bulan</b>
                            </li>
                            <li class="flex items-center gap-2">
                                <span
                                    class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs">✓</span>
                                Laporan keuangan <b>harian</b>
                            </li>
                            <li class="flex items-center gap-2">
                                <span
                                    class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-red-100 text-red-700 text-xs">✕</span>
                                Integrasi Midtrans <b>tidak tersedia</b>
                            </li>
                        </ul>
                    </div>

                    {{-- NOTE: sekarang bukan submit subscription, tapi bayar via modal --}}
                    <form method="POST" action="{{ route('subscriptions.basic') }}" class="mt-6">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
                            Aktifkan Basic
                        </button>
                    </form>

                    <p class="mt-3 text-xs text-slate-500">
                        Basic langsung aktif tanpa pembayaran.
                    </p>

                </div>

                {{-- PRO --}}
                <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-6 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-36 h-36 bg-emerald-100 rounded-full"></div>

                    <div class="flex items-start justify-between relative">
                        <div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
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
                                <span
                                    class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-200 text-emerald-800 text-xs">✓</span>
                                Pencatatan transaksi <b>unlimited</b>
                            </li>
                            <li class="flex items-center gap-2">
                                <span
                                    class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-200 text-emerald-800 text-xs">✓</span>
                                Laporan keuangan <b>bulanan & tahunan</b>
                            </li>
                            <li class="flex items-center gap-2">
                                <span
                                    class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-200 text-emerald-800 text-xs">✓</span>
                                Integrasi Midtrans <b>tersedia</b>
                            </li>
                        </ul>
                    </div>

                    <button type="button" onclick="startPayment('pro', 50000)"
                        class="mt-6 w-full px-4 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
                        Pilih Pro
                    </button>
                </div>

            </div>

            <p class="mt-6 text-xs text-slate-500">
                Catatan: Pembayaran dilakukan melalui Midtrans (QRIS). Setelah bayar, klik "Cek Status Pembayaran".
            </p>

        </div>
    </div>

    {{-- MIDTRANS SNAP JS (SANDBOX) --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    {{-- MODAL BAYAR --}}
    <div id="payModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 p-4 z-50">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="p-5 border-b flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-widest text-emerald-600">Tagihan</p>
                    <h3 class="text-lg font-bold text-slate-900" id="modalTitle">Pembayaran</h3>
                    <p class="text-sm text-slate-500" id="modalSubtitle"></p>
                </div>
                <button type="button" onclick="closePayModal()" class="text-slate-500 hover:text-slate-700">✕</button>
            </div>

            <div class="p-5 flex-1 overflow-y-auto">
                {{-- Snap embed container --}}
                <div id="snapContainer" class="rounded-xl border border-slate-200 overflow-hidden min-h-[320px]"></div>

                <div id="statusBox" class="mt-4 hidden rounded-xl border px-4 py-3 text-sm"></div>
            </div>

            <div class="p-5 border-t bg-white">
                <div class="flex gap-2">
                    <button id="btnCheck" type="button" onclick="checkPaymentStatus()"
                        class="flex-1 px-4 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                        Cek Status Pembayaran
                    </button>
                    <button type="button" onclick="closePayModal()"
                        class="px-4 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50">
                        Tutup
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        let currentOrderId = null;

        function openPayModal(title, subtitle) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalSubtitle').innerText = subtitle;

            const modal = document.getElementById('payModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePayModal() {
            const modal = document.getElementById('payModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.getElementById('snapContainer').innerHTML = '';
            document.getElementById('statusBox').classList.add('hidden');

            currentOrderId = null;
        }

        async function startPayment(plan, amount) {
            openPayModal(`Tagihan Paket ${plan.toUpperCase()}`, `Total: Rp ${amount.toLocaleString('id-ID')}`);
            showStatus('Membuat tagihan pembayaran...', 'warn');

            let res;
            try {
                res = await fetch("{{ route('midtrans.pay') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        plan
                    }),
                });
            } catch (e) {
                showStatus('Gagal request ke server (cek koneksi / server Laravel).', 'error');
                return;
            }

            const contentType = res.headers.get('content-type') || '';

            // Kalau ternyata kena redirect / dapat HTML, tampilkan pesan jelas
            if (!contentType.includes('application/json')) {
                const text = await res.text();
                console.error('Response bukan JSON:', text);
                showStatus(
                    'Server tidak mengembalikan JSON. Biasanya karena route kena middleware/redirect. Cek routes midtrans.pay.',
                    'error');
                return;
            }

            const data = await res.json();

            if (!res.ok || !data.snap_token) {
                console.error('midtrans.pay response:', data);
                showStatus(data.message ?? 'Gagal membuat tagihan (snap_token kosong). Cek laravel.log.', 'error');
                return;
            }

            currentOrderId = data.order_id;

            // Biar container Snap nggak "kecil" / blank
            document.getElementById('snapContainer').style.minHeight = '520px';

            window.snap.embed(data.snap_token, {
                embedId: 'snapContainer',

                onSuccess: async function(result) {
                    // Pembayaran sukses
                    showStatus('Pembayaran berhasil. Mengaktifkan akun PRO...', 'success');

                    // cek status ke backend (biar subscription aktif)
                    await checkPaymentStatus();

                    // reload supaya badge BASIC → PRO langsung berubah
                    setTimeout(() => {
                        window.location.href = "{{ route('dashboard') }}";
                    }, 1000);
                },

                onPending: function(result) {
                    showStatus(
                        'Pembayaran masih pending. Selesaikan pembayaran lalu klik "Cek Status Pembayaran".',
                        'warn'
                    );
                },

                onError: function(result) {
                    showStatus('Pembayaran gagal. Silakan ulangi pembayaran.', 'error');
                },

                onClose: function() {
                    showStatus(
                        'Popup pembayaran ditutup. Kamu bisa lanjutkan nanti.',
                        'warn'
                    );
                }
            });


            showStatus('Silakan pilih QRIS dan scan barcode yang muncul.', 'warn');
        }

        async function checkPaymentStatus() {
            if (!currentOrderId) {
                showStatus('Order belum dibuat. Silakan pilih paket dulu.', 'warn');
                return;
            }

            const res = await fetch("{{ route('midtrans.check') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_id: currentOrderId
                })
            });

            if (!res.ok) {
                showStatus('Gagal cek status pembayaran. Pastikan route midtrans.check ada.', 'error');
                return;
            }

            const data = await res.json();

            if (data.paid) {
                document.getElementById('snapContainer').innerHTML = `
                <div class="p-6 text-center">
                    <div class="text-emerald-700 font-extrabold text-lg">Pembayaran Berhasil ✅</div>
                    <div class="mt-1 text-slate-600">${data.message}</div>
                </div>
            `;
                showStatus(data.message, 'success');
            } else {
                showStatus(data.message, 'warn');
            }
        }

        function showStatus(message, type) {
            const box = document.getElementById('statusBox');
            box.classList.remove('hidden');

            if (type === 'success') {
                box.className =
                    "mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800";
            } else if (type === 'error') {
                box.className = "mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800";
            } else {
                box.className = "mt-4 rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800";
            }

            box.innerText = message;
        }
    </script>
</x-app-layout>

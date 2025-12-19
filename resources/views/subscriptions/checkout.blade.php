<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900">
            Pembayaran Langganan Pro
        </h2>
    </x-slot>

    <div class="py-10 text-center">
        <button id="pay-button"
            class="px-6 py-3 bg-emerald-600 text-white rounded-xl font-semibold">
            Bayar Sekarang
        </button>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $snapToken }}');
        };
    </script>
</x-app-layout>

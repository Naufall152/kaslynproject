<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-widest text-emerald-600">Kaslyn</p>
                <h2 class="font-semibold text-xl text-slate-900">Laporan Laba Rugi</h2>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('reports.export_csv', ['month' => $month]) }}"
                   class="px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-50">
                    Export CSV
                </a>
                <a href="{{ route('reports.export_pdf', ['month' => $month]) }}"
                   class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                    Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <form method="GET" class="flex items-center gap-2">
                    <input type="month" name="month" value="{{ $month }}"
                           class="rounded-lg border-gray-200 text-sm focus:border-emerald-400 focus:ring-emerald-400"/>
                    <button class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                        Terapkan
                    </button>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="p-5 rounded-xl bg-emerald-50 border border-emerald-100">
                        <p class="text-sm text-emerald-700">Pemasukan</p>
                        <p class="text-2xl font-bold text-emerald-800">Rp {{ number_format($income,0,',','.') }}</p>
                    </div>

                    <div class="p-5 rounded-xl bg-red-50 border border-red-100">
                        <p class="text-sm text-red-700">Pengeluaran</p>
                        <p class="text-2xl font-bold text-red-800">Rp {{ number_format($expense,0,',','.') }}</p>
                    </div>

                    <div class="p-5 rounded-xl bg-slate-50 border border-slate-200">
                        <p class="text-sm text-slate-700">Laba / Rugi</p>
                        <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($profit,0,',','.') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

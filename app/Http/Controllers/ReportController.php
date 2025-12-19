<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;

        // basic|pro|null (kalau belum subscribe bisa null)
        $plan = $user->activePlan();

        /**
         * BASIC: ringkasan & chart = hari ini
         * PRO  : ringkasan = bulan terpilih, chart = 6 bulan terakhir
         */
        if ($plan === 'basic') {
            $start = now()->startOfDay();
            $end   = now()->endOfDay();

            // untuk kebutuhan view saja (kalau kamu masih pakai input month di blade)
            $month = now()->format('Y-m');

            // Chart basic hanya 1 titik (hari ini)
            $chartLabels = [now()->format('d M Y')];

            $todayIncome = (float) Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('amount');

            $todayExpense = (float) Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('amount');

            $chartIncome  = [$todayIncome];
            $chartExpense = [$todayExpense];
        } else {
            // PRO (atau null) → boleh pilih bulan
            $month = $request->get('month', now()->format('Y-m'));

            try {
                [$year, $mon] = explode('-', $month);
                $start = Carbon::createFromDate((int) $year, (int) $mon, 1)->startOfDay();
                $end   = $start->copy()->endOfMonth()->endOfDay();
            } catch (\Throwable $e) {
                $month = now()->format('Y-m');
                $start = now()->startOfMonth()->startOfDay();
                $end   = now()->endOfMonth()->endOfDay();
            }

            // Chart: 6 bulan terakhir
            $months = collect(range(0, 5))->map(fn ($i) => now()->subMonths(5 - $i));

            $chartLabels = [];
            $chartIncome = [];
            $chartExpense = [];

            foreach ($months as $m) {
                $s = $m->copy()->startOfMonth()->startOfDay();
                $e = $m->copy()->endOfMonth()->endOfDay();

                $chartLabels[] = $m->format('M Y');

                $chartIncome[] = (float) Transaction::where('user_id', $userId)
                    ->where('type', 'income')
                    ->whereBetween('transaction_date', [$s, $e])
                    ->sum('amount');

                $chartExpense[] = (float) Transaction::where('user_id', $userId)
                    ->where('type', 'expense')
                    ->whereBetween('transaction_date', [$s, $e])
                    ->sum('amount');
            }
        }

        // Ringkasan mengikuti range start-end (basic=hari ini, pro=bukan)
        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $expense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $balance = $income - $expense;

        // Transaksi terbaru (tidak terikat filter)
        $latest = Transaction::where('user_id', $userId)
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'income',
            'expense',
            'balance',
            'latest',
            'month',
            'chartLabels',
            'chartIncome',
            'chartExpense',
            'plan',
            'start',
            'end'
        ));
    }

    /**
     * Reports - Daily (untuk basic & pro)
     * Route kamu: reports/daily
     */
    public function daily(Request $request)
    {
        $userId = $request->user()->id;

        $date = $request->get('date', now()->toDateString());
        try {
            $start = Carbon::parse($date)->startOfDay();
            $end   = Carbon::parse($date)->endOfDay();
        } catch (\Throwable $e) {
            $start = now()->startOfDay();
            $end   = now()->endOfDay();
            $date  = now()->toDateString();
        }

        $income = Transaction::where('user_id', $userId)->where('type', 'income')
            ->whereBetween('transaction_date', [$start, $end])->sum('amount');

        $expense = Transaction::where('user_id', $userId)->where('type', 'expense')
            ->whereBetween('transaction_date', [$start, $end])->sum('amount');

        $profit = $income - $expense;

        $rows = Transaction::where('user_id', $userId)
            ->whereBetween('transaction_date', [$start, $end])
            ->orderByDesc('transaction_date')
            ->get();

        return view('reports.daily', compact('date', 'start', 'end', 'income', 'expense', 'profit', 'rows'));
    }

    /**
     * Reports - Profit/Loss (PRO only)
     * Route kamu: reports/profit-loss
     */
    public function profitLoss(Request $request)
    {
        $userId = $request->user()->id;

        $month = $request->get('month', now()->format('Y-m'));
        try {
            [$year, $mon] = explode('-', $month);
            $start = Carbon::createFromDate((int) $year, (int) $mon, 1)->startOfDay();
            $end   = $start->copy()->endOfMonth()->endOfDay();
        } catch (\Throwable $e) {
            $month = now()->format('Y-m');
            $start = now()->startOfMonth()->startOfDay();
            $end   = now()->endOfMonth()->endOfDay();
        }

        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $expense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $profit = $income - $expense;

        $itemsIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$start, $end])
            ->orderByDesc('transaction_date')
            ->get();

        $itemsExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$start, $end])
            ->orderByDesc('transaction_date')
            ->get();

        return view('reports.profit_loss', compact(
            'month',
            'start',
            'end',
            'income',
            'expense',
            'profit',
            'itemsIncome',
            'itemsExpense'
        ));
    }

    /**
     * Reports - Yearly (PRO only)
     * Route kamu: reports/yearly
     */
    public function yearly(Request $request)
    {
        $userId = $request->user()->id;

        $year = (int) $request->get('year', now()->year);
        $start = Carbon::create($year, 1, 1)->startOfDay();
        $end   = Carbon::create($year, 12, 31)->endOfDay();

        $income = Transaction::where('user_id', $userId)->where('type', 'income')
            ->whereBetween('transaction_date', [$start, $end])->sum('amount');

        $expense = Transaction::where('user_id', $userId)->where('type', 'expense')
            ->whereBetween('transaction_date', [$start, $end])->sum('amount');

        $profit = $income - $expense;

        return view('reports.yearly', compact('year', 'start', 'end', 'income', 'expense', 'profit'));
    }

    public function exportCsv(Request $request)
    {
        $userId = $request->user()->id;

        $month = $request->get('month', now()->format('Y-m'));
        try {
            [$year, $mon] = explode('-', $month);
            $start = Carbon::createFromDate((int) $year, (int) $mon, 1)->startOfDay();
            $end   = $start->copy()->endOfMonth()->endOfDay();
        } catch (\Throwable $e) {
            $month = now()->format('Y-m');
            $start = now()->startOfMonth()->startOfDay();
            $end   = now()->endOfMonth()->endOfDay();
        }

        $rows = Transaction::where('user_id', $userId)
            ->whereBetween('transaction_date', [$start, $end])
            ->orderBy('transaction_date')
            ->get(['transaction_date', 'type', 'category', 'description', 'amount']);

        $filename = "kaslyn-report-{$month}-user{$userId}.csv";

        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, ['Tanggal', 'Tipe', 'Kategori', 'Deskripsi', 'Jumlah']);

        foreach ($rows as $r) {
            fputcsv($handle, [
                $r->transaction_date,
                $r->type,
                $r->category,
                $r->description,
                $r->amount,
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        Storage::disk('local')->put("reports/{$filename}", $csvContent);

        if (config('filesystems.disks.azure')) {
            Storage::disk('azure')->put("reports/{$filename}", $csvContent);
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    public function exportPdf(Request $request)
    {
        $userId = $request->user()->id;

        $month = $request->get('month', now()->format('Y-m'));
        try {
            [$year, $mon] = explode('-', $month);
            $start = Carbon::createFromDate((int) $year, (int) $mon, 1)->startOfDay();
            $end   = $start->copy()->endOfMonth()->endOfDay();
        } catch (\Throwable $e) {
            $month = now()->format('Y-m');
            $start = now()->startOfMonth()->startOfDay();
            $end   = now()->endOfMonth()->endOfDay();
        }

        $income = Transaction::where('user_id', $userId)->where('type', 'income')
            ->whereBetween('transaction_date', [$start, $end])->sum('amount');

        $expense = Transaction::where('user_id', $userId)->where('type', 'expense')
            ->whereBetween('transaction_date', [$start, $end])->sum('amount');

        $profit = $income - $expense;

        $rows = Transaction::where('user_id', $userId)
            ->whereBetween('transaction_date', [$start, $end])
            ->orderBy('transaction_date')
            ->get();

        $pdf = Pdf::loadView('reports.pdf', compact(
            'month',
            'start',
            'end',
            'income',
            'expense',
            'profit',
            'rows'
        ))
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $filename = "kaslyn-report-{$month}-user{$userId}.pdf";
        $pdfBinary = $pdf->output();

        Storage::disk('local')->put("reports/{$filename}", $pdfBinary);

        if (config('filesystems.disks.azure')) {
            Storage::disk('azure')->put("reports/{$filename}", $pdfBinary);
        }

        return $pdf->download($filename);
    }
}

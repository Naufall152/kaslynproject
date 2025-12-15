<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $userId = $request->user()->id;

        // filter bulan (opsional)
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $start = Carbon::createFromDate((int) $year, (int) $mon, 1)->startOfDay();
        $end = $start->copy()->endOfMonth()->endOfDay();

        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $expense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $balance = $income - $expense;

        // Chart: tren 6 bulan terakhir
        $months = collect(range(0, 5))->map(function ($i) {
            return now()->subMonths(5 - $i)->format('Y-m');
        });

        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];

        foreach ($months as $m) {
            [$y, $mo] = explode('-', $m);
            $s = Carbon::createFromDate((int) $y, (int) $mo, 1)->startOfDay();
            $e = $s->copy()->endOfMonth()->endOfDay();

            $chartLabels[] = $s->format('M Y');

            $chartIncome[] = (float) Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('transaction_date', [$s, $e])
                ->sum('amount');

            $chartExpense[] = (float) Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$s, $e])
                ->sum('amount');
        }

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
            'chartExpense'
        ));
    }

    public function profitLoss(Request $request)
    {
        $userId = $request->user()->id;
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $start = Carbon::createFromDate((int) $year, (int) $mon, 1)->startOfDay();
        $end = $start->copy()->endOfMonth()->endOfDay();

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

    public function exportCsv(Request $request)
    {
        $userId = $request->user()->id;
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $start = Carbon::createFromDate((int) $year, (int) $mon, 1)->startOfDay();
        $end = $start->copy()->endOfMonth()->endOfDay();

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

        // Simpan lokal (storage/app/reports)
        Storage::disk('local')->put("reports/{$filename}", $csvContent);

        // Upload ke Azure Blob (kalau sudah diset)
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
        [$year, $mon] = explode('-', $month);

        $start = Carbon::createFromDate((int) $year, (int) $mon, 1)->startOfDay();
        $end = $start->copy()->endOfMonth()->endOfDay();

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

        // Simpan lokal
        Storage::disk('local')->put("reports/{$filename}", $pdfBinary);

        // Upload Azure Blob (kalau disk azure sudah diset)
        if (config('filesystems.disks.azure')) {
            Storage::disk('azure')->put("reports/{$filename}", $pdfBinary);
        }

        return $pdf->download($filename);
    }

}

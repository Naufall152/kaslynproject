<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Kaslyn {{ $month }}</title>

    <style>
        @page { margin: 24px; }

        /* PENTING: paksa semua elemen pakai DejaVu Sans */
        * {
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-size: 12px;
            color: #111827;
            line-height: 1.35;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .muted {
            color: #6b7280;
            margin-bottom: 12px;
        }

        .summary p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 7px 8px;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            font-weight: 700;
        }

        .right { text-align: right; }
    </style>
</head>

<body>
    <div class="title">Laporan Kaslyn - {{ $month }}</div>
    <div class="muted">Periode: {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</div>

    <div class="summary">
        <p><b>Pemasukan:</b> Rp {{ number_format($income, 0, ',', '.') }}</p>
        <p><b>Pengeluaran:</b> Rp {{ number_format($expense, 0, ',', '.') }}</p>
        <p><b>Laba/Rugi:</b> Rp {{ number_format($profit, 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 16%">Tanggal</th>
                <th style="width: 14%">Tipe</th>
                <th style="width: 18%">Kategori</th>
                <th>Deskripsi</th>
                <th class="right" style="width: 18%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $r)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($r->transaction_date)->format('d M Y') }}</td>
                    <td>{{ $r->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                    <td>{{ $r->category ?? '-' }}</td>
                    <td>{{ $r->description ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($r->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#6b7280; padding:16px;">
                        Tidak ada transaksi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Absensi - {{ $months[$month] }} {{ $year }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            background: #fff;
            margin: 20px;
            font-size: 13px;
            line-height: 1.4;
        }

        .print-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .print-title {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            color: #000;
        }

        .print-subtitle {
            font-size: 14px;
            margin: 0;
            color: #555;
        }

        .print-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 11px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5 !important;
            color: #000;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #fafafb !important;
        }

        .total-row td {
            border-top: 2px solid #333;
            font-weight: bold;
            color: #000;
        }

        /* Tanda tangan / Approval footer */
        .signature-block {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            margin-top: 70px;
            border-top: 1px solid #333;
            font-weight: bold;
        }

        @media print {
            body {
                margin: 0;
                font-size: 12px;
            }
            .no-print {
                display: none !important;
            }
            th {
                background-color: #f5f5f5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .total-row {
                background-color: #fafafb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="print-header">
        <h1 class="print-title">Laporan Rekap Absensi Bulanan</h1>
        <h2 class="print-subtitle">Karyawan Gudang Kopi Elang Emas</h2>
    </div>

    <div class="print-meta">
        <div><strong>Periode:</strong> {{ $months[$month] }} {{ $year }} ({{ $daysInMonth }} Hari)</div>
        <div><strong>Tanggal Cetak:</strong> {{ date('d-m-Y H:i') }} oleh {{ auth()->user()->name }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;" class="text-center">No</th>
                <th>Nama Karyawan</th>
                <th style="width: 100px;">Status Karyawan</th>
                <th style="width: 80px;" class="text-center">Hadir</th>
                <th style="width: 80px;" class="text-center">Izin</th>
                <th style="width: 80px;" class="text-center">Sakit</th>
                <th style="width: 80px;" class="text-center">Alfa</th>
                <th style="width: 100px;" class="text-center">Belum Dicatat</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($recap as $row)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><strong>{{ $row['employee']->name }}</strong></td>
                    <td>{{ $row['employee']->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                    <td class="text-center">{{ $row['hadir'] }}</td>
                    <td class="text-center">{{ $row['izin'] }}</td>
                    <td class="text-center">{{ $row['sakit'] }}</td>
                    <td class="text-center">{{ $row['alfa'] }}</td>
                    <td class="text-center">{{ $row['belum_dicatat'] }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="3">TOTAL KESELURUHAN</td>
                <td class="text-center">{{ $totals['hadir'] }}</td>
                <td class="text-center">{{ $totals['izin'] }}</td>
                <td class="text-center">{{ $totals['sakit'] }}</td>
                <td class="text-center">{{ $totals['alfa'] }}</td>
                <td class="text-center">{{ $totals['belum_dicatat'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-block">
        <div class="signature-box">
            <p>Penanggung Jawab,</p>
            <div class="signature-line">
                {{ auth()->user()->name }}
            </div>
            <p style="font-size: 10px; margin-top: 4px; color: #666;">Administrator</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

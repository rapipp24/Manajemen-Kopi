<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pengambilan Barang - {{ $salesOrder->order_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 750px;
            margin: 0 auto;
            border: 1px dashed #ccc;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px double #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 14px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #000;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .meta-table td.label {
            width: 20%;
        }
        .meta-table td.separator {
            width: 2%;
        }
        .meta-table td.value {
            width: 28%;
            font-weight: bold;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .item-table th, .item-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }
        .item-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .item-table td.center {
            text-align: center;
        }
        .signatures {
            width: 100%;
            margin-top: 50px;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            height: 100px;
        }
        .no-print-bar {
            background: #f1f5f9;
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #0284c7;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13.5px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #0369a1;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .container {
                border: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print-bar {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <button class="btn-print" onclick="window.print()">Cetak Nota</button>
    </div>

    <div class="container">
        <div class="header">
            <h1>KOPI ELANG EMAS</h1>
            <h2>NOTA PENGAMBILAN BARANG SALES</h2>
            <p>ARSIP ADMIN</p>
        </div>

        <table class="meta-table">
            <tr>
                <td class="label">No. Pengajuan</td>
                <td class="separator">:</td>
                <td class="value"><code>{{ $salesOrder->order_number }}</code></td>
                
                <td class="label">Nama Sales</td>
                <td class="separator">:</td>
                <td class="value">{{ $salesOrder->sales->name }}</td>
            </tr>
            <tr>
                <td class="label">Tgl Pengajuan</td>
                <td class="separator">:</td>
                <td class="value">{{ $salesOrder->created_at->format('d-m-Y H:i') }}</td>
                
                <td class="label">Tujuan / Toko</td>
                <td class="separator">:</td>
                <td class="value">{{ $salesOrder->customer->name ?? 'Stok Pribadi / Keliling' }}</td>
            </tr>
            <tr>
                <td class="label">Tgl Disetujui</td>
                <td class="separator">:</td>
                <td class="value">{{ $salesOrder->processed_at ? $salesOrder->processed_at->format('d-m-Y H:i') : '-' }}</td>
                
                <td class="label">Status</td>
                <td class="separator">:</td>
                <td class="value" style="text-transform: uppercase;">{{ $salesOrder->status }}</td>
            </tr>
        </table>

        @if($salesOrder->catatan)
        <div style="margin-bottom: 20px; border: 1px dashed #000; padding: 10px;">
            <strong>Catatan Pengajuan:</strong><br>
            {{ $salesOrder->catatan }}
        </div>
        @endif

        <table class="item-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 20%;">Kode Barang</th>
                    <th style="width: 55%;">Nama Barang / Paket</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 10%; text-align: center;">Satuan</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                
                {{-- Produk Satuan --}}
                @foreach($salesOrder->items as $item)
                <tr>
                    <td class="center">{{ $no++ }}</td>
                    <td><code>{{ $item->product->code ?? '-' }}</code></td>
                    <td>{{ $item->product->name }} {{ $item->product->variant ?? '' }}</td>
                    <td class="center">{{ $item->qty }}</td>
                    <td class="center">{{ $item->product->unit->name ?? 'pcs' }}</td>
                </tr>
                @endforeach
                
                {{-- Paket --}}
                @foreach($salesOrder->packageItems as $pkgItem)
                <tr>
                    <td class="center">{{ $no++ }}</td>
                    <td><code>{{ $pkgItem->package->code ?? '-' }}</code></td>
                    <td>[PAKET] {{ $pkgItem->package->name }}</td>
                    <td class="center">{{ $pkgItem->qty }}</td>
                    <td class="center">pack</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="signatures">
            <tr>
                <td>
                    Diserahkan oleh,<br><br><br><br>
                    <strong>( Admin/Gudang )</strong>
                </td>
                <td>
                    Diterima oleh,<br><br><br><br>
                    <strong>( {{ $salesOrder->sales->name }} )</strong>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan {{ $sale->invoice_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        @page {
            size: A4;
            margin: 0;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 40px;
            background: #fff;
            line-height: 1.5;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 0;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 3px solid #0f172a;
            padding-bottom: 20px;
        }

        .brand-info h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -1px;
        }

        .brand-info p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .invoice-title .inv-number {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            font-family: monospace;
            margin-top: 4px;
        }

        .info-container {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .info-block h3 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
            font-size: 13px;
            vertical-align: top;
        }

        .label {
            color: #64748b;
            font-weight: 600;
            width: 100px;
        }

        .value {
            color: #1e293b;
            font-weight: 700;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .items-table td {
            padding: 15px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
        }

        .items-table tr:last-child td {
            border-bottom: 2px solid #e2e8f0;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 50px;
        }

        .summary-box {
            width: 300px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }

        .total-row {
            border-top: 2px solid #0f172a;
            margin-top: 8px;
            padding-top: 12px;
            font-weight: 800;
            font-size: 18px;
            color: #0f172a;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 100px;
            margin-top: 60px;
            text-align: center;
        }

        .signature-box p {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 60px;
        }

        .signature-line {
            border-top: 1px solid #1e293b;
            width: 180px;
            margin: 0 auto;
            font-weight: 700;
            padding-top: 5px;
            font-size: 13px;
        }

        .footer-note {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px dashed #cbd5e1;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            font-style: italic;
        }

        @media print {
            body { padding: 20px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 20px; right: 20px; z-index: 100;">
        <button onclick="window.print()" style="padding: 12px 24px; background: #0f172a; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            Cetak Sekarang
        </button>
    </div>

    <div class="invoice-box">
        <header class="header-section">
            <div class="brand-info">
                <h1>{{ $settings['shop_name'] }}</h1>
                <p>{{ $settings['shop_address'] }}</p>
                <p>Telp: {{ $settings['shop_phone'] }} | Email: {{ $settings['shop_email'] }}</p>
            </div>
            <div class="invoice-title">
                <h2>Invoice</h2>
                <div class="inv-number">{{ $sale->invoice_number }}</div>
            </div>
        </header>

        <section class="info-container">
            <div class="info-block">
                <h3>Informasi Member</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="value">{{ $sale->customer->name ?? 'Umum' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Telepon</td>
                        <td class="value">{{ $sale->customer->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="value">{{ $sale->customer->address ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="info-block">
                <h3>Detail Transaksi</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Tanggal</td>
                        <td class="value">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td class="value">
                            @if($sale->payment_status === 'lunas') LUNAS
                            @elseif($sale->payment_status === 'sebagian') DP / SEBAGIAN
                            @else BELUM BAYAR
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Metode</td>
                        <td class="value">{{ strtoupper($sale->payment_method) }}</td>
                    </tr>
                </table>
            </div>
        </section>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="45%">Deskripsi Produk</th>
                    <th width="15%" class="text-right">Harga</th>
                    <th width="10%" class="text-center">Qty</th>
                    <th width="25%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: 700; color: #0f172a;">{{ $item->product->name ?? '-' }}</div>
                        @if($item->product->variant)
                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Varian: {{ $item->product->variant }}</div>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item->qty) }}</td>
                    <td class="text-right" style="font-weight: 600;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-section">
            <div class="summary-box">
                <div class="summary-row">
                    <span style="color: #64748b; font-weight: 600;">Total Item</span>
                    <span style="font-weight: 700;">{{ $sale->items->sum('qty') }} Pcs</span>
                </div>
                <div class="summary-row total-row">
                    <span>GRAND TOTAL</span>
                    <span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div style="font-size: 13px; color: #1e293b; margin-top: 20px;">
            <strong>Catatan:</strong><br>
            {{ $sale->note ?: 'Tidak ada catatan tambahan.' }}
        </div>

        <section class="signature-section">
            <div class="signature-box">
                <p>Member,</p>
                <div class="signature-line">{{ $sale->customer->name ?? '.......................' }}</div>
            </div>
            <div class="signature-box">
                <p>Hormat Kami,</p>
                <div class="signature-line">{{ $sale->creator->name ?? 'Administrator' }}</div>
            </div>
        </section>

        <footer class="footer-note">
            {{ $settings['footer_note'] }}
        </footer>
    </div>
</body>
</html>

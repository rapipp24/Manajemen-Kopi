<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan {{ $sale->invoice_number }}</title>
    <style>
        /* ── CONFIG UNTUK EPSON LX-310 DOT MATRIX CONTINUOUS FORM ── */
        @page {
            size: auto;
            margin: 0.4cm 0.4cm 0.4cm 0.4cm;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 10px;
            line-height: 1.3;
        }

        .invoice-box {
            max-width: 680px; /* Cocok untuk lebar continuous form 9.5 inci (sekitar 76 char per baris) */
            margin: 0;
            padding: 0;
        }

        /* Header Section */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-table td {
            padding: 0;
            vertical-align: top;
        }

        .brand-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .brand-sub {
            font-size: 10.5px;
        }

        /* Screen-only: tampil di browser, disembunyikan saat print LX */
        .screen-only {
            display: block;
        }

        .invoice-title {
            text-align: right;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .invoice-num {
            font-size: 12px;
            font-weight: bold;
        }

        /* Divider lines menggunakan border tipis standar */
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .double-divider {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            height: 2px;
            margin: 6px 0;
        }

        /* Info Grid - 2 Column Table untuk Spasial Efisien */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10.5px;
        }

        .info-label {
            width: 80px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .items-table th {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 4px;
            font-size: 10.5px;
            font-weight: bold;
            text-align: left;
        }

        .items-table td {
            padding: 4px;
            font-size: 10.5px;
            border-bottom: 1px dashed #ccc; /* Garis pembantu antar item — cukup terlihat saat print */
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        /* Summary Section */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .summary-table td {
            padding: 2px 4px;
            font-size: 11px;
        }

        .total-label {
            font-weight: bold;
            font-size: 12px;
        }

        .total-val {
            font-weight: bold;
            font-size: 12px;
        }

        /* Signature Blocks */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signature-table td {
            text-align: center;
            width: 50%;
            font-size: 11px;
            padding-bottom: 45px; /* Spasi untuk tanda tangan fisik */
        }

        .signature-line {
            width: 160px;
            margin: 0 auto;
            border-top: 1px solid #000;
            font-weight: bold;
            padding-top: 2px;
        }

        .footer-note {
            margin-top: 25px;
            text-align: center;
            font-size: 9.5px;
            border-top: 1px dashed #000;
            padding-top: 6px;
            font-style: italic;
        }

        /* ── RESPONSIVE PREVIEW KHUSUS MOBILE ── */
        @media screen and (max-width: 768px) {
            body {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
                padding: 16px !important;
                background: #f1f5f9;
            }
            .invoice-box {
                width: 680px; /* Jaga lebar tetap agar layout continuous form tidak pecah */
                background: white;
                padding: 24px;
                border: 1px solid #cbd5e1;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                border-radius: 8px;
                margin: 0 auto;
            }
        }

        /* ── CSS KHUSUS PRINT ── */
        @media print {
            body {
                padding: 0;
                margin: 0;
                background: white !important;
            }
            .invoice-box {
                width: 100% !important;
                max-width: 680px !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                background: white !important;
            }
            .no-print {
                display: none !important;
            }
            /* Sembunyikan elemen screen-only saat print LX */
            .screen-only {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Tombol Aksi di Layar Monitor (Disembunyikan saat dicetak) -->
    <div class="no-print" style="position: fixed; top: 20px; right: 20px; z-index: 100;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #92400e; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; font-family: monospace; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            CETAK NOTA (PRINT)
        </button>
    </div>

    <div class="invoice-box">
        <!-- Header Usaha -->
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    {{-- Screen-only preview logo (tidak muncul saat print LX dot matrix) --}}
                    <div class="screen-only" style="margin-bottom: 6px;">
                        <img src="{{ asset('images/LOGO-KOPI-ELANG-EMAS.jpg') }}"
                             alt="Kopi Elang Emas"
                             style="max-height: 36px; max-width: 40px; object-fit: contain; border-radius: 3px; display: block;"
                             onerror="this.style.display='none';">
                    </div>
                    <div class="brand-name">{{ $settings['shop_name'] }}</div>
                    <div class="brand-sub">{{ $settings['shop_address'] }}</div>
                    <div class="brand-sub">Telp: {{ $settings['shop_phone'] }}</div>
                </td>
                <td style="width: 40%; text-align: right;">
                    <div class="invoice-title">{{ \App\Models\Setting::get('receipt_title', 'INVOICE PENJUALAN') }}</div>
                    <div class="invoice-num">{{ $sale->invoice_number }}</div>
                </td>
            </tr>
        </table>

        {{-- Divider ganda setelah header — lebih tegas untuk pemisah di kertas continuous form --}}
        <div class="double-divider"></div>

        <!-- Informasi Transaksi & Pelanggan -->
        <table class="info-table">
            <tr>
                <!-- Kolom Kiri: Informasi Pelanggan -->
                <td style="width: 50%;">
                    <table>
                        <tr>
                            <td class="info-label" style="color: #444;">Pelanggan:</td>
                            <td style="font-weight: bold;">{{ $sale->customer->name ?? $sale->customer_name }}</td>
                        </tr>
                        <tr>
                            <td class="info-label" style="color: #444;">Telepon  :</td>
                            <td>{{ $sale->customer->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label" style="color: #444;">Alamat   :</td>
                            <td>{{ $sale->customer->address ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <!-- Kolom Kanan: Detail Transaksi -->
                <td style="width: 50%;">
                    <table style="margin-left: auto;">
                        <tr>
                            <td class="info-label" style="color: #444;">Tanggal :</td>
                            <td style="font-weight: bold;">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="info-label" style="color: #444;">Metode  :</td>
                            <td style="font-weight: bold;">{{ strtoupper($sale->payment_method) }}</td>
                        </tr>
                        <tr>
                            <td class="info-label" style="color: #444;">Status  :</td>
                            <td style="font-weight: bold;">
                                @if($sale->payment_status === 'lunas') LUNAS
                                @elseif($sale->payment_status === 'sebagian') DP/SEBAGIAN
                                @else BELUM BAYAR
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Rincian Produk Dipesan -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 45%;">Nama Produk</th>
                    <th style="width: 15%; text-align: right;">Harga</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 25%; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <span style="font-weight: bold;">{{ $item->product->name ?? '-' }}</span>
                        @if($item->product->variant)
                            <span style="font-size: 9.5px; color: #555;">({{ $item->product->variant }})</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item->qty) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <!-- Bagian Summary & Total -->
        <table class="summary-table">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <strong>Catatan:</strong> {{ $sale->note ?: 'Tidak ada.' }}
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="text-align: right; color: #444;">Total Qty:</td>
                            <td style="text-align: right; font-weight: bold; width: 100px;">{{ $sale->items->sum('qty') }} Pcs</td>
                        </tr>
                        <tr>
                            <td class="total-label" style="text-align: right;">GRAND TOTAL:</td>
                            <td class="total-val" style="text-align: right;">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Area Tanda Tangan Administratif -->
        <table class="signature-table">
            <tr>
                <td>
                    {{ $settings['receipt_left_signature_label'] ?? 'Penerima / Member' }}
                </td>
                <td>
                    {{ $settings['receipt_right_signature_label'] ?? 'Hormat Kami,' }}
                </td>
            </tr>
            <tr>
                <td>
                    <div class="signature-line">{{ $sale->customer->name ?? '.......................' }}</div>
                </td>
                <td>
                    <div class="signature-line">{{ $settings['receipt_right_signature_name'] ?? 'Administrator' }}</div>
                </td>
            </tr>
        </table>

        <!-- Catatan Kaki Nota -->
        <footer class="footer-note">
            @if(\App\Models\Setting::get('receipt_thank_you_text'))
                <div style="margin-bottom: 2px; font-weight: bold; text-transform: uppercase;">{{ \App\Models\Setting::get('receipt_thank_you_text') }}</div>
            @endif
            {{ $settings['footer_note'] }}
        </footer>
    </div>
</body>
</html>

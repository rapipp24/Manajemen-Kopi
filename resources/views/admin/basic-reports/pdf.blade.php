<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} · Cetak Laporan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 40px;
            font-size: 13px;
            background: #fff;
        }

        .pdf-header {
            border-bottom: 2.5px solid #6B2E16;
            padding-bottom: 18px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        /* Logo + company name side by side */
        .company-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .company-logo {
            flex-shrink: 0;
            max-height: 48px;
            max-width: 54px;
            width: auto;
            object-fit: contain;
            border-radius: 4px;
            display: block;
        }

        .company-info h1 {
            margin: 0 0 4px 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.4px;
            color: #3a1a09;
        }

        .company-info p {
            margin: 0;
            font-size: 11px;
            color: #7a5a45;
        }

        .report-title {
            text-align: right;
        }

        .report-title h2 {
            margin: 0 0 5px 0;
            font-size: 17px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .report-title p {
            margin: 0;
            font-size: 11px;
            color: #666;
        }

        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            page-break-inside: auto;
        }

        .pdf-table tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        .pdf-table th {
            background: #f5f5f5;
            border-top: 1px solid #ddd;
            border-bottom: 2px solid #333;
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .pdf-table td {
            border-bottom: 1px solid #eee;
            padding: 10px 12px;
            font-size: 12px;
            color: #222;
        }

        /* Status badge plain for print */
        .badge {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
        }

        .pdf-footer {
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .signature-block {
            text-align: center;
            width: 200px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 6px;
            font-weight: bold;
            font-size: 12px;
        }

        /* ── CSS FOR PRINTING ── */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            .no-print {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            table {
                page-break-inside: auto;
            }

            .pdf-footer {
                page-break-inside: avoid;
                break-inside: avoid;
                margin-top: 48px;
            }

            .signature-block {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Petunjuk Cetak (Hanya tampil di screen browser, tidak ikut tercetak) -->
    <div class="no-print" style="background: #fffbeb; border: 1.5px solid #fde68a; color: #b45309; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; display: flex; align-items: center; gap: 10px; line-height: 1.5; font-family: system-ui, -apple-system, sans-serif; box-shadow: 0 2px 8px rgba(180, 83, 9, 0.05);">
        <svg style="width: 20px; height: 20px; flex-shrink: 0; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span><strong>Petunjuk Cetak:</strong> Jika URL, tanggal, atau nomor halaman muncul saat dicetak, silakan nonaktifkan opsi <strong>Headers and footers</strong> di dialog print browser Anda.</span>
    </div>

    <!-- Header Dokumen -->
    <div class="pdf-header">
        <div class="company-brand">
            {{-- Logo resmi Kopi Elang Emas --}}
            <img src="{{ asset('images/LOGO-KOPI-ELANG-EMAS.jpg') }}"
                 alt="Kopi Elang Emas"
                 class="company-logo"
                 onerror="this.style.display='none';">
            <div class="company-info">
                <h1>{{ \App\Models\Setting::get('report_header_name', 'Kopi Elang Emas') }}</h1>
                <p>{{ \App\Models\Setting::get('report_subtitle', 'Manajemen Kopi & Produksi Terintegrasi') }}</p>
            </div>
        </div>
        <div class="report-title">
            <h2>{{ $title }}</h2>
            <p>
                @if($type === 'stock')
                    Real-Time: {{ now()->format('d-m-Y H:i') }}
                @else
                    Periode: {{ $startDate->format('d-m-Y') }} s/d {{ $endDate->format('d-m-Y') }}
                @endif
            </p>
        </div>
    </div>

    <!-- Data Table -->
    
    <!-- 1. Bahan Baku -->
    @if($type === 'raw_material')
        <table class="pdf-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 30%;">Supplier</th>
                    <th style="width: 25%;">Bahan Baku</th>
                    <th style="width: 15%; text-align: right;">Qty Diterima</th>
                    <th style="width: 15%; text-align: right;">Total Pembelian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rawMaterials as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->receipt->receipt_date)->format('d-m-Y') }}</td>
                        <td><strong>{{ $item->receipt->supplier->name ?? '—' }}</strong></td>
                        <td>{{ $item->rawMaterial->name ?? '—' }}</td>
                        <td style="text-align: right;">{{ number_format($item->qty, 0, ',', '.') }} {{ $item->rawMaterial->unit->code ?? '' }}</td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #888;">Belum ada data pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- 2. Produksi Kopi -->
    @if($type === 'production')
        <table class="pdf-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 20%;">Nomor Batch</th>
                    <th style="width: 35%;">Bahan Digunakan</th>
                    <th style="width: 15%; text-align: right;">Hasil Output</th>
                    <th style="width: 15%; text-align: right;">Susut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productions as $batch)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($batch->production_date)->format('d-m-Y') }}</td>
                        <td><strong>{{ $batch->batch_number }}</strong></td>
                        <td style="font-size: 11px;">
                            @foreach($batch->items as $item)
                                • {{ $item->rawMaterial->name ?? '—' }}: {{ number_format($item->qty_used, 0, ',', '.') }} {{ $item->rawMaterial->unit->code ?? '' }}<br>
                            @endforeach
                        </td>
                        <td style="text-align: right; font-weight: 700;">{{ number_format($batch->total_output, 0, ',', '.') }} gr</td>
                        <td style="text-align: right; color: #777;">{{ number_format($batch->shrinkage, 0, ',', '.') }} gr</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #888;">Belum ada data pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- 3. Stok Aktual -->
    @if($type === 'stock')
        <table class="pdf-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Tipe Item</th>
                    <th style="width: 45%;">Nama Item</th>
                    <th style="width: 15%; text-align: right;">Stok Saat Ini</th>
                    <th style="width: 15%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rawMaterialsStock as $r)
                    @php
                        if ($r->current_stock <= 0) {
                            $statusColor = '#dc2626';
                            $statusText = 'HABIS';
                        } elseif ($r->current_stock <= $r->minimum_stock) {
                            $statusColor = '#d97706';
                            $statusText = 'HAMPIR HABIS';
                        } else {
                            $statusColor = '#059669';
                            $statusText = 'AMAN';
                        }
                    @endphp
                    <tr>
                        <td>Bahan Baku</td>
                        <td><strong>{{ $r->name }}</strong></td>
                        <td style="text-align: right;">{{ number_format($r->current_stock, 0, ',', '.') }} {{ $r->unit->code ?? '' }}</td>
                        <td style="text-align: center; font-weight: bold; color: {{ $statusColor }}">
                            {{ $statusText }}
                        </td>
                    </tr>
                @endforeach

                @foreach($productsStock as $p)
                    @php $isOut = $p->current_stock <= 0; @endphp
                    <tr>
                        <td>Barang Jadi</td>
                        <td><strong>{{ $p->name }}</strong></td>
                        <td style="text-align: right;">{{ number_format($p->current_stock, 0, ',', '.') }} {{ $p->unit->code ?? 'pcs' }}</td>
                        <td style="text-align: center; font-weight: bold; color: {{ $isOut ? '#dc2626' : '#2563eb' }}">
                            {{ $isOut ? 'HABIS' : 'TERSEDIA' }}
                        </td>
                    </tr>
                @endforeach

                @if($rawMaterialsStock->isEmpty() && $productsStock->isEmpty())
                    <tr>
                        <td colspan="4" style="text-align: center; color: #888;">Belum ada data pada periode ini.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif

    <!-- 4. Penjualan Direct Admin -->
    @if($type === 'sale')
        <table class="pdf-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Tanggal Penjualan</th>
                    <th style="width: 25%;">Nomor Invoice</th>
                    <th style="width: 35%;">Customer</th>
                    <th style="width: 20%; text-align: right;">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}</td>
                        <td><strong>{{ $sale->invoice_number }}</strong></td>
                        <td>{{ $sale->customer_name ?? ($sale->customer->name ?? 'Umum / Retail') }}</td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #888;">Belum ada data pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- 5. Pengajuan Barang Sales -->
    @if($type === 'order')
        <table class="pdf-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Tanggal Pengajuan</th>
                    <th style="width: 20%;">Sales Pengaju</th>
                    <th style="width: 35%;">Detail Produk Dipesan</th>
                    <th style="width: 12%; text-align: center;">Status</th>
                    <th style="width: 13%; text-align: right;">Total Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                        <td><strong>{{ $order->sales->name ?? '—' }}</strong></td>
                        <td style="font-size: 11px;">
                            @foreach($order->items as $item)
                                • {{ $item->product->name ?? '—' }}: {{ number_format($item->qty, 0, ',', '.') }} pcs<br>
                            @endforeach
                            @foreach($order->packageItems as $pkgItem)
                                • [PAKET] {{ $pkgItem->package->name ?? '—' }}: {{ number_format($pkgItem->qty, 0, ',', '.') }} pack<br>
                            @endforeach
                        </td>
                        <td style="text-align: center; font-weight: bold;">
                            {{ strtoupper($order->status) }}
                        </td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #888;">Belum ada data pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- Footer Tandatangan -->
    <div class="pdf-footer">
        <div class="signature-block">
            <p>Dibuat Oleh,</p>
            <div class="signature-line">
                {{ \App\Models\Setting::get('report_prepared_by_label', 'Staf Administrasi') }}
            </div>
        </div>
        <div class="signature-block">
            <p>Disetujui Oleh,</p>
            <div class="signature-line">
                {{ \App\Models\Setting::get('report_approved_by_label', 'Pemilik Gudang / Owner') }}
            </div>
        </div>
    </div>

    @if(\App\Models\Setting::get('report_footer_note'))
        <div class="no-print" style="margin-top: 30px; border-top: 1px dashed #ddd; padding-top: 10px; font-size: 11px; color: #666; text-align: center; font-style: italic;">
            {{ \App\Models\Setting::get('report_footer_note') }}
        </div>
    @endif

    <!-- Pemicu Dialog Print Otomatis -->
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>

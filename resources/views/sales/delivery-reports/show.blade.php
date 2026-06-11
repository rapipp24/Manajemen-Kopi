<x-layouts.user>
    <x-slot name="title">Laporan {{ $deliveryReport->report_number }}</x-slot>

    <style>
        /* ── Page heading ──────────────────── */
        .report-heading { display:flex;align-items:center;gap:12px;margin-bottom:22px;flex-wrap:wrap; }
        .report-number  { font-size:17px;font-weight:700;color:var(--text);font-family:monospace;letter-spacing:0.02em; }
        
        .badge { display:inline-block;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;line-height:1.3;text-transform:uppercase;letter-spacing:0.04em; }
        .badge-approved { background:#f0fdf4;color:#166534;border:1px solid #bbf7d0; }
        .badge-pending  { background:#fffbeb;color:#b45309;border:1px solid #fef3c7; }
        .badge-canceled { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }

        /* ── Layout ──────────────────────── */
        .layout { display:grid;grid-template-columns:1fr 280px;gap:16px;align-items:start; }

        /* ── Card ────────────────────────── */
        .card { background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01); }
        .card-header { padding:13px 18px;border-bottom:1px solid var(--border);background:var(--cream); }
        .card-header h3 { font-size:13.5px;font-weight:700;color:var(--text);margin:0; }

        /* ── Table ───────────────────────── */
        .table-scroll-container { width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; }
        table { width:100%; border-collapse:collapse; }
        thead tr { background:var(--cream); border-bottom:1px solid var(--border); }
        th { padding:12px 18px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:0.07em; }
        td { padding:14px 18px; border-bottom:1px solid var(--border); font-size:13.5px; color:var(--text); vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:var(--cream); }

        /* ── Info panel ──────────────────── */
        .info-row { display:flex;justify-content:space-between;align-items:baseline;padding:12px 18px;border-bottom:1px solid var(--border);font-size:13.5px; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.05em;flex-shrink:0; }
        .info-value { font-size:13.5px;font-weight:600;color:var(--text);text-align:right;max-width:58%; }

        .note-box { padding:12px 18px;background:var(--cream);border-top:1px solid var(--border); }
        .note-label { font-size:10px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:4px; }
        .note-text  { font-size:13px;color:var(--text);font-style:italic; }

        .btn-primary {
            background:var(--brown);color:#fff;border:none;padding:10px 16px;border-radius:8px;
            font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:6px;
            transition:background 0.15s; width:100%; box-sizing:border-box; text-decoration:none;
        }
        .btn-primary:hover { background:var(--brown-hover); }

        /* ── Desktop/Mobile Dual Layout ──────── */
        .desktop-only { display: block; }
        .mobile-only { display: none; }

        .mobile-item {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .mobile-item:last-child { border-bottom: none; }
        .mobile-item-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }
        .mobile-item-title { font-weight: 700; color: var(--text); font-size: 13.5px; }
        .mobile-item-qty { font-weight: 800; color: var(--brown); font-size: 13.5px; }
        .mobile-item-bot {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .mobile-item-weight {
            background: var(--cream); border: 1px solid var(--border); color: var(--text);
            font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
        }
        .mobile-item-subtotal { color: var(--muted); font-weight: 600; font-size: 12.5px; }

        .mobile-total-row {
            padding: 12px 14px;
            background: var(--cream);
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .mobile-total-label { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .mobile-total-val { font-size: 16px; font-weight: 800; color: var(--brown); letter-spacing: -0.02em; }

        @media (max-width: 768px) {
            .layout { grid-template-columns: 1fr; }
            .desktop-only { display: none !important; }
            .mobile-only { display: block !important; }
        }

        /* ═══════════════ PRINT STYLES ═══════════════ */
        @media print {
            body {
                background: white !important;
                color: #2a170e !important;
                font-family: 'Inter', sans-serif !important;
                font-size: 11px !important;
                line-height: 1.4 !important;
            }
            .sidebar, .topbar, .mobile-topbar, .sidebar-overlay, .sales-back-link, .btn-primary, .sales-ghost-button, .no-print, .report-heading {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
            }
            .main {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                min-height: auto !important;
                display: block !important;
            }
            .page-body {
                padding: 0 !important;
            }
            
            /* Show A4 print header */
            .print-header-a4 {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid #6B2E16;
                padding-bottom: 12px;
                margin-bottom: 20px;
            }

            /* Adjust Grid to Block for Print */
            .layout {
                display: block !important;
            }

            /* Adjust side panels spacing and break avoidance */
            .layout > div {
                margin-bottom: 20px !important;
                page-break-inside: avoid !important;
            }

            .card {
                border: 1px solid var(--border) !important;
                box-shadow: none !important;
                page-break-inside: avoid !important;
            }
            
            /* Force desktop view when printing */
            .desktop-only {
                display: block !important;
            }
            .mobile-only {
                display: none !important;
            }
            
            /* Show signatures */
            .print-signatures {
                display: flex !important;
                justify-content: space-between;
                margin-top: 40px !important;
                page-break-inside: avoid !important;
            }
        }
        .print-header-a4, .print-signatures { display: none; }
    </style>

    <!-- Header Khusus Cetak A4 (Arsip Resmi) -->
    <div class="print-header-a4">
        <div style="display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('images/LOGO-KOPI-ELANG-EMAS.jpg') }}" alt="Logo" style="max-height: 45px; width: auto; object-fit: contain;" onerror="this.style.display='none';">
            <div>
                <h2 style="font-size: 15px; font-weight: 800; color: #6B2E16; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Inter', sans-serif;">{{ \App\Models\Setting::get('shop_name', 'KOPI ELANG EMAS') }}</h2>
                <p style="font-size: 9px; color: #7A3E1D; margin: 2px 0 0 0; font-weight: 600; text-transform: uppercase; font-family: 'Inter', sans-serif;">{{ \App\Models\Setting::get('shop_tagline', 'Panel Manajemen') }}</p>
            </div>
        </div>
        <div style="text-align: right;">
            <h1 style="font-size: 16px; font-weight: 800; color: #6B2E16; margin: 0; letter-spacing: 0.5px; font-family: 'Inter', sans-serif; text-transform: uppercase;">LAPORAN PENGIRIMAN</h1>
            <p style="font-size: 11px; font-weight: 700; color: #0f172a; margin: 4px 0 0 0; font-family: monospace;">{{ $deliveryReport->report_number }}</p>
        </div>
    </div>

    <a href="{{ route('sales.delivery-reports.index') }}" class="sales-back-link">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali ke Riwayat
    </a>

    <div class="report-heading">
        <span class="report-number">{{ $deliveryReport->report_number }}</span>
        <span class="badge badge-approved" style="display:inline-flex;align-items:center;gap:4px;">
            <i data-lucide="check" style="width:12px;height:12px;"></i> Terkirim
        </span>
    </div>

    <div class="layout">
        {{-- Kiri: Barang & Paket yang Dikirim --}}
        <div class="card">
            <div class="card-header">
                <h3>Rincian Pengiriman</h3>
            </div>
            
            <div class="table-scroll-container desktop-only">
                <table>
                    <thead>
                        <tr>
                            <th>Item / Nama Barang</th>
                            <th>Tipe / Kemasan</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Harga Jual</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Render Produk Satuan --}}
                        @foreach($deliveryReport->items as $item)
                        <tr>
                            <td style="font-weight:700;color:var(--text);">{{ $item->product->name }}</td>
                            <td>
                                <span style="background:var(--cream);border:1px solid var(--border);color:var(--text);font-size:11.5px;font-weight:600;padding:2px 8px;border-radius:20px;">
                                    {{ $item->product->weight }} Gram
                                </span>
                            </td>
                            <td style="text-align:center;font-weight:700;color:var(--text);">{{ number_format($item->qty, 0, ',', '.') }} pcs</td>
                            <td style="text-align:right;color:var(--muted);font-weight:500;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td style="text-align:right;font-weight:800;color:var(--text);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach

                        {{-- Render Paket --}}
                        @foreach($deliveryReport->packageItems as $pkgItem)
                        <tr>
                            <td style="font-weight:700;color:var(--text);">
                                {{ $pkgItem->package_name_snapshot }}
                                <small style="color:var(--muted); display:block; font-weight:normal; margin-top:3px; line-height:1.3;">
                                    Resep: {{ $pkgItem->package ? $pkgItem->package->items->map(fn($item) => ($item->qty + 0) . ' ' . ($item->product->unit->code ?? 'pcs') . ' ' . $item->product->name)->join(', ') : '—' }}
                                </small>
                            </td>
                            <td>
                                <span style="background:#fef3c7;border:1px solid #fde68a;color:#d97706;font-size:11.5px;font-weight:600;padding:2px 8px;border-radius:20px;">
                                    Paket ({{ $pkgItem->package_code_snapshot }})
                                </span>
                            </td>
                            <td style="text-align:center;font-weight:700;color:var(--text);">{{ number_format($pkgItem->qty, 0, ',', '.') }} pack</td>
                            <td style="text-align:right;color:var(--muted);font-weight:500;">Rp {{ number_format($pkgItem->price, 0, ',', '.') }}</td>
                            <td style="text-align:right;font-weight:800;color:var(--text);">Rp {{ number_format($pkgItem->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--cream); font-weight:800; border-top:1px solid var(--border);">
                            <td colspan="4" style="text-align:right;font-size:10.5px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;">
                                Total Nilai
                            </td>
                            <td style="text-align:right;font-size:17px;font-weight:800;color:var(--brown);letter-spacing:-0.02em;">
                                Rp {{ number_format($deliveryReport->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mobile-only">
                {{-- Render Produk Satuan Mobile --}}
                @foreach($deliveryReport->items as $item)
                <div class="mobile-item">
                    <div class="mobile-item-top">
                        <div class="mobile-item-title">{{ $item->product->name }}</div>
                        <div class="mobile-item-qty">{{ number_format($item->qty, 0, ',', '.') }} pcs</div>
                    </div>
                    <div class="mobile-item-bot">
                        <span class="mobile-item-weight">{{ $item->product->weight }} Gram</span>
                        <span class="mobile-item-subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach

                {{-- Render Paket Mobile --}}
                @foreach($deliveryReport->packageItems as $pkgItem)
                <div class="mobile-item">
                    <div class="mobile-item-top">
                        <div class="mobile-item-title">
                            {{ $pkgItem->package_name_snapshot }}
                            <small style="color:var(--muted); display:block; font-weight:normal; margin-top:2px; line-height:1.3;">
                                Resep: {{ $pkgItem->package ? $pkgItem->package->items->map(fn($item) => ($item->qty + 0) . ' ' . ($item->product->unit->code ?? 'pcs') . ' ' . $item->product->name)->join(', ') : '—' }}
                            </small>
                        </div>
                        <div class="mobile-item-qty">{{ number_format($pkgItem->qty, 0, ',', '.') }} pack</div>
                    </div>
                    <div class="mobile-item-bot">
                        <span class="mobile-item-weight" style="background:#fef3c7; color:#d97706; border-color:#fde68a;">Paket ({{ $pkgItem->package_code_snapshot }})</span>
                        <span class="mobile-item-subtotal">Rp {{ number_format($pkgItem->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach

                <div class="mobile-total-row">
                    <div class="mobile-total-label">Total Nilai</div>
                    <div class="mobile-total-val">Rp {{ number_format($deliveryReport->total_amount, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        {{-- Kanan: Info Pengiriman --}}
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card">
                <div class="card-header">
                    <h3>Info Pengiriman</h3>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Toko Tujuan</span>
                    <span class="info-value">{{ $deliveryReport->toko_name }}</span>
                </div>
                @if($deliveryReport->customer_address_manual)
                <div class="info-row" style="flex-direction:column; align-items:flex-start; gap:2px;">
                    <span class="info-label">Alamat Toko</span>
                    <span class="info-value" style="width:100%; text-align:left; font-size:12.5px; color:var(--muted); font-weight:normal; margin-top:2px;">
                        {{ $deliveryReport->customer_address_manual }}
                    </span>
                </div>
                @endif
                @if($deliveryReport->customer_phone_manual)
                <div class="info-row">
                    <span class="info-label">No. HP</span>
                    <span class="info-value">{{ $deliveryReport->customer_phone_manual }}</span>
                </div>
                @endif
                @if($deliveryReport->payment_term_days)
                <div class="info-row">
                    <span class="info-label">Tempo Bayar</span>
                    <span class="info-value" style="color:var(--brown);font-weight:800;">{{ $deliveryReport->payment_term_days }} hari</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Tanggal Kirim</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($deliveryReport->delivery_date)->format('d M Y') }}</span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Pembayaran & Tagihan</h3>
                </div>
                
                @php
                    $statusPaymentMap = [
                        'lunas' => ['badge-approved', 'Lunas'],
                        'dp'    => ['badge-pending', 'DP'],
                        'belum_bayar' => ['badge-canceled', 'Belum Bayar'],
                    ];
                    [$payCls, $payLbl] = $statusPaymentMap[$deliveryReport->payment_status] ?? ['badge-pending', $deliveryReport->payment_status];

                    if ($deliveryReport->payment_status === 'belum_bayar' && !$deliveryReport->payment_term_days) {
                        $hasPendingDeposit = $deliveryReport->deposits()->where('status', 'menunggu_verifikasi')->exists();
                        if ($hasPendingDeposit) {
                            $payLbl = 'Belum Bayar (Menunggu Verifikasi Setoran)';
                            $payCls = 'badge-pending';
                        }
                    }
                @endphp

                <div class="info-row">
                    <span class="info-label">Status Bayar</span>
                    <span class="badge {{ $payCls }}">{{ $payLbl }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Tagihan</span>
                    <span class="info-value">Rp {{ number_format($deliveryReport->total_amount, 0, ',', '.') }}</span>
                </div>
                @if($deliveryReport->payment_status === 'dp')
                <div class="info-row">
                    <span class="info-label">Jumlah DP</span>
                    <span class="info-value">Rp {{ number_format($deliveryReport->down_payment_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="info-row" style="background:var(--cream);">
                    <span class="info-label" style="color:var(--brown); font-weight:800;">Sisa Tagihan</span>
                    <span class="info-value" style="font-size:15px; font-weight:800; color:var(--brown);">Rp {{ number_format($deliveryReport->remaining_amount, 0, ',', '.') }}</span>
                </div>
                @if($deliveryReport->due_date)
                <div class="info-row">
                    <span class="info-label" style="color:#991b1b;">Jatuh Tempo</span>
                    <span class="info-value" style="color:#991b1b; font-weight:700;">{{ \Carbon\Carbon::parse($deliveryReport->due_date)->format('d M Y') }}</span>
                </div>
                @endif

                @if($deliveryReport->remaining_amount > 0)
                <div style="padding:12px 18px; border-top:1px solid var(--border);">
                    <a href="{{ route('sales.deposits.create', ['delivery_report_id' => $deliveryReport->id]) }}" class="btn-primary">
                        <i data-lucide="plus-circle" style="width:14px;height:14px;"></i> Kirim Setoran Baru
                    </a>
                </div>
                @endif
            </div>

            {{-- Informasi Return --}}
            @php
                $totalReturnDiterima = $deliveryReport->total_return_diterima;
                $tagihanEfektif      = $deliveryReport->total_amount - $totalReturnDiterima;
                $sisaTagihanReturn   = $tagihanEfektif - $deliveryReport->down_payment_amount;
                $returnsRelated      = $deliveryReport->salesReturns()->with('items')->orderBy('created_at', 'desc')->get();
            @endphp
            @if($totalReturnDiterima > 0 || $returnsRelated->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <h3>Koreksi Return</h3>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Total Return</span>
                    <span class="info-value" style="color:#991b1b;">- Rp {{ number_format($totalReturnDiterima, 0, ',', '.') }}</span>
                </div>
                <div class="info-row" style="background:var(--cream);">
                    <span class="info-label" style="font-weight:800;">Tagihan Efektif</span>
                    <span class="info-value" style="font-weight:800;">Rp {{ number_format(max(0, $tagihanEfektif), 0, ',', '.') }}</span>
                </div>
                @if($sisaTagihanReturn < 0)
                <div class="info-row" style="background:#eff6ff;">
                    <span class="info-label" style="color:#1d4ed8; font-weight:800;">Lebih Bayar</span>
                    <span class="info-value" style="color:#1d4ed8; font-weight:800;">Rp {{ number_format(abs($sisaTagihanReturn), 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Bayar Lebih Info --}}
            @if($deliveryReport->is_overpaid)
            <div class="card" style="background:#f0f9ff; border-color:#bae6fd;">
                <div class="card-header" style="background:#e0f2fe; border-bottom-color:#bae6fd;">
                    <h3 style="color:#0369a1;">Bayar Lebih</h3>
                </div>
                
                <div class="info-row" style="border-bottom-color:#bae6fd;">
                    <span class="info-label" style="color:#0369a1;">Status</span>
                    @if($deliveryReport->overpayment_resolved_at)
                        <span class="badge badge-approved">Selesai</span>
                    @else
                        <span class="badge badge-canceled">Menunggu</span>
                    @endif
                </div>
                <div class="info-row" style="border-bottom-color:#bae6fd;">
                    <span class="info-label" style="color:#0369a1;">Nominal</span>
                    <span class="info-value" style="color:#1d4ed8;">Rp {{ number_format($deliveryReport->overpayment_amount, 0, ',', '.') }}</span>
                </div>
                @if($deliveryReport->overpayment_resolved_at)
                <div style="padding:12px 18px; font-size:12px; color:#0369a1; line-height:1.4;">
                    Diselesaikan oleh: <strong>Admin</strong> pada {{ $deliveryReport->overpayment_resolved_at->format('d M Y') }}<br>
                    Catatan: <span style="font-style:italic;">"{{ $deliveryReport->overpayment_resolution_note }}"</span>
                </div>
                @else
                <div style="padding:12px 18px; font-size:11.5px; color:#0284c7; font-style:italic;">
                    Kelebihan pembayaran akan ditinjau dan diselesaikan manual oleh pihak Admin.
                </div>
                @endif
            </div>
            @endif

            {{-- Tombol Ajukan Return --}}
            <a href="{{ route('sales.returns.create', ['delivery_report_id' => $deliveryReport->id]) }}" class="sales-ghost-button" style="width:100%;">
                <i data-lucide="corner-up-left" style="width:14px;height:14px;"></i> Ajukan Return Barang
            </a>

            @if($deliveryReport->note)
            <div class="card">
                <div class="card-header">
                    <h3>Catatan</h3>
                </div>
                <div style="padding:14px 18px; font-size:13px; color:var(--muted); font-style:italic;">
                    "{{ $deliveryReport->note }}"
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Tanda Tangan Cetak A4 -->
    <div class="print-signatures">
        <div style="text-align: center; width: 200px;">
            <p style="font-size: 11px; margin-bottom: 50px; color: #475569;">Pengirim / Sales,</p>
            <div style="border-top: 1.2px solid #475569; padding-top: 4px; font-weight: 700; color: #0f172a;">{{ $deliveryReport->sales->name ?? 'Sales' }}</div>
        </div>
        <div style="text-align: center; width: 200px;">
            <p style="font-size: 11px; margin-bottom: 50px; color: #475569;">Penerima / Toko,</p>
            <div style="border-top: 1.2px solid #475569; padding-top: 4px; font-weight: 700; color: #0f172a;">{{ $deliveryReport->toko_name }}</div>
        </div>
    </div>
</x-layouts.user>

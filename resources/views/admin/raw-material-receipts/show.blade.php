<x-layouts.admin>
    <x-slot name="title">Detail Penerimaan - {{ $receipt->receipt_number }}</x-slot>

    <style>
        .card { background: white; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 24px; overflow: hidden; }
        .card-header { padding: 18px 24px; border-bottom: 1px solid var(--border); background: #fcfaf8; display: flex; justify-content: space-between; align-items: center; }
        .card-body { padding: 24px; }
        
        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
        .info-label { font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .info-value { font-size: 15px; font-weight: 600; color: var(--text-main); }
        
        .item-table { width: 100%; border-collapse: collapse; }
        .item-table th { text-align: left; padding: 12px 16px; background: #fcfaf8; font-size: 11px; font-weight: 800; color: #6B2E16; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        .item-table td { padding: 14px 16px; border-bottom: 1px solid #fcfaf8; font-size: 14px; }
        
        .grand-total-box { margin-top: 24px; padding: 20px; background: #6B2E16; border-radius: 12px; color: white; display: flex; justify-content: space-between; align-items: center; }
        
        .btn-action { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: 1px solid var(--border); background: white; color: var(--text-mid); }
        .btn-action:hover { background: #f8fafc; color: var(--text-main); }
        .btn-print { background: var(--brown-400); color: white; border-color: var(--brown-500); }
        .btn-print:hover { background: var(--brown-500); color: white; }
        .btn-a5 { background: #1e293b; color: white; border-color: #0f172a; }
        .btn-a5:hover { background: #0f172a; color: white; }

        /* ═══════════════ PRINT STYLES ═══════════════ */
        @media print {
            /* ─── GAYA DEFAULT UNTUK A4 (ARSIP RESMI HVS) ─── */
            body {
                font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
                font-size: 11px !important;
                color: #0f172a !important;
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                line-height: 1.5 !important;
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .sidebar, .topbar, .btn-action, .btn-back, .no-print, .btn-a5, .btn-print, .alert-print-area {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
            }
            .main-wrapper {
                margin: 0 !important;
                padding: 0 !important;
                min-height: auto !important;
                width: 100% !important;
                max-width: 100% !important;
                display: block !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }
            
            /* A4 Print Elements Custom Rules */
            body:not(.is-lx310-active) .print-only-a4 {
                display: block !important;
            }
            body:not(.is-lx310-active) .print-only-a4-grid {
                display: grid !important;
            }
            body:not(.is-lx310-active) .card-header {
                display: none !important;
            }
            body:not(.is-lx310-active) .info-grid {
                display: none !important;
            }
            body:not(.is-lx310-active) .print-header {
                display: none !important;
            }
            
            .card-body {
                padding: 0 !important;
            }
            
            /* Table Styling for A4 Print */
            body:not(.is-lx310-active) .item-table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin-top: 16px !important;
            }
            body:not(.is-lx310-active) .item-table th {
                background: transparent !important;
                padding: 10px 12px !important;
                font-size: 10px !important;
                color: #6B2E16 !important;
                border-top: none !important;
                border-bottom: 1.5px solid #6B2E16 !important;
                text-transform: uppercase !important;
                font-weight: 700 !important;
                letter-spacing: 0.5px !important;
            }
            body:not(.is-lx310-active) .item-table td {
                padding: 12px 12px !important;
                font-size: 11px !important;
                border-bottom: 1px solid #e2e8f0 !important;
                color: #1e293b !important;
            }
            body:not(.is-lx310-active) .item-table tr:last-child td {
                border-bottom: 1px solid #e2e8f0 !important;
            }
            
            /* Grand Total Box styling for A4 Print */
            body:not(.is-lx310-active) .grand-total-box {
                background: transparent !important;
                color: #6B2E16 !important;
                border: none !important;
                border-top: 1.5px solid #6B2E16 !important;
                border-bottom: 1.5px solid #6B2E16 !important;
                border-radius: 0 !important;
                padding: 12px 12px !important;
                margin-top: 20px !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                page-break-inside: avoid !important;
            }
            body:not(.is-lx310-active) .grand-total-box span:first-child {
                font-size: 11px !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                color: #7A3E1D !important;
            }
            body:not(.is-lx310-active) .grand-total-box span:last-child {
                font-size: 18px !important;
                font-weight: 800 !important;
                color: #6B2E16 !important;
            }
            
            /* Receipt Note Box for A4 Print */
            body:not(.is-lx310-active) .receipt-note-box {
                background: transparent !important;
                border: none !important;
                border-left: 3px solid #64748b !important;
                border-radius: 0 !important;
                padding: 4px 12px !important;
                margin-bottom: 20px !important;
            }
            
            /* Footer and Signatures for A4 Print */
            body:not(.is-lx310-active) .print-footer {
                display: flex !important;
                justify-content: space-between !important;
                margin-top: 48px !important;
                page-break-inside: avoid !important;
            }
            body:not(.is-lx310-active) .signature-box {
                text-align: center !important;
                width: 220px !important;
                font-size: 11px !important;
                color: #475569 !important;
            }
            body:not(.is-lx310-active) .signature-line {
                margin-top: 64px !important;
                border-top: 1.2px solid #475569 !important;
                padding-top: 6px !important;
                font-weight: 700 !important;
                font-size: 11.5px !important;
                color: #0f172a !important;
                text-transform: uppercase !important;
            }

            /* ═══════════════ CETAK OPTIMASI EPSON LX-310 OVERRIDES ═══════════════ */
            body.is-lx310-active {
                font-family: 'Courier New', Courier, monospace !important;
                font-size: 10px !important;
                color: #000 !important;
                background: white !important;
                margin: 0 !important;
                padding: 5px !important;
                line-height: 1.2 !important;
                display: block !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                max-width: 100% !important;
                width: 100% !important;
                border-radius: 0 !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .card-header {
                background: white !important;
                padding: 0 0 6px 0 !important;
                border-bottom: 1px dashed #000 !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: flex-end !important;
                margin-bottom: 12px !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .card-header h1 {
                font-size: 12px !important;
                font-weight: bold !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .card-header span {
                font-size: 11px !important;
                font-weight: bold !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .card-body {
                padding: 6px 0 !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .info-grid {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 10px !important;
                margin-bottom: 10px !important;
                border-bottom: 1px dashed #000 !important;
                padding-bottom: 6px !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .info-label {
                font-size: 9px !important;
                color: #444 !important;
                font-weight: bold !important;
                margin-bottom: 2px !important;
                font-family: 'Courier New', Courier, monospace !important;
                text-transform: uppercase !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .info-value {
                font-size: 10px !important;
                font-weight: bold !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .item-table th {
                background: transparent !important;
                padding: 4px 6px !important;
                font-size: 10px !important;
                color: #000 !important;
                border-top: 1px dashed #000 !important;
                border-bottom: 1px dashed #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
                text-transform: uppercase !important;
                font-weight: bold !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .item-table td {
                padding: 4px 6px !important;
                font-size: 10px !important;
                border-bottom: 1px dashed #eee !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .item-table tr:last-child td {
                border-bottom: none !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .grand-total-box {
                background: transparent !important;
                color: #000 !important;
                border-top: 1px dashed #000 !important;
                border-bottom: 1px dashed #000 !important;
                border-left: none !important;
                border-right: none !important;
                border-radius: 0 !important;
                padding: 6px 8px !important;
                margin-top: 10px !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .grand-total-box span:first-child {
                font-size: 10px !important;
                font-weight: bold !important;
                font-family: 'Courier New', Courier, monospace !important;
                text-transform: uppercase !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .grand-total-box span:last-child {
                font-size: 13px !important;
                font-weight: bold !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .print-header {
                display: block !important;
                margin-bottom: 10px !important;
                text-align: center !important;
                border-bottom: 1px dashed #000 !important;
                padding-bottom: 6px !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .print-header h1 {
                font-size: 13px !important;
                font-weight: bold !important;
                margin: 0 0 2px 0 !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .print-header p {
                font-size: 9px !important;
                margin: 0 !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .print-footer {
                display: flex !important;
                justify-content: space-between !important;
                margin-top: 20px !important;
                page-break-inside: avoid !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .signature-box {
                text-align: center !important;
                width: 150px !important;
                font-size: 10px !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .signature-line {
                margin-top: 35px !important;
                border-top: 1px solid #000 !important;
                padding-top: 2px !important;
                font-weight: bold !important;
                font-size: 10px !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            body.is-lx310-active #printArea.is-lx310 .receipt-note-box {
                background: transparent !important;
                border: none !important;
                border-left: 1px dashed #000 !important;
                border-radius: 0 !important;
                padding: 4px 6px !important;
                margin-bottom: 10px !important;
            }
        }
        
        .print-header, .print-footer, .print-only-a4 { display: none !important; }
    </style>


        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('admin.raw-material-receipts.index') }}" class="btn-action">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali
            </a>
            <div style="display: flex; gap: 10px;">
                <button onclick="printLX310()" class="btn-action btn-a5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    Cetak LX-310
                </button>
                <button onclick="printA4()" class="btn-action btn-print">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.89l-4.72-4.72m0 0l4.72-4.72M2 9.17h18a2 2 0 012 2v.92c0 .55-.45 1-1 1H7.83l4.72 4.72m-4.72-4.72L12.55 13.89" /><path stroke-linecap="round" stroke-linejoin="round" d="M17 17v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2" /><path stroke-linecap="round" stroke-linejoin="round" d="M17 7V5a2 2 0 00-2-2H5a2 2 0 00-2 2v2" /></svg>
                    Cetak A4 (Arsip)
                </button>
            </div>
        </div>
    </div>

    <div class="card" id="printArea">
        <!-- Header Khusus Cetak -->
        <div class="print-header">
            <h1 style="font-size: 24px; font-family: 'Inter', sans-serif; font-weight: 700; color: #000;">LAPORAN PENERIMAAN BARANG</h1>
            <p style="font-size: 14px; color: #000;">KOPI ELANG EMAS - PANEL MANAJEMEN</p>
            <p style="font-size: 12px; margin-top: 5px;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Header Khusus Cetak A4 (Arsip Resmi) -->
        <div class="print-only-a4" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 2px solid #6B2E16;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <img src="{{ asset('images/LOGO-KOPI-ELANG-EMAS.jpg') }}" alt="Logo" style="max-height: 45px; width: auto; object-fit: contain;" onerror="this.style.display='none';">
                    <div>
                        <h2 style="font-size: 15px; font-weight: 800; color: #6B2E16; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Inter', sans-serif;">{{ \App\Models\Setting::get('shop_name', 'KOPI ELANG EMAS') }}</h2>
                        <p style="font-size: 10px; color: #7A3E1D; margin: 2px 0 0 0; font-weight: 600; text-transform: uppercase; font-family: 'Inter', sans-serif;">{{ \App\Models\Setting::get('shop_tagline', 'Panel Manajemen') }}</p>
                    </div>
                </div>
                <div style="text-align: right;">
                    <h1 style="font-size: 16px; font-weight: 800; color: #6B2E16; margin: 0; letter-spacing: 0.5px; font-family: 'Inter', sans-serif; text-transform: uppercase;">LAPORAN PENERIMAAN BARANG</h1>
                    <p style="font-size: 9.5px; color: #475569; margin: 4px 0 0 0; font-weight: 500; font-family: 'Inter', sans-serif;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Transaksi A4 (Arsip Resmi) -->
        <div class="print-only-a4" style="margin-bottom: 24px; padding: 12px 0 16px 0; border-bottom: 1px solid #cbd5e1;">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: start;">
                <div>
                    <span style="font-size: 9px; font-weight: 700; color: #475569; display: block; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Nomor Transaksi</span>
                    <span style="font-size: 14px; font-weight: 700; color: #0f172a;">{{ $receipt->receipt_number }}</span>
                </div>
                <div>
                    <span style="font-size: 9px; font-weight: 700; color: #475569; display: block; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Supplier</span>
                    <span style="font-size: 11.5px; font-weight: 600; color: #1e293b;">{{ $receipt->supplier?->name ?? 'Supplier tidak tersedia' }}</span>
                </div>
                <div>
                    <span style="font-size: 9px; font-weight: 700; color: #475569; display: block; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Tanggal Terima</span>
                    <span style="font-size: 11.5px; font-weight: 600; color: #1e293b;">{{ date('d F Y', strtotime($receipt->receipt_date)) }}</span>
                </div>
                <div>
                    <span style="font-size: 9px; font-weight: 700; color: #475569; display: block; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">No. Nota / Surat Jalan</span>
                    <span style="font-size: 11.5px; font-weight: 600; color: #1e293b;">
                        @if($receipt->reference_number)
                            <span>{{ $receipt->reference_number }}</span>
                        @else
                            <span style="color: #64748b; font-style: italic; font-weight: normal;">-</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="card-header">
            <div>
                <h1 style="font-size: 18px; font-weight: 800; color: var(--text-main);">Detail Penerimaan</h1>
                <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Data transaksi pencatatan bahan masuk.</p>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 11px; font-weight: 800; color: var(--text-muted); display: block; text-transform: uppercase;">Nomor Transaksi</span>
                <span style="font-size: 18px; font-weight: 800; color: var(--brown-400);">{{ $receipt->receipt_number }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div>
                    <div class="info-label">Supplier</div>
                    <div class="info-value">{{ $receipt->supplier?->name ?? 'Supplier tidak tersedia' }}</div>
                </div>
                <div>
                    <div class="info-label">Tanggal Terima</div>
                    <div class="info-value">{{ date('d F Y', strtotime($receipt->receipt_date)) }}</div>
                </div>
                <div>
                    <div class="info-label">No. Nota / Surat Jalan</div>
                    <div class="info-value">
                        @if($receipt->reference_number)
                            <span style="background: #f0f9ff; color: #0369a1; padding: 4px 10px; border-radius: 6px; font-size: 13px;">{{ $receipt->reference_number }}</span>
                        @else
                            <span style="color: #cbd5e1;">Tidak ada nota</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($receipt->note)
                <div class="receipt-note-box" style="margin-bottom: 32px; padding: 16px; background: #fcfaf8; border-radius: 8px; border-left: 4px solid #e7d8c5;">
                    <div class="info-label" style="margin-bottom: 4px;">Catatan Tambahan</div>
                    <div style="font-size: 14px; color: var(--text-mid); line-height: 1.5;">{{ $receipt->note }}</div>
                </div>
            @endif

            <h3 style="font-size: 13px; font-weight: 800; color: var(--text-main); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">Rincian Item Bahan</h3>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Nama Bahan</th>
                        <th style="text-align: right;">Kuantitas</th>
                        <th style="text-align: right;">Harga Satuan</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipt->items as $item)
                        @php
                            // Null-safe: rawMaterial bisa null jika sudah dihapus permanen
                            $rawMaterialName = $item->rawMaterial?->name ?? 'Bahan baku telah dihapus';
                            $rawMaterialUnit = $item->rawMaterial?->unit?->code ?? null;

                            // Logika tampil qty — null-safe jika unit tidak ada
                            $qty = $item->qty;
                            if ($rawMaterialUnit) {
                                if (strtolower($rawMaterialUnit) === 'kg' && $qty >= 1000) {
                                    $displayQty = number_format($qty / 1000, 2, ',', '.') . ' <span style="font-size: 11px; color: var(--text-muted); font-weight: 700;">Ton</span>';
                                } else {
                                    $fmt = (floor($qty) == $qty) ? 0 : 2;
                                    $displayQty = number_format($qty, $fmt, ',', '.') . ' <span style="font-size: 11px; color: var(--text-muted); font-weight: 700;">' . $rawMaterialUnit . '</span>';
                                }
                            } else {
                                // Unit tidak tersedia — tampilkan qty saja tanpa satuan
                                $fmt = (floor($qty) == $qty) ? 0 : 2;
                                $displayQty = number_format($qty, $fmt, ',', '.');
                            }
                        @endphp
                        <tr>
                            <td style="font-weight: 600;">
                                {{ $rawMaterialName }}
                                @if($item->rawMaterial === null)
                                    <span style="font-size: 11px; color: #94a3b8; font-weight: 400; margin-left: 4px;">(ID: {{ $item->raw_material_id }})</span>
                                @elseif($item->rawMaterial->trashed())
                                    <span style="font-size: 11px; color: #94a3b8; font-weight: 400; margin-left: 4px;">(dihapus)</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                {!! $displayQty !!}
                            </td>
                            <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td style="text-align: right; font-weight: 700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="grand-total-box">
                <span style="font-size: 14px; font-weight: 600; opacity: 0.8;">Total Seluruh Transaksi</span>
                <span style="font-size: 24px; font-weight: 800;">Rp {{ number_format($receipt->total_amount, 0, ',', '.') }}</span>
            </div>

            <!-- Footer Khusus Cetak -->
            <div class="print-footer">
                <div class="signature-box">
                    <p>Hormat Kami,</p>
                    <div class="signature-line">{{ $receipt->supplier?->name ?? 'Supplier tidak tersedia' }}</div>
                </div>
                <div class="signature-box">
                    <p>Diterima Oleh,</p>
                    <div class="signature-line">{{ $receipt->creator?->name ?? 'Pengguna tidak tersedia' }}</div>
                </div>
            </div>
            
            <div class="no-print" style="margin-top: 24px; font-size: 12px; color: var(--text-muted); text-align: center;">
                Dicatat oleh <strong>{{ $receipt->creator?->name ?? 'Pengguna tidak tersedia' }}</strong> pada {{ $receipt->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <script>
        function getOrCreateDynamicStyle() {
            let style = document.getElementById('dynamic-print-page-size');
            if (!style) {
                style = document.createElement('style');
                style.id = 'dynamic-print-page-size';
                document.head.appendChild(style);
            }
            return style;
        }

        function printLX310() {
            // 1. Terapkan kelas CSS khusus ke body & printArea
            document.getElementById('printArea').classList.add('is-lx310');
            document.body.classList.add('is-lx310-active');

            // 2. Buat/Update tag style dinamis
            const style = getOrCreateDynamicStyle();
            style.innerHTML = `
                @media print {
                    @page { size: 210mm 148mm; margin: 0.3cm; }
                }
            `;

            // 3. Jalankan dialog cetak
            window.print();
        }

        function printA4() {
            // 1. Pastikan kelas khusus LX310 bersih
            document.getElementById('printArea').classList.remove('is-lx310');
            document.body.classList.remove('is-lx310-active');

            // 2. Buat/Update tag style dinamis untuk A4
            const style = getOrCreateDynamicStyle();
            style.innerHTML = `
                @media print {
                    @page { size: A4 portrait; margin: 14mm; }
                }
            `;

            // 3. Jalankan dialog cetak
            window.print();
        }

        // Bersihkan class dan style setelah dialog cetak ditutup
        window.addEventListener('afterprint', () => {
            document.getElementById('printArea').classList.remove('is-lx310');
            document.body.classList.remove('is-lx310-active');

            const style = document.getElementById('dynamic-print-page-size');
            if (style) {
                style.remove();
            }
        });
    </script>
</x-layouts.admin>

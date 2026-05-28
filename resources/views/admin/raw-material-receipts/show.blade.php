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
        .item-table th { text-align: left; padding: 12px 16px; background: #f8fafc; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        .item-table td { padding: 14px 16px; border-bottom: 1px solid #f8fafc; font-size: 14px; }
        
        .grand-total-box { margin-top: 24px; padding: 20px; background: #1a1512; border-radius: 12px; color: white; display: flex; justify-content: space-between; align-items: center; }
        
        .btn-action { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: 1px solid var(--border); background: white; color: var(--text-mid); }
        .btn-action:hover { background: #f8fafc; color: var(--text-main); }
        .btn-print { background: var(--brown-400); color: white; border-color: var(--brown-500); }
        .btn-print:hover { background: var(--brown-500); color: white; }
        .btn-a5 { background: #1e293b; color: white; border-color: #0f172a; }
        .btn-a5:hover { background: #0f172a; color: white; }

        /* ═══════════════ PRINT STYLES ═══════════════ */
        @media print {
            /* Gaya Cetak Default A4 (Arsip HVS Bersih & Elegan) */
            body {
                font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
                font-size: 13px !important;
                color: #1c1917 !important;
                background: white !important;
                margin: 0 !important;
                padding: 20px !important;
                line-height: 1.4 !important;
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
            }
            .card {
                border: 1px solid #e7e5e4 !important;
                box-shadow: none !important;
                margin: 0 auto !important;
                padding: 24px !important;
                background: white !important;
                max-width: 800px !important;
                border-radius: 8px !important;
            }
            .card-header {
                background: #fcfaf8 !important;
                padding: 16px 20px !important;
                border-bottom: 1px solid #e7e5e4 !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
            }
            .card-header h1 {
                font-size: 16px !important;
                font-weight: 800 !important;
                color: #2c1a0e !important;
            }
            .card-header span {
                font-size: 14px !important;
                font-weight: bold !important;
                color: #92400e !important;
            }
            .card-body {
                padding: 20px 0 !important;
            }
            .info-grid {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 20px !important;
                margin-bottom: 24px !important;
                border-bottom: 1px solid #f5f0eb !important;
                padding-bottom: 16px !important;
            }
            .info-label {
                font-size: 10px !important;
                color: #78716c !important;
                font-weight: bold !important;
                margin-bottom: 4px !important;
            }
            .info-value {
                font-size: 13px !important;
                font-weight: 600 !important;
                color: #1c1917 !important;
            }
            .item-table th {
                background: #f8fafc !important;
                padding: 10px 14px !important;
                font-size: 11px !important;
                color: #6b4c35 !important;
                border-bottom: 1px solid #e7e5e4 !important;
            }
            .item-table td {
                padding: 12px 14px !important;
                font-size: 13px !important;
                border-bottom: 1px solid #f8fafc !important;
                color: #1c1917 !important;
            }
            .item-table tr:last-child td {
                border-bottom: none !important;
            }
            .grand-total-box {
                background: #1a1512 !important;
                color: white !important;
                border-radius: 8px !important;
                padding: 16px 20px !important;
                margin-top: 20px !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
            }
            .grand-total-box span:first-child {
                font-size: 13px !important;
                font-weight: 600 !important;
            }
            .grand-total-box span:last-child {
                font-size: 20px !important;
                font-weight: 800 !important;
            }
            .print-header {
                display: block !important;
                margin-bottom: 20px !important;
                text-align: center !important;
                border-bottom: 2px solid #2c1a0e !important;
                padding-bottom: 12px !important;
            }
            .print-header h1 {
                font-size: 18px !important;
                font-weight: 800 !important;
                margin: 0 0 6px 0 !important;
                color: #2c1a0e !important;
            }
            .print-header p {
                font-size: 12px !important;
                margin: 0 !important;
                color: #6b4c35 !important;
            }
            .print-footer {
                display: flex !important;
                justify-content: space-between !important;
                margin-top: 30px !important;
                page-break-inside: avoid !important;
            }
            .signature-box {
                text-align: center !important;
                width: 200px !important;
                font-size: 12px !important;
            }
            .signature-line {
                margin-top: 50px !important;
                border-top: 1px solid #78716c !important;
                padding-top: 6px !important;
                font-weight: bold !important;
                font-size: 12px !important;
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
            }
            
            #printArea.is-lx310 {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            
            #printArea.is-lx310 .card-header {
                background: white !important;
                padding: 0 0 6px 0 !important;
                border-bottom: 1px dashed #000 !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: flex-end !important;
            }
            
            #printArea.is-lx310 .card-header h1 {
                font-size: 12px !important;
                font-weight: bold !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .card-header span {
                font-size: 11px !important;
                font-weight: bold !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .card-body {
                padding: 6px 0 !important;
            }
            
            #printArea.is-lx310 .info-grid {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 10px !important;
                margin-bottom: 10px !important;
                border-bottom: 1px dashed #000 !important;
                padding-bottom: 6px !important;
            }
            
            #printArea.is-lx310 .info-label {
                font-size: 9px !important;
                color: #444 !important;
                font-weight: bold !important;
                margin-bottom: 2px !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .info-value {
                font-size: 10px !important;
                font-weight: bold !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .item-table th {
                background: transparent !important;
                padding: 4px 6px !important;
                font-size: 10px !important;
                color: #000 !important;
                border-top: 1px dashed #000 !important;
                border-bottom: 1px dashed #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .item-table td {
                padding: 4px 6px !important;
                font-size: 10px !important;
                border-bottom: 1px dashed #eee !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .item-table tr:last-child td {
                border-bottom: none !important;
            }
            
            #printArea.is-lx310 .grand-total-box {
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
            
            #printArea.is-lx310 .grand-total-box span:first-child {
                font-size: 10px !important;
                font-weight: bold !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .grand-total-box span:last-child {
                font-size: 13px !important;
                font-weight: bold !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .print-header {
                display: block !important;
                margin-bottom: 10px !important;
                text-align: center !important;
                border-bottom: 1px dashed #000 !important;
                padding-bottom: 6px !important;
            }
            
            #printArea.is-lx310 .print-header h1 {
                font-size: 13px !important;
                font-weight: bold !important;
                margin: 0 0 2px 0 !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .print-header p {
                font-size: 9px !important;
                margin: 0 !important;
                color: #000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .print-footer {
                display: flex !important;
                justify-content: space-between !important;
                margin-top: 20px !important;
                page-break-inside: avoid !important;
            }
            
            #printArea.is-lx310 .signature-box {
                text-align: center !important;
                width: 150px !important;
                font-size: 10px !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
            
            #printArea.is-lx310 .signature-line {
                margin-top: 35px !important;
                border-top: 1px solid #000 !important;
                padding-top: 2px !important;
                font-weight: bold !important;
                font-size: 10px !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
        }
        
        .print-header, .print-footer { display: none; }
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
                    <div class="info-value">{{ $receipt->supplier->name }}</div>
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
                <div style="margin-bottom: 32px; padding: 16px; background: #fcfaf8; border-radius: 8px; border-left: 4px solid #e7d8c5;">
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
                        <tr>
                            <td style="font-weight: 600;">{{ $item->rawMaterial->name }}</td>
                            <td style="text-align: right;">
                                @php
                                    $qty = $item->qty;
                                    $unit = $item->rawMaterial->unit->code;
                                    
                                    // Logika konversi Ton otomatis
                                    if (strtolower($unit) == 'kg' && $qty >= 1000) {
                                        $displayQty = number_format($qty / 1000, 2, ',', '.') . ' <span style="font-size: 11px; color: var(--text-muted); font-weight: 700;">Ton</span>';
                                    } else {
                                        // Hilangkan desimal ,00 jika angka bulat
                                        $fmt = (floor($qty) == $qty) ? 0 : 2;
                                        $displayQty = number_format($qty, $fmt, ',', '.') . ' <span style="font-size: 11px; color: var(--text-muted); font-weight: 700;">' . $unit . '</span>';
                                    }
                                @endphp
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
                    <div class="signature-line">{{ $receipt->supplier->name }}</div>
                </div>
                <div class="signature-box">
                    <p>Diterima Oleh,</p>
                    <div class="signature-line">{{ $receipt->creator->name }}</div>
                </div>
            </div>
            
            <div class="no-print" style="margin-top: 24px; font-size: 12px; color: var(--text-muted); text-align: center;">
                Dicatat oleh <strong>{{ $receipt->creator->name }}</strong> pada {{ $receipt->created_at->format('d/m/Y H:i') }}
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
                    @page { size: A4 portrait; margin: 0.8cm; }
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

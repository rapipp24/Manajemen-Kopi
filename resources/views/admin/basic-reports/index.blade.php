<x-layouts.admin>
    <x-slot name="title">Laporan Dasar</x-slot>

    <!-- Flatpickr for Date Selection -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .report-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
            padding-bottom: 40px;
        }

        /* ── Header Laporan & Filter ── */
        .report-header-card {
            background: white;
            border-radius: 24px;
            padding: 28px;
            border: 1px solid #e8d8c4;
            box-shadow: 0 4px 20px rgba(120, 53, 15, 0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .filter-input-wrapper {
            position: relative;
            background: #fffdfa;
            border: 1.5px solid #e8d8c4;
            border-radius: 16px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-input-wrapper:hover {
            border-color: #92400e;
            box-shadow: 0 4px 12px rgba(146, 64, 14, 0.05);
        }

        .btn-filter-submit {
            background: #92400e;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);
        }

        .btn-filter-submit:hover {
            background: #78350f;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(146, 64, 14, 0.2);
        }

        /* ── Tabs Navigation ── */
        .report-tabs {
            display: flex;
            background: #f7f0e6;
            border-radius: 18px;
            padding: 6px;
            gap: 4px;
            overflow-x: auto;
            border: 1px solid #e8d8c4;
            scrollbar-width: none;
        }

        .report-tabs::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            padding: 12px 20px;
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 700;
            color: #6b4c35;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid transparent;
        }

        .tab-btn:hover {
            color: #92400e;
            background: rgba(255, 255, 255, 0.5);
        }

        .tab-btn.active {
            background: white;
            color: #92400e;
            box-shadow: 0 4px 10px rgba(120, 53, 15, 0.05);
            border-color: rgba(232, 216, 196, 0.5);
        }

        /* ── Action Buttons ── */
        .report-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-bottom: 4px;
        }

        .btn-action-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 14px;
            background: white;
            border: 1.5px solid #e8d8c4;
            color: #6b4c35;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-action-outline:hover {
            background: #fffdfa;
            border-color: #92400e;
            color: #92400e;
            box-shadow: 0 4px 12px rgba(146, 64, 14, 0.04);
            transform: translateY(-1px);
        }

        /* ── Table Card Styling ── */
        .report-table-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #e8d8c4;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(120, 53, 15, 0.03);
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .report-table th {
            background: #fffdfa;
            padding: 18px 24px;
            font-size: 12px;
            font-weight: 800;
            color: #9e7c62;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #e8d8c4;
        }

        .report-table td {
            padding: 18px 24px;
            font-size: 14px;
            color: #2c1a0e;
            border-bottom: 1px solid #f7f0e6;
            vertical-align: middle;
        }

        .report-table tr:last-child td {
            border-bottom: none;
        }

        .report-table tr:hover td {
            background: #fffdfa;
        }

        /* ── Badge status ── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669; }
        .status-warning { background: #fffbeb; border: 1px solid #fde68a; color: #d97706; }
        .status-danger  { background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; }
        .status-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; }

        /* ── Empty State ── */
        .empty-state {
            padding: 60px 40px;
            text-align: center;
            color: #9e7c62;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }
    </style>

    <div class="report-container">
        <!-- Header & Date Filter Card -->
        <div class="report-header-card">
            <div>
                <h1 style="font-size: 20px; font-weight: 800; color: #1c0f05; margin-bottom: 4px;">Laporan Dasar</h1>
                <p style="font-size: 13.5px; color: #9e7c62;">Analisis data operasional, stok, dan penjualan retail</p>
            </div>

            @if($activeTab !== 'stock' && $activeTab !== 'stok')
            <form id="filter-form" method="GET" action="{{ route('admin.basic-reports.index') }}">
                <input type="hidden" name="type" value="{{ $activeTab }}">
                
                <div class="filter-group">
                    <div id="date-picker-trigger" class="filter-input-wrapper">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #fdfaf6; display: flex; align-items: center; justify-content: center; color: #92400e;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div style="flex: 1;">
                            <p style="font-size: 9px; font-weight: 800; color: #9e7c62; text-transform: uppercase; margin-bottom: -1px;">Rentang Tanggal</p>
                            <span id="date-display" style="font-size: 13px; font-weight: 700; color: #2c1a0e;">{{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <input type="text" id="date-range-input" style="position: absolute; opacity: 0; pointer-events: none; width: 1px; height: 1px;">
                    <input type="hidden" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}">

                    <button type="submit" class="btn-filter-submit">Terapkan Filter</button>
                </div>
            </form>
            @endif
        </div>

        <!-- Tabs Navigation -->
        <div class="report-tabs">
            <a href="{{ route('admin.basic-reports.index', ['type' => 'raw_material', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               class="tab-btn {{ $activeTab === 'raw_material' ? 'active' : '' }}">
                Bahan Baku
            </a>
            <a href="{{ route('admin.basic-reports.index', ['type' => 'production', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               class="tab-btn {{ $activeTab === 'production' ? 'active' : '' }}">
                Produksi Kopi
            </a>
            <a href="{{ route('admin.basic-reports.index', ['type' => 'stock', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               class="tab-btn {{ ($activeTab === 'stock' || $activeTab === 'stok') ? 'active' : '' }}">
                Stok Aktual
            </a>
            <a href="{{ route('admin.basic-reports.index', ['type' => 'sale', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               class="tab-btn {{ $activeTab === 'sale' ? 'active' : '' }}">
                Penjualan Admin
            </a>
            <a href="{{ route('admin.basic-reports.index', ['type' => 'order', 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               class="tab-btn {{ $activeTab === 'order' ? 'active' : '' }}">
                Pengajuan Sales
            </a>
        </div>

        <!-- Helper Text Laporan Stok -->
        @if($activeTab === 'stock' || $activeTab === 'stok')
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 16px; padding: 14px 20px; font-size: 13px; color: #b45309; display: flex; align-items: center; gap: 10px;">
                <svg style="width: 20px; height: 20px; flex-shrink: 0; color: #b45309;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><strong>Catatan:</strong> Laporan stok di bawah ini menunjukkan kondisi fisik saat ini secara real-time dan tidak dipengaruhi oleh filter rentang tanggal di atas.</span>
            </div>
        @endif

        <!-- Action Buttons (Export) -->
        <div class="report-actions">
            <a href="{{ route('admin.basic-reports.export-excel', ['type' => $activeTab, 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               class="btn-action-outline">
                <svg style="width: 16px; height: 16px; color: #2d6a4f;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Excel (.csv)
            </a>
            <a href="{{ route('admin.basic-reports.export-pdf', ['type' => $activeTab, 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               target="_blank"
               class="btn-action-outline">
                <svg style="width: 16px; height: 16px; color: #92400e;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"></path></svg>
                Export PDF (Cetak)
            </a>
        </div>

        <!-- Table Display Area -->
        <div class="report-table-card">
            
            <!-- 1. BAHAN BAKU -->
            @if($activeTab === 'raw_material')
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Bahan Baku</th>
                            <th style="text-align: right;">Qty Diterima</th>
                            <th style="text-align: right;">Total Pembelian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rawMaterials as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->receipt->receipt_date)->format('d-m-Y') }}</td>
                                <td><span style="font-weight: 600; color: #1c0f05;">{{ $item->receipt->supplier->name ?? '—' }}</span></td>
                                <td>{{ $item->rawMaterial->name ?? '—' }}</td>
                                <td style="text-align: right; font-weight: 600;">{{ number_format($item->qty, 0, ',', '.') }} {{ $item->rawMaterial->unit->code ?? '' }}</td>
                                <td style="text-align: right; font-weight: 700; color: #2d6a4f;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <svg style="width: 48px; height: 48px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path></svg>
                                        <p style="font-size: 14px; font-weight: 600;">Belum ada data pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            <!-- 2. PRODUKSI KOPI -->
            @if($activeTab === 'production')
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nomor Batch</th>
                            <th>Bahan Digunakan</th>
                            <th style="text-align: right;">Hasil Output</th>
                            <th style="text-align: right;">Susut (Shrinkage)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productions as $batch)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($batch->production_date)->format('d-m-Y') }}</td>
                                <td><span style="font-family: monospace; font-size: 13px; font-weight: 700; background: #fdfaf6; padding: 4px 8px; border-radius: 6px; border: 1px solid #e8d8c4;">{{ $batch->batch_number }}</span></td>
                                <td style="max-width: 300px; font-size: 13px; color: #6b4c35; line-height: 1.4;">
                                    @foreach($batch->items as $item)
                                        <div style="margin-bottom: 2px;">
                                            • {{ $item->rawMaterial->name ?? '—' }}: <strong>{{ number_format($item->qty_used, 0, ',', '.') }} {{ $item->rawMaterial->unit->code ?? '' }}</strong>
                                        </div>
                                    @endforeach
                                </td>
                                <td style="text-align: right; font-weight: 700; color: #92400e;">{{ number_format($batch->total_output, 0, ',', '.') }} gr</td>
                                <td style="text-align: right; font-weight: 600; color: #dc2626;">{{ number_format($batch->shrinkage, 0, ',', '.') }} gr</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <svg style="width: 48px; height: 48px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        <p style="font-size: 14px; font-weight: 600;">Belum ada data pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            <!-- 3. STOK AKTUAL -->
            @if($activeTab === 'stock' || $activeTab === 'stok')
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tipe Item</th>
                            <th>Nama Item</th>
                            <th style="text-align: right;">Stok Saat Ini</th>
                            <th style="text-align: center; width: 180px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Raw Materials List -->
                        @foreach($rawMaterialsStock as $r)
                            @php $isCritical = $r->current_stock <= $r->minimum_stock; @endphp
                            <tr>
                                <td><span style="font-size: 11px; font-weight: 800; color: #9e7c62; background: #fdfaf6; padding: 3px 8px; border-radius: 6px; border: 1px solid #e8d8c4; text-transform: uppercase;">Bahan Baku</span></td>
                                <td><span style="font-weight: 600; color: #2c1a0e;">{{ $r->name }}</span></td>
                                <td style="text-align: right; font-weight: 700;">{{ number_format($r->current_stock, 0, ',', '.') }} {{ $r->unit->code ?? '' }}</td>
                                <td style="text-align: center;">
                                    @if($isCritical)
                                        <span class="status-badge status-danger">Hampir Habis</span>
                                    @else
                                        <span class="status-badge status-success">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        <!-- Products List -->
                        @foreach($productsStock as $p)
                            @php $isOut = $p->current_stock <= 0; @endphp
                            <tr>
                                <td><span style="font-size: 11px; font-weight: 800; color: #2563eb; background: #eff6ff; padding: 3px 8px; border-radius: 6px; border: 1px solid #bfdbfe; text-transform: uppercase;">Barang Jadi</span></td>
                                <td><span style="font-weight: 600; color: #2c1a0e;">{{ $p->name }}</span></td>
                                <td style="text-align: right; font-weight: 700;">{{ number_format($p->current_stock, 0, ',', '.') }} {{ $p->unit->code ?? 'pcs' }}</td>
                                <td style="text-align: center;">
                                    @if($isOut)
                                        <span class="status-badge status-danger">Habis</span>
                                    @else
                                        <span class="status-badge status-info">Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if($rawMaterialsStock->isEmpty() && $productsStock->isEmpty())
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <svg style="width: 48px; height: 48px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path></svg>
                                        <p style="font-size: 14px; font-weight: 600;">Belum ada data pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endif

            <!-- 4. PENJUALAN DIRECT ADMIN -->
            @if($activeTab === 'sale')
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tanggal Invoice</th>
                            <th>Nomor Invoice</th>
                            <th>Customer / Toko</th>
                            <th style="text-align: right;">Total Nilai Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}</td>
                                <td><span style="font-family: monospace; font-size: 13px; font-weight: 700; color: #92400e;">{{ $sale->invoice_number }}</span></td>
                                <td><span style="font-weight: 600; color: #2c1a0e;">{{ $sale->customer_name ?? ($sale->customer->name ?? 'Umum / Retail') }}</span></td>
                                <td style="text-align: right; font-weight: 800; color: #2d6a4f;">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <svg style="width: 48px; height: 48px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <p style="font-size: 14px; font-weight: 600;">Belum ada data pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            <!-- 5. PENGAJUAN BARANG SALES -->
            @if($activeTab === 'order')
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <th>Sales Pengaju</th>
                            <th>Detail Produk Dipesan</th>
                            <th style="text-align: center; width: 150px;">Status</th>
                            <th style="text-align: right;">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                <td><span style="font-weight: 600; color: #1c0f05;">{{ $order->sales->name ?? '—' }}</span></td>
                                <td style="font-size: 13px; color: #6b4c35; line-height: 1.4;">
                                    @foreach($order->items as $item)
                                        <div style="margin-bottom: 2px;">
                                            • {{ $item->product->name ?? '—' }}: <strong>{{ number_format($item->qty, 0, ',', '.') }} pcs</strong>
                                        </div>
                                    @endforeach
                                </td>
                                <td style="text-align: center;">
                                    @if($order->status === 'disetujui')
                                        <span class="status-badge status-success">Disetujui</span>
                                    @elseif($order->status === 'ditolak')
                                        <span class="status-badge status-danger">Ditolak</span>
                                    @else
                                        <span class="status-badge status-warning">Menunggu</span>
                                    @endif
                                </td>
                                <td style="text-align: right; font-weight: 800; color: #92400e;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <svg style="width: 48px; height: 48px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p style="font-size: 14px; font-weight: 600;">Belum ada data pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

        </div>
    </div>

    <!-- Date Picker Integration script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateTrigger = document.getElementById('date-picker-trigger');
            const dateInput = document.getElementById('date-range-input');
            
            if (dateTrigger && dateInput) {
                const fp = flatpickr("#date-range-input", {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    defaultDate: ["{{ $startDate->format('Y-m-d') }}", "{{ $endDate->format('Y-m-d') }}"],
                    onOpen: function() {
                        dateTrigger.style.borderColor = '#92400e';
                        dateTrigger.style.boxShadow = '0 0 0 4px rgba(146, 64, 14, 0.05)';
                    },
                    onClose: function(selectedDates) {
                        dateTrigger.style.borderColor = '#e8d8c4';
                        dateTrigger.style.boxShadow = 'none';
                        
                        if (selectedDates.length === 2) {
                            const start = selectedDates[0].toLocaleDateString('en-CA');
                            const end = selectedDates[1].toLocaleDateString('en-CA');
                            document.getElementById('start_date').value = start;
                            document.getElementById('end_date').value = end;
                            
                            // Parse values into screen display
                            const startDisplay = selectedDates[0].toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                            const endDisplay = selectedDates[1].toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                            document.getElementById('date-display').textContent = `${startDisplay} - ${endDisplay}`;
                        }
                    }
                });

                dateTrigger.addEventListener('click', () => {
                    fp.open();
                });
            }
        });
    </script>
</x-layouts.admin>

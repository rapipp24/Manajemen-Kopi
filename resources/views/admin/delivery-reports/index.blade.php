<x-layouts.admin>
    <x-slot name="title">Laporan Pengiriman Sales</x-slot>

    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;flex-wrap:wrap;gap:16px;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.02em;margin-bottom:4px;">
                @if($activeTab === 'stock')
                    Stok di Sales
                @elseif($activeTab === 'delivered')
                    Barang Terkirim ke Toko
                @else
                    Laporan Pengiriman Sales
                @endif
            </h1>
            <p style="color:#64748b;font-size:13px;margin:0;">
                @if($activeTab === 'stock')
                    Daftar stok produk yang masih dipegang masing-masing sales.
                @elseif($activeTab === 'delivered')
                    Rekap barang yang sudah dikirim sales ke toko berdasarkan Laporan Pengiriman.
                @else
                    Semua laporan pengiriman dari seluruh sales ke toko.
                @endif
            </p>
        </div>
        
        <form method="GET" action="{{ route('admin.delivery-reports.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            
            <!-- Filter Sales (Semua Tab) -->
            <select name="sales_id" style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:13px;outline:none;background:white;">
                <option value="">Semua Sales</option>
                @foreach($salesUsers as $sales)
                    <option value="{{ $sales->id }}" {{ request('sales_id') == $sales->id ? 'selected' : '' }}>
                        {{ $sales->name }}
                    </option>
                @endforeach
            </select>
            
            <!-- Filter Produk (Hanya Tab Stok & Barang Terkirim) -->
            @if($activeTab === 'stock' || $activeTab === 'delivered')
                <select name="product_id" style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:13px;outline:none;background:white;">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} {{ $product->variant ? '('.$product->variant.')' : '' }}
                        </option>
                    @endforeach
                </select>
            @endif

            <!-- Filter Status Stok (Hanya Tab Stok) -->
            @if($activeTab === 'stock')
                <select name="stock_status" style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:13px;outline:none;background:white;">
                    <option value="" {{ request('stock_status') === '' ? 'selected' : '' }}>Semua Stok</option>
                    <option value="available" {{ request('stock_status') === 'available' ? 'selected' : '' }}>Stok Tersedia (> 0)</option>
                    <option value="empty" {{ request('stock_status') === 'empty' ? 'selected' : '' }}>Stok Kosong (≤ 0)</option>
                </select>
                
                <select name="sort_stock" style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:13px;outline:none;background:white;">
                    <option value="desc" {{ request('sort_stock') !== 'asc' ? 'selected' : '' }}>Stok Terbesar</option>
                    <option value="asc" {{ request('sort_stock') === 'asc' ? 'selected' : '' }}>Stok Terkecil</option>
                </select>
            @endif
            
            <!-- Filter Tanggal (Tab Laporan Pengiriman & Barang Terkirim) -->
            @if($activeTab === 'delivery' || $activeTab === 'delivered')
                <input type="date" name="date" value="{{ request('date') }}" style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:13px;outline:none;background:white;">
            @endif
            
            <button type="submit" style="padding:8px 14px;background:#0f172a;color:white;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">Filter</button>
            @if(request()->hasAny(['sales_id', 'product_id', 'stock_status', 'sort_stock', 'date']))
                <a href="{{ route('admin.delivery-reports.index', ['tab' => $activeTab]) }}" style="padding:8px 14px;background:#f1f5f9;color:#64748b;text-decoration:none;border-radius:6px;font-size:13px;font-weight:600;">Reset</a>
            @endif
        </form>
    </div>

    <style>
        .report-tabs {
            display: flex;
            background: #f7f0e6;
            border-radius: 20px;
            padding: 6px;
            gap: 8px;
            overflow-x: auto;
            border: 1px solid #e8d8c4;
            scrollbar-width: none;
            margin-bottom: 24px;
        }
        .report-tabs::-webkit-scrollbar {
            display: none;
        }
        .tab-btn {
            padding: 12px 24px;
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 600;
            color: #6b4c35;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid #d4c3b3;
            background: transparent;
            cursor: pointer;
        }
        .tab-btn:hover {
            color: #92400e;
            background: rgba(255, 255, 255, 0.5);
            border-color: #92400e;
        }
        .tab-btn.active {
            background: white;
            color: #92400e;
            font-weight: 800;
            border-color: #92400e;
            box-shadow: 0 4px 12px rgba(120, 53, 15, 0.08);
        }
    </style>

    <!-- Tabs Navigation -->
    <div class="report-tabs">
        <a href="{{ route('admin.delivery-reports.index', ['tab' => 'delivery'] + request()->except(['tab', 'page'])) }}" 
           class="tab-btn {{ $activeTab === 'delivery' ? 'active' : '' }}">
            Laporan Pengiriman
        </a>
        <a href="{{ route('admin.delivery-reports.index', ['tab' => 'stock'] + request()->except(['tab', 'page'])) }}" 
           class="tab-btn {{ $activeTab === 'stock' ? 'active' : '' }}">
            Stok di Sales
        </a>
        <a href="{{ route('admin.delivery-reports.index', ['tab' => 'delivered'] + request()->except(['tab', 'page'])) }}" 
           class="tab-btn {{ $activeTab === 'delivered' ? 'active' : '' }}">
            Barang Terkirim ke Toko
        </a>
    </div>

    <!-- Tab Content -->
    @if($activeTab === 'stock')
        <!-- Summary Cards Stok di Sales -->
        @if($salesStockSummary && $salesStockSummary->count() > 0)
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));gap:16px;margin-bottom:24px;">
                @foreach($salesStockSummary as $summary)
                    <div style="background:white;border:1px solid #e2e8f0;border-radius:8px;padding:14px 18px;box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                        <span style="font-size:11px;color:#64748b;font-weight:700;text-transform:uppercase;display:block;margin-bottom:4px;">{{ $summary->user->name ?? 'Sales' }}</span>
                        <span style="font-size:20px;font-weight:800;color:#0f172a;">
                            {{ number_format($summary->total_qty, 0, ',', '.') }} <span style="font-size:12px;font-weight:500;color:#64748b;">pcs</span>
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Table Stok di Sales -->
        <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Sales</th>
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Produk</th>
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Varian / Satuan</th>
                        <th style="padding:12px 18px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Qty Stok di Sales</th>
                        <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Terakhir Update</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesStocks as $stock)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:14px 18px;font-weight:600;color:#0f172a;">
                            {{ $stock->user->name ?? '—' }}
                        </td>
                        <td style="padding:14px 18px;font-weight:600;color:#0f172a;">
                            {{ $stock->product->name ?? '—' }}
                        </td>
                        <td style="padding:14px 18px;color:#475569;font-size:13px;">
                            {{ $stock->product->variant ?? '—' }} ({{ $stock->product->unit->name ?? '—' }})
                        </td>
                        <td style="padding:14px 18px;text-align:right;font-weight:700;color:#0f172a;">
                            {{ number_format($stock->qty, 0, ',', '.') }} pcs
                        </td>
                        <td style="padding:14px 18px;text-align:center;color:#64748b;font-size:13px;">
                            {{ $stock->updated_at ? $stock->updated_at->format('d/m/Y H:i') : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:56px;text-align:center;color:#94a3b8;">
                            <i data-lucide="package-open" style="width:36px;height:36px;margin:0 auto 12px;display:block;opacity:0.3;"></i>
                            <p style="margin:0;font-size:14px;">Belum ada data stok di sales.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($salesStocks->hasPages())
            <div style="padding:14px 18px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                {{ $salesStocks->links() }}
            </div>
            @endif
        </div>

    @elseif($activeTab === 'delivered')
        <!-- Table Barang Terkirim ke Toko -->
        <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Sales</th>
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Toko Tujuan</th>
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Produk</th>
                        <th style="padding:12px 18px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Qty Dikirim</th>
                        <th style="padding:12px 18px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Qty Return Diterima</th>
                        <th style="padding:12px 18px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Qty Bersih Terkirim</th>
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">No. Delivery Report</th>
                        <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Tgl Kirim</th>
                        <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Status Tagihan</th>
                        <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveredItems as $item)
                    @php
                        $qtyDikirim = (float) $item->qty;
                        $qtyReturnDiterima = (float) $item->salesReturnItems
                            ->filter(fn($srItem) => $srItem->salesReturn?->status === 'diterima')
                            ->sum('qty_return');
                        $qtyBersihTerkirim = max(0.0, $qtyDikirim - $qtyReturnDiterima);
                        $report = $item->deliveryReport;
                    @endphp
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:14px 18px;font-weight:600;color:#0f172a;">
                            {{ $report->sales->name ?? '—' }}
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="font-weight:600;color:#0f172a;">{{ $report->toko_name }}</div>
                            @if($report && !$report->customer_id && $report->customer_name_manual)
                                <div style="font-size:10px;color:#94a3b8;margin-top:2px;">Input manual</div>
                            @endif
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="font-weight:600;color:#0f172a;">{{ $item->product->name ?? '—' }}</div>
                            <div style="font-size:11px;color:#64748b;margin-top:2px;">{{ $item->product->variant ?? '—' }}</div>
                        </td>
                        <td style="padding:14px 18px;text-align:right;color:#0f172a;">
                            {{ number_format($qtyDikirim, 0, ',', '.') }} pcs
                        </td>
                        <td style="padding:14px 18px;text-align:right;color:#b91c1c;font-weight:500;">
                            {{ number_format($qtyReturnDiterima, 0, ',', '.') }} pcs
                        </td>
                        <td style="padding:14px 18px;text-align:right;font-weight:700;color:#166534;">
                            {{ number_format($qtyBersihTerkirim, 0, ',', '.') }} pcs
                        </td>
                        <td style="padding:14px 18px;font-family:monospace;font-weight:700;color:#0f172a;font-size:13px;">
                            {{ $report->report_number ?? '—' }}
                        </td>
                        <td style="padding:14px 18px;text-align:center;color:#475569;font-size:13px;">
                            {{ $report ? \Carbon\Carbon::parse($report->delivery_date)->format('d/m/Y') : '—' }}
                        </td>
                        <td style="padding:14px 18px;text-align:center;">
                            @if($report)
                                @if($report->payment_status === 'lunas')
                                    <span style="background:#dcfce7;color:#166534;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">LUNAS</span>
                                @elseif($report->payment_status === 'dp')
                                    <span style="background:#fef08a;color:#854d0e;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">DP</span>
                                @else
                                    <span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">BELUM BAYAR</span>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td style="padding:14px 18px;text-align:center;">
                            @if($report)
                                <a href="{{ route('admin.delivery-reports.show', $report) }}"
                                   style="padding:5px 14px;border:1px solid #e2e8f0;border-radius:6px;color:#475569;text-decoration:none;font-size:12.5px;font-weight:600;">
                                    Detail
                                </a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="padding:56px;text-align:center;color:#94a3b8;">
                            <i data-lucide="shopping-bag" style="width:36px;height:36px;margin:0 auto 12px;display:block;opacity:0.3;"></i>
                            <p style="margin:0;font-size:14px;">Belum ada data barang terkirim ke toko.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($deliveredItems->hasPages())
            <div style="padding:14px 18px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                {{ $deliveredItems->links() }}
            </div>
            @endif
        </div>

    @else
        <!-- Table Laporan Pengiriman (Tab 1 - Existing) -->
        <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">No. Laporan</th>
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Sales</th>
                        <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Toko Tujuan</th>
                        <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Status</th>
                        <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Tgl Kirim</th>
                        <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Jatuh Tempo</th>
                        <th style="padding:12px 18px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Total Tagihan</th>
                        <th style="padding:12px 18px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Sisa Tagihan</th>
                        <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:14px 18px;font-family:monospace;font-weight:700;color:#0f172a;font-size:13px;">
                            {{ $report->report_number }}
                        </td>
                        <td style="padding:14px 18px;font-weight:600;color:#0f172a;">
                            {{ $report->sales->name ?? '—' }}
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="font-weight:600;color:#0f172a;">{{ $report->toko_name }}</div>
                            @if(!$report->customer_id && $report->customer_name_manual)
                                <div style="font-size:10px;color:#94a3b8;margin-top:2px;">Input manual</div>
                            @endif
                        </td>
                        <td style="padding:14px 18px;text-align:center;">
                            @if($report->payment_status === 'lunas')
                                <span style="background:#dcfce7;color:#166534;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">LUNAS</span>
                            @elseif($report->payment_status === 'dp')
                                <span style="background:#fef08a;color:#854d0e;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">DP</span>
                            @else
                                <span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">BELUM BAYAR</span>
                            @endif
                        </td>
                        <td style="padding:14px 18px;text-align:center;color:#475569;font-size:13px;">
                            {{ \Carbon\Carbon::parse($report->delivery_date)->format('d/m/Y') }}
                        </td>
                        <td style="padding:14px 18px;text-align:center;color:#b91c1c;font-size:13px;font-weight:600;">
                            {{ $report->due_date ? \Carbon\Carbon::parse($report->due_date)->format('d/m/Y') : '—' }}
                        </td>
                        <td style="padding:14px 18px;text-align:right;font-weight:600;color:#0f172a;">
                            Rp {{ number_format($report->total_amount, 0, ',', '.') }}
                        </td>
                        <td style="padding:14px 18px;text-align:right;font-weight:700;color:#92400e;">
                            Rp {{ number_format($report->remaining_amount, 0, ',', '.') }}
                        </td>
                        <td style="padding:14px 18px;text-align:center;">
                            <a href="{{ route('admin.delivery-reports.show', $report) }}"
                               style="padding:5px 14px;border:1px solid #e2e8f0;border-radius:6px;color:#475569;text-decoration:none;font-size:12.5px;font-weight:600;">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding:56px;text-align:center;color:#94a3b8;">
                            <i data-lucide="truck" style="width:36px;height:36px;margin:0 auto 12px;display:block;opacity:0.3;"></i>
                            <p style="margin:0;font-size:14px;">Belum ada laporan pengiriman dari sales.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($reports->hasPages())
            <div style="padding:14px 18px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    @endif
</x-layouts.admin>

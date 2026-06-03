<x-layouts.admin>
    <x-slot name="title">Review Pengajuan Barang #{{ $salesOrder->order_number }}</x-slot>

    <style>
        /* ── Responsive Layout ──────────────────────────── */
        .so-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            align-items: start;
            margin-bottom: 50px;
        }

        /* ── Card shell ─────────────────────────────────── */
        .so-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }
        .so-card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .so-card-header h3 {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        /* ── Desktop table (hidden on mobile) ───────────── */
        .so-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .so-table-wrap table {
            width: 100%;
            border-collapse: collapse;
            min-width: 520px;
        }
        .so-table-wrap thead tr {
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }
        .so-table-wrap th {
            padding: 12px 24px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .so-table-wrap td {
            padding: 16px 24px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #0f172a;
        }
        .so-table-wrap tfoot td {
            padding: 16px 24px;
            background: #f8fafc;
        }

        /* ── Mobile item list (hidden on desktop) ────────── */
        .so-mobile-list { display: none; }

        .so-item-card {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
        }
        .so-item-card:last-child { border-bottom: none; }

        .so-item-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 14px;
            margin-bottom: 2px;
        }
        .so-item-sku {
            font-size: 11.5px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .so-item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 16px;
        }
        .so-item-meta-row {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12.5px;
        }
        .so-item-meta-label {
            color: #94a3b8;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }
        .so-item-meta-val {
            font-weight: 700;
            color: #0f172a;
        }
        .so-item-subtotal-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #e2e8f0;
        }
        .so-item-subtotal-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
        }
        .so-item-subtotal-val {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
        }

        /* ── Mobile total row ───────────────────────────── */
        .so-mobile-total {
            display: none;
            justify-content: space-between;
            align-items: center;
            padding: 14px 16px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        .so-mobile-total-label {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .so-mobile-total-val {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        /* ── Info rows ──────────────────────────────────── */
        .so-info-body { padding: 20px 24px; }
        .so-info-row { margin-bottom: 16px; }
        .so-info-row:last-child { margin-bottom: 0; }
        .so-info-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 4px;
            letter-spacing: 0.04em;
        }
        .so-info-val {
            font-weight: 600;
            color: #0f172a;
            font-size: 14px;
        }
        .so-info-sub {
            font-size: 12px;
            color: #475569;
            margin-bottom: 3px;
        }

        /* ── Status badge ───────────────────────────────── */
        .so-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        /* ── Catatan box ────────────────────────────────── */
        .so-catatan {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .so-catatan h4 {
            font-size: 11px;
            font-weight: 700;
            color: #92400e;
            margin: 0 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .so-catatan p {
            font-size: 14px;
            color: #92400e;
            margin: 0;
            line-height: 1.5;
        }

        /* ── Action buttons ─────────────────────────────── */
        .so-btn-approve {
            width: 100%; padding: 12px;
            background: #166534; color: white;
            border: none; border-radius: 10px;
            font-weight: 700; font-size: 14px;
            cursor: pointer; margin-bottom: 12px;
        }
        .so-btn-reject {
            width: 100%; padding: 10px;
            background: white; color: #ef4444;
            border: 1px solid #fee2e2; border-radius: 10px;
            font-weight: 600; font-size: 14px; cursor: pointer;
        }
        .so-btn-done {
            width: 100%; padding: 12px;
            background: #075985; color: white;
            border: none; border-radius: 10px;
            font-weight: 700; font-size: 14px; cursor: pointer;
        }

        /* ── Responsive breakpoints ─────────────────────── */
        @media (max-width: 768px) {
            .so-layout {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .so-card-header { padding: 14px 16px; }
            .so-card-header h3 { font-size: 14px; }

            /* Hide desktop table, show mobile list */
            .so-desktop-table { display: none !important; }
            .so-mobile-list   { display: block !important; }
            .so-mobile-total  { display: flex !important; }

            .so-info-body { padding: 16px; }
            .so-catatan { padding: 14px 16px; margin-bottom: 16px; }
        }

        @media (max-width: 430px) {
            .so-item-card { padding: 12px 14px; }
        }
    </style>

    {{-- ══ Page back-link ══════════════════════════════════ --}}
    <a href="{{ route('admin.sales-orders.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:20px;padding:6px 10px;border-radius:8px;transition:background 0.15s;"
       onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Pengajuan
    </a>

    <div class="so-layout">

        {{-- ══ KIRI: Daftar Barang ════════════════════════ --}}
        <div>
            <div class="so-card">
                <div class="so-card-header">
                    <h3>Daftar Barang yang Diminta</h3>
                </div>

                {{-- Desktop table --}}
                <div class="so-desktop-table so-table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th style="text-align:center;">Stok Gudang</th>
                                <th style="text-align:center;">Qty Minta</th>
                                <th style="text-align:right;">Estimasi Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesOrder->items as $item)
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:#0f172a;">{{ $item->product->name }}</div>
                                    <div style="font-size:12px;color:#64748b;">SKU: {{ $item->product->sku }}</div>
                                </td>
                                <td style="text-align:center;">
                                    <span style="font-weight:600;color:{{ $item->product->current_stock < $item->qty ? '#ef4444' : '#166534' }};">
                                        {{ number_format($item->product->current_stock, 0, ',', '.') }} {{ $item->product->unit->name ?? '' }}
                                    </span>
                                </td>
                                <td style="text-align:center;font-weight:700;font-size:15px;color:#0f172a;">
                                    {{ number_format($item->qty, 0, ',', '.') }}
                                </td>
                                <td style="text-align:right;font-weight:700;">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:700;color:#64748b;font-size:12px;text-transform:uppercase;">Total Nilai Barang</td>
                                <td style="text-align:right;font-size:18px;font-weight:800;color:#0f172a;">
                                    Rp {{ number_format($salesOrder->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Mobile card list --}}
                <div class="so-mobile-list">
                    @foreach($salesOrder->items as $item)
                    <div class="so-item-card">
                        <div class="so-item-name">{{ $item->product->name }}</div>
                        <div class="so-item-sku">SKU: {{ $item->product->sku }}</div>
                        <div class="so-item-meta">
                            <div class="so-item-meta-row">
                                <span class="so-item-meta-label">Stok Gudang</span>
                                <span class="so-item-meta-val" style="color:{{ $item->product->current_stock < $item->qty ? '#ef4444' : '#166534' }};">
                                    {{ number_format($item->product->current_stock, 0, ',', '.') }} {{ $item->product->unit->name ?? '' }}
                                </span>
                            </div>
                            <div class="so-item-meta-row">
                                <span class="so-item-meta-label">Qty Minta</span>
                                <span class="so-item-meta-val" style="color:#0f172a;">
                                    {{ number_format($item->qty, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="so-item-subtotal-row">
                            <span class="so-item-subtotal-label">Subtotal</span>
                            <span class="so-item-subtotal-val">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach

                    {{-- Mobile total --}}
                    <div class="so-mobile-total">
                        <span class="so-mobile-total-label">Total Nilai Barang</span>
                        <span class="so-mobile-total-val">Rp {{ number_format($salesOrder->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($salesOrder->catatan)
            <div class="so-catatan">
                <h4>Catatan Sales</h4>
                <p>{{ $salesOrder->catatan }}</p>
            </div>
            @endif
        </div>

        {{-- ══ KANAN: Info & Action ═══════════════════════ --}}
        <div>
            {{-- Info Pengajuan --}}
            <div class="so-card">
                <div class="so-card-header">
                    <h3>Info Pengajuan</h3>
                </div>
                <div class="so-info-body">
                    <div class="so-info-row">
                        <span class="so-info-label">No. Pengajuan</span>
                        <div class="so-info-val" style="font-family:monospace;font-size:13px;">{{ $salesOrder->order_number }}</div>
                    </div>
                    <div class="so-info-row">
                        <span class="so-info-label">Status</span>
                        @php
                            $statusColors = [
                                'menunggu'   => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Persetujuan'],
                                'diproses'   => ['bg' => '#dcfce7', 'text' => '#166534', 'label' => 'Disetujui & Diproses'],
                                'selesai'    => ['bg' => '#e0f2fe', 'text' => '#075985', 'label' => 'Selesai Diambil'],
                                'dibatalkan' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Ditolak / Batal'],
                            ];
                            $color = $statusColors[$salesOrder->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => $salesOrder->status];
                        @endphp
                        <span class="so-badge" style="background:{{ $color['bg'] }};color:{{ $color['text'] }};">
                            {{ $color['label'] }}
                        </span>
                    </div>
                    <div class="so-info-row">
                        <span class="so-info-label">Nama Sales</span>
                        <div class="so-info-val">{{ $salesOrder->sales->name }}</div>
                    </div>
                    <div class="so-info-row">
                        <span class="so-info-label">Tujuan / Toko</span>
                        <div class="so-info-val">{{ $salesOrder->customer->name ?? 'Stok Pribadi / Keliling' }}</div>
                    </div>
                    <div class="so-info-row" style="padding-top:14px;border-top:1px solid #f1f5f9;">
                        <span class="so-info-label">Log Waktu</span>
                        <div class="so-info-sub">Dibuat: {{ $salesOrder->created_at->format('d M Y H:i') }}</div>
                        @if($salesOrder->processed_at)
                            <div class="so-info-sub" style="color:#166534;">Disetujui: {{ $salesOrder->processed_at->format('d M Y H:i') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Approval Action --}}
            @if($salesOrder->status === 'menunggu' || $salesOrder->status === 'diproses')
            <div class="so-card" style="margin-bottom:0;">
                <div class="so-card-header">
                    <h3>Approval Admin</h3>
                </div>
                <div style="padding:20px 24px;">
                    <form action="{{ route('admin.sales-orders.update-status', $salesOrder) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        @if($salesOrder->status === 'menunggu')
                        <button type="button" name="status" value="diproses"
                                class="confirm-action so-btn-approve"
                                data-confirm-title="Setujui Pengajuan?"
                                data-confirm-text="Stok gudang akan langsung dikurangi untuk pengajuan ini."
                                data-confirm-icon="question">
                            Setujui &amp; Potong Stok
                        </button>

                        <button type="button" name="status" value="dibatalkan"
                                class="confirm-action so-btn-reject"
                                data-confirm-title="Tolak Pengajuan?"
                                data-confirm-text="Pengajuan ini akan dibatalkan dan tidak akan memotong stok."
                                data-confirm-icon="warning">
                            Tolak Pengajuan
                        </button>
                        @endif

                        @if($salesOrder->status === 'diproses')
                        <button type="button" name="status" value="selesai"
                                class="confirm-action so-btn-done"
                                data-confirm-title="Selesaikan Pengajuan?"
                                data-confirm-text="Tandai bahwa barang sudah benar-benar diambil oleh sales."
                                data-confirm-icon="info">
                            Barang Sudah Diambil
                        </button>
                        @endif
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>

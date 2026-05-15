<x-layouts.admin>
    <x-slot name="title">Kartu Stok - {{ $rawMaterial->name }}</x-slot>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .page-title h1 { font-size: 22px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
        .page-title p { font-size: 13px; color: var(--text-muted); }
        
        .card { background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { text-align: left; padding: 14px 20px; background: #fcfaf8; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        .table td { padding: 16px 20px; font-size: 14px; color: var(--text-main); border-bottom: 1px solid #f8fafc; }
        
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .badge-in { background: #f0fdf4; color: #166534; }
        .badge-out { background: #fef2f2; color: #991b1b; }
        
        .stock-summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
        .summary-card { padding: 20px; background: white; border: 1px solid var(--border); border-radius: 12px; }
        .summary-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; }
        .summary-value { font-size: 20px; font-weight: 800; color: var(--text-main); }
        
        .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: 1px solid var(--border); background: white; color: var(--text-mid); }
        .btn-back:hover { background: #f8fafc; }
    </style>

    <div class="page-header">
        <div class="page-title">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <a href="{{ route('admin.raw-materials.index') }}" class="btn-back" style="padding: 6px 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                </a>
                <h1 style="margin: 0;">Kartu Stok: {{ $rawMaterial->name }}</h1>
            </div>
            <p>Riwayat mutasi keluar masuk barang untuk kode <strong>{{ $rawMaterial->code }}</strong></p>
        </div>
    </div>

    <div class="stock-summary">
        <div class="summary-card" style="background: white; border-top: 4px solid #94a3b8;">
            <div class="summary-label">Stok Awal (Input)</div>
            <div class="summary-value">0 <span style="font-size: 12px; color: var(--text-muted);">{{ $rawMaterial->unit->code }}</span></div>
        </div>
        <div class="summary-card" style="background: #fdfaf7; border-top: 4px solid #92400e;">
            <div class="summary-label">Stok Saat Ini</div>
            <div class="summary-value" style="color: #92400e;">
                @php
                    $stock = $rawMaterial->current_stock;
                    $unit = $rawMaterial->unit->code ?? 'kg';
                    $displayStock = number_format($stock, 2, ',', '.');
                    if (strtolower($unit) == 'kg' && $stock >= 1000) {
                        $displayStock = number_format($stock / 1000, 2, ',', '.') . ' <span style="font-size: 14px;">Ton</span>';
                    } else {
                        $displayStock = $displayStock . ' <span style="font-size: 12px;">' . $unit . '</span>';
                    }
                @endphp
                {!! $displayStock !!}
            </div>
        </div>
        <div class="summary-card" style="background: white; border-top: 4px solid #64748b;">
            <div class="summary-label">Total Mutasi</div>
            <div class="summary-value">{{ $movements->total() }} <span style="font-size: 12px; color: var(--text-muted);">Kejadian</span></div>
        </div>
    </div>

    <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <table class="table">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 14px 20px;">Waktu & Tanggal</th>
                    <th style="padding: 14px 20px;">Jenis</th>
                    <th style="padding: 14px 20px; text-align: right;">Stok Sebelum</th>
                    <th style="padding: 14px 20px; text-align: right;">Jumlah (In/Out)</th>
                    <th style="padding: 14px 20px; text-align: right;">Stok Sesudah</th>
                    <th style="padding: 14px 20px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                    <tr style="transition: background 0.2s;">
                        <td style="padding: 16px 20px;">
                            <div style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $m->created_at->format('d M Y') }}</div>
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">{{ $m->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td style="padding: 16px 20px;">
                            @if($m->movement_type == 'in')
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0fdf4; color: #166534; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; border: 1px solid #dcfce7;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 10px; height: 10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" /></svg>
                                    MASUK
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #fef2f2; color: #991b1b; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; border: 1px solid #fee2e2;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 10px; height: 10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" /></svg>
                                    KELUAR
                                </span>
                            @endif
                        </td>
                        <td style="padding: 16px 20px; text-align: right; color: #64748b; font-family: monospace; font-size: 13px;">
                            {{ number_format($m->stock_before, 2, ',', '.') }}
                        </td>
                        <td style="padding: 16px 20px; text-align: right;">
                            <div style="font-size: 15px; font-weight: 800; color: {{ $m->movement_type == 'in' ? '#166534' : '#be123c' }};">
                                {{ $m->movement_type == 'in' ? '+' : '-' }}{{ number_format($m->qty, 2, ',', '.') }}
                            </div>
                        </td>
                        <td style="padding: 16px 20px; text-align: right; font-size: 15px; font-weight: 800; color: #1e293b; font-family: monospace;">
                            {{ number_format($m->stock_after, 2, ',', '.') }}
                        </td>
                        <td style="padding: 16px 20px;">
                            @php
                                preg_match('/RCP-[\d-]+/', $m->note ?? '', $matches);
                                $rcpNumber = $matches[0] ?? null;
                            @endphp
                            @if($rcpNumber)
                                <div style="font-size: 13px; color: #475569;">
                                    {{ Str::before($m->note, $rcpNumber) }}
                                    <a href="{{ route('admin.raw-material-receipts.show', $rcpNumber) }}"
                                       style="background: #fdf4ff; color: #701a75; padding: 2px 6px; border-radius: 4px; font-weight: 700; text-decoration: none; border: 1px solid #fae8ff;"
                                       title="Lihat detail penerimaan {{ $rcpNumber }}">
                                        {{ $rcpNumber }}
                                    </a>
                                </div>
                            @else
                                <div style="font-size: 13px; color: #475569; line-height: 1.5;">{{ $m->note }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 60px; color: #94a3b8; font-size: 14px;">
                            Belum ada riwayat mutasi untuk bahan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $movements->links() }}
    </div>
</x-layouts.admin>

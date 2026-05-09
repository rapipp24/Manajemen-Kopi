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
        <div class="summary-card">
            <div class="summary-label">Stok Awal (Input Sistem)</div>
            <div class="summary-value">0 <span style="font-size: 12px; color: var(--text-muted);">{{ $rawMaterial->unit->code }}</span></div>
        </div>
        <div class="summary-card" style="border-left: 4px solid var(--brown-400);">
            <div class="summary-label">Stok Saat Ini</div>
            <div class="summary-value">{{ number_format($rawMaterial->current_stock, 2, ',', '.') }} <span style="font-size: 12px; color: var(--text-muted);">{{ $rawMaterial->unit->code }}</span></div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Total Mutasi</div>
            <div class="summary-value">{{ $movements->total() }} <span style="font-size: 12px; color: var(--text-muted);">Kejadian</span></div>
        </div>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu & Tanggal</th>
                    <th>Tipe</th>
                    <th style="text-align: right;">Stok Sebelum</th>
                    <th style="text-align: right;">Jumlah (In/Out)</th>
                    <th style="text-align: right;">Stok Sesudah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                    <tr>
                        <td style="color: var(--text-muted); font-size: 13px;">
                            <div style="color: var(--text-main); font-weight: 600;">{{ $m->created_at->format('d M Y') }}</div>
                            {{ $m->created_at->format('H:i') }} WIB
                        </td>
                        <td>
                            <span class="badge {{ $m->movement_type == 'in' ? 'badge-in' : 'badge-out' }}">
                                {{ $m->movement_type == 'in' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td style="text-align: right; color: var(--text-muted);">{{ number_format($m->stock_before, 2, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 800; color: {{ $m->movement_type == 'in' ? '#166534' : '#991b1b' }};">
                            {{ $m->movement_type == 'in' ? '+' : '-' }}{{ number_format($m->qty, 2, ',', '.') }}
                        </td>
                        <td style="text-align: right; font-weight: 800;">{{ number_format($m->stock_after, 2, ',', '.') }}</td>
                        <td>
                            @php
                                // Ekstrak nomor RCP dari teks keterangan jika ada
                                preg_match('/RCP-[\d-]+/', $m->note ?? '', $matches);
                                $rcpNumber = $matches[0] ?? null;
                            @endphp
                            @if($rcpNumber)
                                <div style="font-size: 13px; color: var(--text-mid);">
                                    {{ Str::before($m->note, $rcpNumber) }}
                                    <a href="{{ route('admin.raw-material-receipts.show', $rcpNumber) }}"
                                       style="color: var(--brown-400); font-weight: 700; text-decoration: none;"
                                       title="Lihat detail penerimaan {{ $rcpNumber }}">
                                        {{ $rcpNumber }}
                                    </a>
                                </div>
                            @else
                                <div style="font-size: 13px; color: var(--text-mid);">{{ $m->note }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
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

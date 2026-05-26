<x-layouts.admin>
    <x-slot name="title">Kartu Stok - {{ $rawMaterial->name }}</x-slot>

    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="{{ route('admin.raw-materials.index') }}" 
               style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 12px; border: 1px solid var(--border); background: var(--cream-100); color: var(--text-mid); text-decoration: none; transition: all 0.2s;"
               onmouseover="this.style.background='var(--cream-200)'" onmouseout="this.style.background='var(--cream-100)'"
               title="Kembali ke Daftar Bahan Baku">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 style="font-size: 20px; font-weight: 700; color: var(--text-main); margin: 0;">Kartu Stok: {{ $rawMaterial->name }}</h1>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Riwayat mutasi keluar masuk barang untuk kode <strong style="font-family: monospace;">{{ $rawMaterial->code }}</strong></p>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
        <div style="padding: 20px; background: white; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02); border-top: 4px solid var(--text-muted);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Stok Awal (Input)</div>
            <div style="font-size: 20px; font-weight: 800; color: var(--text-main);">0 <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">{{ $rawMaterial->unit->code }}</span></div>
        </div>
        <div style="padding: 20px; background: #fffdfa; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02); border-top: 4px solid var(--brown-500);">
            <div style="font-size: 11px; font-weight: 700; color: var(--brown-500); text-transform: uppercase; margin-bottom: 8px;">Stok Saat Ini</div>
            <div style="font-size: 20px; font-weight: 800; color: var(--brown-500);">
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
        <div style="padding: 20px; background: white; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02); border-top: 4px solid var(--text-mid);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-mid); text-transform: uppercase; margin-bottom: 8px;">Total Mutasi</div>
            <div style="font-size: 20px; font-weight: 800; color: var(--text-main);">{{ $movements->total() }} <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Kejadian</span></div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Waktu & Tanggal</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Jenis</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Stok Sebelum</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Jumlah (In/Out)</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Stok Sesudah</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                    <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fffdfb'" onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 16px 20px;">
                            <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $m->created_at->format('d M Y') }}</div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">{{ $m->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td style="padding: 16px 20px;">
                            @if($m->movement_type == 'in')
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0fdf4; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; border: 1px solid #bbf7d0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 10px; height: 10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" /></svg>
                                    MASUK
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #fff5f5; color: #be123c; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; border: 1px solid #fecaca;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 10px; height: 10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" /></svg>
                                    KELUAR
                                </span>
                            @endif
                        </td>
                        <td style="padding: 16px 20px; text-align: right; color: var(--text-muted); font-family: monospace; font-size: 13px; font-weight: 600;">
                            {{ number_format($m->stock_before, 2, ',', '.') }}
                        </td>
                        <td style="padding: 16px 20px; text-align: right;">
                            <div style="font-size: 15px; font-weight: 800; color: {{ $m->movement_type == 'in' ? '#166534' : '#be123c' }};">
                                {{ $m->movement_type == 'in' ? '+' : '-' }}{{ number_format($m->qty, 2, ',', '.') }}
                            </div>
                        </td>
                        <td style="padding: 16px 20px; text-align: right; font-size: 15px; font-weight: 800; color: var(--text-main); font-family: monospace;">
                            {{ number_format($m->stock_after, 2, ',', '.') }}
                        </td>
                        <td style="padding: 16px 20px;">
                            @php
                                preg_match('/RCP-[\d-]+/', $m->note ?? '', $matches);
                                $rcpNumber = $matches[0] ?? null;
                            @endphp
                            @if($rcpNumber)
                                <div style="font-size: 13.5px; color: var(--text-mid); line-height: 1.5;">
                                    {{ Str::before($m->note, $rcpNumber) }}
                                    <a href="{{ route('admin.raw-material-receipts.show', $rcpNumber) }}"
                                       style="background: #fffdf5; color: var(--brown-500); padding: 2px 8px; border-radius: 6px; font-weight: 700; text-decoration: none; border: 1px solid var(--border);"
                                       title="Lihat detail penerimaan {{ $rcpNumber }}">
                                        {{ $rcpNumber }}
                                    </a>
                                </div>
                            @else
                                <div style="font-size: 13.5px; color: var(--text-mid); line-height: 1.5;">{{ $m->note }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px; color: var(--text-muted); font-size: 14px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.35; color: var(--text-muted);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div>Belum ada riwayat mutasi untuk bahan baku ini.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($movements->hasPages())
    <div style="margin-top: 20px;">
        {{ $movements->links() }}
    </div>
    @endif
</x-layouts.admin>

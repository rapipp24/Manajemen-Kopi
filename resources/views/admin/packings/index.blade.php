<x-layouts.admin>
    <x-slot name="title">Daftar Packing</x-slot>

    {{-- Flash error --}}
    @if(session('error'))
    <div style="background:#fff1f2;border:1px solid #fecaca;color:#be123c;padding:14px 18px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600;">
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:14px 18px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Stok Curah per Jenis --}}
    @if(count($curahStocks) > 0)
    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
        @foreach($curahStocks as $jenis => $stok)
        <div style="background:white;border:1px solid #e2e8f0;border-radius:10px;padding:14px 20px;min-width:180px;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <p style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">Curah: {{ $jenis }}</p>
            <p style="font-size:20px;font-weight:800;color:{{ $stok > 0 ? '#166534' : '#dc2626' }};">{{ number_format($stok, 2) }} <span style="font-size:13px;font-weight:500;">kg</span></p>
        </div>
        @endforeach
    </div>
    @else
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;margin-bottom:18px;font-size:13px;color:#92400e;font-weight:600;">
        Belum ada data produksi. Tambah produksi terlebih dahulu sebelum melakukan packing.
    </div>
    @endif

    {{-- Card utama --}}
    <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="padding:20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;background:#f8fafc;">
            <div>
                <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin:0;">Daftar Packing</h3>
                <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Riwayat packing produk jadi dari curah.</p>
            </div>
            <a href="{{ route('admin.packings.create') }}"
               style="background:#92400e;color:white;text-decoration:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Packing
            </a>
        </div>

        <table style="width:100%;border-collapse:collapse;text-align:left;">
            <thead>
                <tr style="background:#f1f5f9;border-bottom:1px solid #e2e8f0;">
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">No. Packing</th>
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Tanggal</th>
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Jenis Curah</th>
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Jumlah Item</th>
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Catatan</th>
                    <th style="padding:14px 20px;text-align:right;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packings as $packing)
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:14px 20px;">
                        <span style="font-family:monospace;font-size:13px;font-weight:700;color:#92400e;background:#fef3c7;padding:3px 8px;border-radius:5px;">
                            {{ $packing->packing_number }}
                        </span>
                    </td>
                    <td style="padding:14px 20px;font-size:13px;color:#475569;">
                        {{ \Carbon\Carbon::parse($packing->packing_date)->format('d M Y') }}
                    </td>
                    <td style="padding:14px 20px;">
                        @if($packing->curah_type)
                            <span style="font-size:12px;font-weight:600;background:#e0f2fe;color:#0369a1;padding:3px 9px;border-radius:20px;border:1px solid #bae6fd;">
                                {{ $packing->curah_type }}
                            </span>
                        @else
                            <span style="color:#94a3b8;font-size:13px;">-</span>
                        @endif
                    </td>
                    <td style="padding:14px 20px;font-size:13px;color:#1e293b;font-weight:600;">
                        {{ $packing->items->count() }} produk
                    </td>
                    <td style="padding:14px 20px;font-size:13px;color:#64748b;">
                        {{ $packing->note ? \Illuminate\Support\Str::limit($packing->note, 40) : '-' }}
                    </td>
                    <td style="padding:14px 20px;text-align:right;">
                        <a href="{{ route('admin.packings.show', $packing) }}"
                           style="color:#0284c7;text-decoration:none;font-size:13px;font-weight:600;padding:6px 12px;background:#f0f9ff;border-radius:6px;border:1px solid #e0f2fe;">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:50px;text-align:center;color:#94a3b8;font-size:14px;">
                        Belum ada data packing.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($packings->hasPages())
        <div style="padding:15px 20px;border-top:1px solid #e2e8f0;background:#f8fafc;">
            {{ $packings->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>

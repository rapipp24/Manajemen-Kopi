<x-layouts.user>
    <x-slot name="title">Daftar Return Saya</x-slot>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:20px;font-weight:800;color:#1c1917;margin:0;">Return Barang</h1>
            <p style="font-size:13px;color:#78716c;margin:4px 0 0;">Daftar pengajuan return barang yang kamu kirim ke toko.</p>
        </div>
        <a href="{{ route('sales.returns.create') }}"
           style="background:#92400e;color:#fff;text-decoration:none;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
            + Ajukan Return
        </a>
    </div>

    @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #86efac;border-left:3px solid #22c55e;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fff5f5;border:1px solid #fca5a5;border-left:3px solid #ef4444;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background:#fff;border:1px solid #ece8e3;border-radius:12px;overflow:hidden;">
        @if($returns->isEmpty())
            <div style="padding:48px 24px;text-align:center;color:#a8a29e;">
                <i data-lucide="package" style="width:36px;height:36px;color:#a8a29e;margin:0 auto 12px;display:block;"></i>
                <div style="font-size:14px;font-weight:600;color:#57534e;">Belum ada pengajuan return.</div>
                <div style="font-size:13px;color:#a8a29e;margin-top:4px;">Ajukan return jika ada barang yang dikembalikan toko.</div>
            </div>
        @else
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#fafaf8;border-bottom:1px solid #ece8e3;">
                        <th style="padding:11px 18px;text-align:left;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.06em;">No. Return</th>
                        <th style="padding:11px 18px;text-align:left;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.06em;">Laporan</th>
                        <th style="padding:11px 18px;text-align:left;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.06em;">Tgl Return</th>
                        <th style="padding:11px 18px;text-align:right;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.06em;">Total Return</th>
                        <th style="padding:11px 18px;text-align:center;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.06em;">Status</th>
                        <th style="padding:11px 18px;text-align:center;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.06em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $ret)
                    <tr style="border-bottom:1px solid #f5f0eb;">
                        <td style="padding:13px 18px;font-family:monospace;font-weight:700;font-size:12.5px;color:#92400e;">
                            {{ $ret->return_number }}
                        </td>
                        <td style="padding:13px 18px;font-size:13px;color:#57534e;">
                            {{ $ret->deliveryReport->report_number ?? '—' }}
                        </td>
                        <td style="padding:13px 18px;font-size:13px;color:#57534e;">
                            {{ $ret->return_date->format('d M Y') }}
                        </td>
                        <td style="padding:13px 18px;text-align:right;font-weight:700;color:#1c1917;font-size:13px;">
                            Rp {{ number_format($ret->total_return, 0, ',', '.') }}
                        </td>
                        <td style="padding:13px 18px;text-align:center;">
                            @if($ret->status === 'diterima')
                                <span style="background:#dcfce7;color:#166534;font-size:10px;font-weight:700;padding:3px 8px;border-radius:6px;">DITERIMA</span>
                            @elseif($ret->status === 'ditolak')
                                <span style="background:#fee2e2;color:#991b1b;font-size:10px;font-weight:700;padding:3px 8px;border-radius:6px;">DITOLAK</span>
                            @else
                                <span style="background:#fef08a;color:#854d0e;font-size:10px;font-weight:700;padding:3px 8px;border-radius:6px;">MENUNGGU</span>
                            @endif
                        </td>
                        <td style="padding:13px 18px;text-align:center;">
                            <a href="{{ route('sales.returns.show', $ret) }}"
                               style="background:#fff;border:1px solid #d6d3d1;color:#57534e;text-decoration:none;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding:16px 18px;">
                {{ $returns->links() }}
            </div>
        @endif
    </div>
</x-layouts.user>

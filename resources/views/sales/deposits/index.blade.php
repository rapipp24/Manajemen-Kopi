<x-layouts.user>
    <x-slot name="title">Setoran Saya</x-slot>

    <style>
        .page-header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px; }
        .page-title  { font-size:20px;font-weight:700;color:#1c1917;letter-spacing:-0.03em; }
        .page-desc   { font-size:13px;color:#78716c;margin-top:3px; }

        .btn-primary {
            background:#92400e;color:#fff;padding:9px 18px;border-radius:9px;
            text-decoration:none;font-size:13px;font-weight:600;
            display:inline-flex;align-items:center;gap:7px;
            transition:background 0.15s,box-shadow 0.15s;white-space:nowrap;
            box-shadow:0 1px 3px rgba(146,64,14,0.25);
        }
        .btn-primary:hover { background:#78350f;box-shadow:0 3px 8px rgba(146,64,14,0.3); }

        .table-card { background:#fff;border:1px solid #ece8e3;border-radius:12px;overflow:hidden; }
        .table-title { padding:14px 18px;border-bottom:1px solid #f5f0eb;font-size:13px;font-weight:700;color:#1c1917; }

        table { width:100%;border-collapse:collapse; }
        thead tr { background:#fafaf8;border-bottom:1px solid #ece8e3; }
        th { padding:10px 16px;text-align:left;font-size:10px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.07em; }
        td { padding:13px 16px;border-bottom:1px solid #f5f0eb;font-size:13px;color:#44403c;vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#fdfcfb; }

        .btn-link { font-size:12.5px;font-weight:600;color:#92400e;text-decoration:none; }
        .btn-link:hover { text-decoration:underline; }

        .empty-wrap { padding:56px 20px;text-align:center; }
        .empty-emoji { font-size:34px;margin-bottom:10px;opacity:0.25; }
        .empty-title { font-size:14px;font-weight:600;color:#78716c;margin-bottom:8px; }
        .empty-cta { font-size:13px;color:#92400e;font-weight:600;text-decoration:none; }
        .empty-cta:hover { text-decoration:underline; }

        .alert-success {
            background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;
            padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:20px;
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Setoran Saya</h1>
            <p class="page-desc">Daftar uang tagihan yang telah Anda setorkan ke admin.</p>
        </div>
        <a href="{{ route('sales.deposits.create') }}" class="btn-primary">
            + Kirim Setoran Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-card">
        <div class="table-title">Daftar Setoran Uang</div>
        <table>
            <thead>
                <tr>
                    <th>No. Setoran</th>
                    <th>Laporan Pengiriman</th>
                    <th>Toko</th>
                    <th>Nominal</th>
                    <th>Tgl Bayar</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $d)
                <tr>
                    <td>
                        <span style="font-family:monospace;font-weight:700;color:#1c1917;font-size:12px;">{{ $d->deposit_number }}</span>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-weight:600;color:#78716c;font-size:12px;">
                            {{ $d->deliveryReport->report_number }}
                        </span>
                    </td>
                    <td style="font-weight:600;">
                        {{ $d->deliveryReport->toko_name }}
                    </td>
                    <td style="font-weight:700;color:#15803d;">
                        Rp {{ number_format($d->amount, 0, ',', '.') }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($d->payment_date)->format('d M Y') }}
                    </td>
                    <td>
                        @if($d->status === 'disetujui')
                            <span style="background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:4px 8px;border-radius:6px;">Disetujui</span>
                        @elseif($d->status === 'ditolak')
                            <span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:600;padding:4px 8px;border-radius:6px;">Ditolak</span>
                        @else
                            <span style="background:#fef08a;color:#854d0e;font-size:11px;font-weight:600;padding:4px 8px;border-radius:6px;">Menunggu Verifikasi</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('sales.deposits.show', $d) }}" class="btn-link">Detail →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-wrap">
                            <div class="empty-emoji">💰</div>
                            <div class="empty-title">Belum ada riwayat setoran uang</div>
                            <a href="{{ route('sales.deposits.create') }}" class="empty-cta">Ajukan Setoran Pertama →</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($deposits->hasPages())
    <div style="margin-top:16px;">
        {{ $deposits->links() }}
    </div>
    @endif
</x-layouts.user>

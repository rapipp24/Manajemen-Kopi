<x-layouts.admin>
    <x-slot name="title">Setoran Sales</x-slot>

    <div style="margin-bottom:24px;display:flex;justify-content:space-between;align-items:flex-start;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.03em;">Verifikasi Setoran Sales</h1>
            <p style="font-size:13.5px;color:#64748b;margin-top:4px;">Daftar uang tagihan toko yang disetorkan oleh sales lapangan untuk diverifikasi.</p>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#166534;padding:12px 16px;border-radius:8px;font-size:13.5px;margin-bottom:20px;font-weight:500;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;padding:12px 16px;border-radius:8px;font-size:13.5px;margin-bottom:20px;font-weight:500;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter Card -->
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin-bottom:20px;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <form action="{{ route('admin.sales-deposits.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
            <div style="display:flex;flex-direction:column;gap:6px;min-width:200px;">
                <label style="font-size:12px;font-weight:700;color:#475569;">Pilih Sales</label>
                <select name="sales_id" style="padding:8px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;color:#334155;background:#fff;width:100%;">
                    <option value="">Semua Sales</option>
                    @foreach($salesUsers as $u)
                        <option value="{{ $u->id }}" {{ request('sales_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;flex-direction:column;gap:6px;min-width:180px;">
                <label style="font-size:12px;font-weight:700;color:#475569;">Status Verifikasi</label>
                <select name="status" style="padding:8px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;color:#334155;background:#fff;width:100%;">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" style="background:#92400e;color:#fff;border:none;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">
                Filter
            </button>
            @if(request()->filled('sales_id') || request()->filled('status'))
                <a href="{{ route('admin.sales-deposits.index') }}" style="background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                    <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">No. Setoran</th>
                    <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Sales</th>
                    <th style="padding:12px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Toko</th>
                    <th style="padding:12px 18px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Nominal</th>
                    <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Tgl Setor</th>
                    <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Status</th>
                    <th style="padding:12px 18px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $d)
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:14px 18px;font-family:monospace;font-weight:700;color:#0f172a;font-size:13px;">
                        {{ $d->deposit_number }}
                    </td>
                    <td style="padding:14px 18px;font-weight:600;color:#0f172a;">
                        {{ $d->sales->name }}
                    </td>
                    <td style="padding:14px 18px;">
                        <div style="font-weight:600;color:#0f172a;">{{ $d->deliveryReport->toko_name }}</div>
                        <div style="font-size:10px;font-family:monospace;color:#94a3b8;margin-top:2px;">{{ $d->deliveryReport->report_number }}</div>
                    </td>
                    <td style="padding:14px 18px;text-align:right;font-weight:700;color:#166534;font-size:14px;">
                        Rp {{ number_format($d->amount, 0, ',', '.') }}
                    </td>
                    <td style="padding:14px 18px;text-align:center;color:#475569;font-size:13px;">
                        {{ \Carbon\Carbon::parse($d->payment_date)->format('d/m/Y') }}
                    </td>
                    <td style="padding:14px 18px;text-align:center;">
                        @if($d->status === 'disetujui')
                            <span style="background:#dcfce7;color:#166534;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">DISETUJUI</span>
                        @elseif($d->status === 'ditolak')
                            <span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">DITOLAK</span>
                        @else
                            <span style="background:#fef08a;color:#854d0e;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">PENDING</span>
                        @endif
                    </td>
                    <td style="padding:14px 18px;text-align:center;">
                        <a href="{{ route('admin.sales-deposits.show', $d) }}"
                           style="padding:5px 14px;border:1px solid #cbd5e1;border-radius:6px;color:#475569;text-decoration:none;font-size:12.5px;font-weight:600;background:#fff;transition:all 0.15s;">
                            Detail / Verifikasi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:56px;text-align:center;color:#94a3b8;">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px;display:block;opacity:0.3;"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <p style="margin:0;font-size:14px;">Belum ada setoran masuk dari sales.</p>
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
</x-layouts.admin>

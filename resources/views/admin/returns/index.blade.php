<x-layouts.admin>
    <x-slot name="title">Daftar Return Sales</x-slot>

    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.03em;margin:0;">Verifikasi Return Sales</h1>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Daftar barang yang dikembalikan toko melalui Sales. Admin menerima/menolak setelah cek fisik barang.</p>
        </div>
    </div>

    {{-- Filter --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin-bottom:20px;">
        <form method="GET" action="{{ route('admin.returns.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label style="font-size:11.5px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Pilih Sales</label>
                <select name="sales_id" style="padding:7px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;min-width:180px;">
                    <option value="">Semua Sales</option>
                    @foreach($salesUsers as $user)
                        <option value="{{ $user->id }}" {{ request('sales_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:11.5px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;">Status</label>
                <select name="status" style="padding:7px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;min-width:160px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" style="background:#92400e;color:#fff;border:none;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">
                Filter
            </button>
            @if(request()->hasAny(['status', 'sales_id']))
                <a href="{{ route('admin.returns.index') }}" style="background:#fff;border:1px solid #cbd5e1;color:#475569;text-decoration:none;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:500;">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
        @if($returns->isEmpty())
            <div style="padding:48px 24px;text-align:center;">
                <i data-lucide="package" style="width:36px;height:36px;color:#cbd5e1;margin:0 auto 12px;display:block;"></i>
                <div style="font-size:14px;font-weight:600;color:#334155;">Belum ada pengajuan return.</div>
            </div>
        @else
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                        <th style="padding:11px 20px;text-align:left;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">No. Return</th>
                        <th style="padding:11px 20px;text-align:left;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Sales</th>
                        <th style="padding:11px 20px;text-align:left;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Laporan Pengiriman</th>
                        <th style="padding:11px 20px;text-align:left;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Tgl Return</th>
                        <th style="padding:11px 20px;text-align:right;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Total Return</th>
                        <th style="padding:11px 20px;text-align:center;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Status</th>
                        <th style="padding:11px 20px;text-align:center;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $ret)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:13px 20px;font-family:monospace;font-weight:700;font-size:12.5px;color:#92400e;">
                            {{ $ret->return_number }}
                        </td>
                        <td style="padding:13px 20px;font-size:13px;font-weight:600;color:#0f172a;">
                            {{ $ret->sales->name ?? '—' }}
                        </td>
                        <td style="padding:13px 20px;font-size:13px;color:#475569;font-family:monospace;">
                            {{ $ret->deliveryReport->report_number ?? '—' }}
                        </td>
                        <td style="padding:13px 20px;font-size:13px;color:#475569;">
                            {{ $ret->return_date->format('d M Y') }}
                        </td>
                        <td style="padding:13px 20px;text-align:right;font-weight:700;color:#0f172a;">
                            Rp {{ number_format($ret->total_return, 0, ',', '.') }}
                        </td>
                        <td style="padding:13px 20px;text-align:center;">
                            @if($ret->status === 'diterima')
                                <span style="background:#dcfce7;color:#166534;font-size:10px;font-weight:700;padding:3px 8px;border-radius:6px;">DITERIMA</span>
                            @elseif($ret->status === 'ditolak')
                                <span style="background:#fee2e2;color:#991b1b;font-size:10px;font-weight:700;padding:3px 8px;border-radius:6px;">DITOLAK</span>
                            @else
                                <span style="background:#fef08a;color:#854d0e;font-size:10px;font-weight:700;padding:3px 8px;border-radius:6px;">MENUNGGU</span>
                            @endif
                        </td>
                        <td style="padding:13px 20px;text-align:center;">
                            <a href="{{ route('admin.returns.show', $ret) }}"
                               style="background:#fff;border:1px solid #cbd5e1;color:#475569;text-decoration:none;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;">
                                Detail / Verifikasi
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding:16px 20px;">
                {{ $returns->links() }}
            </div>
        @endif
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot name="title">Laporan Pengiriman Sales</x-slot>

    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.02em;margin-bottom:4px;">Laporan Pengiriman Sales</h1>
            <p style="color:#64748b;font-size:13px;margin:0;">Semua laporan pengiriman dari seluruh sales ke toko.</p>
        </div>
        
        <form method="GET" action="{{ route('admin.delivery-reports.index') }}" style="display:flex;gap:10px;align-items:center;">
            <select name="sales_id" style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:13px;outline:none;">
                <option value="">Semua Sales</option>
                @foreach($salesUsers as $sales)
                    <option value="{{ $sales->id }}" {{ request('sales_id') == $sales->id ? 'selected' : '' }}>
                        {{ $sales->name }}
                    </option>
                @endforeach
            </select>
            
            <input type="date" name="date" value="{{ request('date') }}" style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:13px;outline:none;">
            
            <button type="submit" style="padding:8px 14px;background:#0f172a;color:white;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">Filter</button>
            @if(request()->hasAny(['sales_id', 'date']))
                <a href="{{ route('admin.delivery-reports.index') }}" style="padding:8px 14px;background:#f1f5f9;color:#64748b;text-decoration:none;border-radius:6px;font-size:13px;font-weight:600;">Reset</a>
            @endif
        </form>
    </div>

    <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;">
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
</x-layouts.admin>

<x-layouts.admin>
    <x-slot name="title">Daftar Penjualan</x-slot>

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:14px 18px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600;">
        {{ session('success') }}
    </div>
    @endif

    <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="padding:20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;background:#f8fafc;">
            <div>
                <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin:0;">Daftar Penjualan</h3>
                <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Kelola transaksi penjualan produk jadi.</p>
            </div>
            <a href="{{ route('admin.sales.create') }}"
               style="background:#0f172a;color:white;text-decoration:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Penjualan
            </a>
        </div>

        <table style="width:100%;border-collapse:collapse;text-align:left;">
            <thead>
                <tr style="background:#f1f5f9;border-bottom:1px solid #e2e8f0;">
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">No. Invoice</th>
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Tanggal</th>
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Member</th>
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Total</th>
                    <th style="padding:14px 20px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Status</th>
                    <th style="padding:14px 20px;text-align:right;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:14px 20px;">
                        <span style="font-family:monospace;font-size:13px;font-weight:700;color:#0f172a;background:#e2e8f0;padding:3px 8px;border-radius:5px;">
                            {{ $sale->invoice_number }}
                        </span>
                    </td>
                    <td style="padding:14px 20px;font-size:13px;color:#475569;">
                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                    </td>
                    <td style="padding:14px 20px;font-size:13px;font-weight:600;color:#1e293b;">
                        {{ $sale->customer->name ?? 'Umum' }}
                    </td>
                    <td style="padding:14px 20px;font-size:13px;font-weight:700;color:#16a34a;">
                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                    </td>
                    <td style="padding:14px 20px;">
                        @if($sale->payment_status === 'lunas')
                            <span style="font-size:12px;font-weight:600;background:#dcfce7;color:#166534;padding:3px 9px;border-radius:20px;border:1px solid #bbf7d0;">Lunas</span>
                        @elseif($sale->payment_status === 'sebagian')
                            <span style="font-size:12px;font-weight:600;background:#fef9c3;color:#854d0e;padding:3px 9px;border-radius:20px;border:1px solid #fef08a;">DP/Sebagian</span>
                        @else
                            <span style="font-size:12px;font-weight:600;background:#fee2e2;color:#991b1b;padding:3px 9px;border-radius:20px;border:1px solid #fecaca;">Belum Bayar</span>
                        @endif
                        <div style="font-size:11px;color:#64748b;margin-top:4px;">Via {{ strtoupper($sale->payment_method) }}</div>
                    </td>
                    <td style="padding:14px 20px;text-align:right;">
                        <a href="{{ route('admin.sales.show', $sale) }}"
                           style="color:#0284c7;text-decoration:none;font-size:13px;font-weight:600;padding:6px 12px;background:#f0f9ff;border-radius:6px;border:1px solid #e0f2fe;">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:50px;text-align:center;color:#94a3b8;font-size:14px;">
                        Belum ada data penjualan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($sales->hasPages())
        <div style="padding:15px 20px;border-top:1px solid #e2e8f0;background:#f8fafc;">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>

<x-layouts.user>
    <x-slot name="title">Laporan {{ $deliveryReport->report_number }}</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:500;color:#78716c;text-decoration:none;margin-bottom:18px; }
        .back-link:hover { color:#1c1917; }

        .report-heading { display:flex;align-items:center;gap:10px;margin-bottom:22px;flex-wrap:wrap; }
        .report-number  { font-size:17px;font-weight:700;color:#1c1917;font-family:monospace;letter-spacing:0.02em; }
        .badge-sent {
            background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;
            padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;
        }

        /* ── Layout ──────────────────────── */
        .layout { display:grid;grid-template-columns:1fr 250px;gap:16px;align-items:start; }

        /* ── Card ────────────────────────── */
        .card { background:#fff;border:1px solid #ece8e3;border-radius:12px;overflow:hidden; }
        .card-header { padding:13px 18px;border-bottom:1px solid #f5f0eb;background:#fafaf8; }
        .card-header h3 { font-size:13px;font-weight:700;color:#1c1917;margin:0; }

        /* ── Products table ──────────────── */
        table { width:100%;border-collapse:collapse; }
        th { padding:9px 18px;font-size:10px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.07em;background:#fafaf8;border-bottom:1px solid #f5f0eb;text-align:left; }
        td { padding:13px 18px;border-bottom:1px solid #f5f0eb;font-size:13px; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#fdfcfb; }

        /* ── Info panel ──────────────────── */
        .info-row { display:flex;justify-content:space-between;align-items:baseline;padding:11px 16px;border-bottom:1px solid #f5f0eb; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:11px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.05em;flex-shrink:0; }
        .info-value { font-size:13px;font-weight:600;color:#1c1917;text-align:right;max-width:60%; }

        .note-box { padding:12px 18px;background:#fafaf8;border-top:1px solid #f5f0eb; }
        .note-label { font-size:10px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.07em;margin-bottom:4px; }
        .note-text  { font-size:13px;color:#78716c;font-style:italic; }

        /* ── Weight pill ─────────────────── */
        .weight-pill {
            display:inline-block;background:#fdf3e7;border:1px solid #f0d9b5;
            color:#7a5c3e;font-size:11px;font-weight:600;
            padding:2px 8px;border-radius:20px;
        }

        /* ── Responsive ──────────────────── */
        @media (max-width:680px) { .layout { grid-template-columns:1fr; } }
    </style>

    <a href="{{ route('sales.delivery-reports.index') }}" class="back-link">← Kembali ke Riwayat</a>

    <div class="report-heading">
        <span class="report-number">{{ $deliveryReport->report_number }}</span>
        <span class="badge-sent" style="display:inline-flex;align-items:center;gap:4px;">
            <i data-lucide="check" style="width:12px;height:12px;"></i> Terkirim
        </span>
    </div>

    <div class="layout">

        {{-- Kiri: Produk yang Dikirim --}}
        <div class="card">
            <div class="card-header"><h3>Produk yang Dikirim</h3></div>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kemasan</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Harga Jual</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveryReport->items as $item)
                    <tr>
                        <td style="font-weight:600;color:#1c1917;">{{ $item->product->name }}</td>
                        <td><span class="weight-pill">{{ $item->product->weight }} Gram</span></td>
                        <td style="text-align:center;font-weight:700;color:#1c1917;">{{ number_format($item->qty, 0, ',', '.') }}</td>
                        <td style="text-align:right;color:#78716c;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td style="text-align:right;font-weight:700;color:#1c1917;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;font-size:10.5px;font-weight:700;color:#b9a99a;text-transform:uppercase;padding-top:12px;letter-spacing:0.06em;">
                            Total Nilai
                        </td>
                        <td style="text-align:right;font-size:17px;font-weight:800;color:#92400e;padding-top:12px;letter-spacing:-0.02em;">
                            Rp {{ number_format($deliveryReport->items->sum('subtotal'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Kanan: Info Pengiriman --}}
        <div class="card">
            <div class="card-header"><h3>Info Pengiriman</h3></div>
            <div class="info-row">
                <span class="info-label">Toko Tujuan</span>
                <span class="info-value">{{ $deliveryReport->toko_name }}</span>
            </div>
            @if($deliveryReport->customer_address_manual)
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span class="info-value" style="font-size:12px;">{{ $deliveryReport->customer_address_manual }}</span>
            </div>
            @endif
            @if($deliveryReport->customer_phone_manual)
            <div class="info-row">
                <span class="info-label">No. HP</span>
                <span class="info-value">{{ $deliveryReport->customer_phone_manual }}</span>
            </div>
            @endif
            @if($deliveryReport->payment_term_days)
            <div class="info-row">
                <span class="info-label">Tempo Bayar</span>
                <span class="info-value" style="color:#92400e;font-weight:700;">{{ $deliveryReport->payment_term_days }} hari</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Tanggal Kirim</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($deliveryReport->delivery_date)->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dicatat pada</span>
                <span class="info-value" style="font-size:12px;">{{ $deliveryReport->created_at->format('d M Y, H:i') }}</span>
            </div>
            
            <div style="border-top:1px solid #f5f0eb; padding:11px 16px; background:#fafaf8;">
                <div style="font-size:11px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Pembayaran</div>
                <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                    <span style="font-size:12px;color:#78716c;">Status</span>
                    <span>
                        @if($deliveryReport->payment_status === 'lunas')
                            <span style="background:#dcfce7;color:#166534;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;">LUNAS</span>
                        @elseif($deliveryReport->payment_status === 'dp')
                            <span style="background:#fef08a;color:#854d0e;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;">DP</span>
                        @else
                            <span style="background:#fee2e2;color:#991b1b;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;">BELUM BAYAR</span>
                        @endif
                    </span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                    <span style="font-size:12px;color:#78716c;">Total Tagihan</span>
                    <span style="font-size:12px;font-weight:600;color:#1c1917;">Rp {{ number_format($deliveryReport->total_amount, 0, ',', '.') }}</span>
                </div>
                @if($deliveryReport->payment_status === 'dp')
                <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                    <span style="font-size:12px;color:#78716c;">Jumlah DP</span>
                    <span style="font-size:12px;font-weight:600;color:#1c1917;">Rp {{ number_format($deliveryReport->down_payment_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div style="display:flex; justify-content:space-between; margin-top:8px; padding-top:8px; border-top:1px dashed #d6d3d1;">
                    <span style="font-size:12px;font-weight:700;color:#92400e;">Sisa Tagihan</span>
                    <span style="font-size:13px;font-weight:800;color:#92400e;">Rp {{ number_format($deliveryReport->remaining_amount, 0, ',', '.') }}</span>
                </div>
                @if($deliveryReport->due_date)
                <div style="display:flex; justify-content:space-between; margin-top:6px;">
                    <span style="font-size:11px;color:#ef4444;">Jatuh Tempo</span>
                    <span style="font-size:11px;font-weight:600;color:#ef4444;">{{ \Carbon\Carbon::parse($deliveryReport->due_date)->format('d M Y') }}</span>
                </div>
                @endif
                
                @if($deliveryReport->remaining_amount > 0)
                <div style="margin-top:14px; padding-top:12px; border-top:1px dashed #d6d3d1;">
                    <a href="{{ route('sales.deposits.create', ['delivery_report_id' => $deliveryReport->id]) }}"
                       style="background:#92400e; color:#fff; text-decoration:none; display:block; text-align:center; padding:8px; border-radius:6px; font-size:12.5px; font-weight:700;">
                        + Kirim Setoran
                    </a>
                </div>
                @endif
            </div>

            {{-- Informasi Return --}}
            @php
                $totalReturnDiterima = $deliveryReport->total_return_diterima;
                $tagihanEfektif      = $deliveryReport->total_amount - $totalReturnDiterima;
                $sisaTagihanReturn   = $tagihanEfektif - $deliveryReport->down_payment_amount;
                $returnsRelated      = $deliveryReport->salesReturns()->with('items')->orderBy('created_at', 'desc')->get();
            @endphp
            @if($totalReturnDiterima > 0 || $returnsRelated->isNotEmpty())
            <div style="border-top:1px solid #f5f0eb; padding:11px 16px;">
                <div style="font-size:11px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Tagihan Efektif (Setelah Return)</div>
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-size:12px;color:#78716c;">Total Return Diterima</span>
                    <span style="font-size:12px;font-weight:600;color:#dc2626;">- Rp {{ number_format($totalReturnDiterima, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-size:12px;color:#78716c;">Tagihan Efektif</span>
                    <span style="font-size:12px;font-weight:700;color:#1c1917;">Rp {{ number_format(max(0,$tagihanEfektif), 0, ',', '.') }}</span>
                </div>
                @if($sisaTagihanReturn < 0)
                <div style="display:flex;justify-content:space-between;margin-top:6px;padding-top:6px;border-top:1px dashed #d6d3d1;">
                    <span style="font-size:12px;font-weight:700;color:#1d4ed8;">Kelebihan Bayar</span>
                    <span style="font-size:13px;font-weight:800;color:#1d4ed8;">Rp {{ number_format(abs($sisaTagihanReturn), 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Bayar Lebih Info --}}
            @if($deliveryReport->is_overpaid)
            <div style="border-top:1px solid #f5f0eb; padding:11px 16px; background:#eff6ff;">
                <div style="font-size:11px;font-weight:700;color:#1e40af;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Informasi Bayar Lebih</div>
                <div style="display:flex; justify-content:space-between; margin-bottom:6px; align-items:center;">
                    <span style="font-size:12px;color:#1e40af;">Status</span>
                    <span>
                        @if($deliveryReport->overpayment_resolved_at)
                            <span style="background:#dcfce7;color:#166534;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;">SUDAH DISELESAIKAN</span>
                        @else
                            <span style="background:#fee2e2;color:#991b1b;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;">BELUM DISELESAIKAN</span>
                        @endif
                    </span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                    <span style="font-size:12px;color:#1e40af;">Nominal</span>
                    <span style="font-size:12px;font-weight:700;color:#1d4ed8;">Rp {{ number_format($deliveryReport->overpayment_amount, 0, ',', '.') }}</span>
                </div>
                @if($deliveryReport->overpayment_resolved_at)
                <div style="margin-top:8px; padding-top:8px; border-top:1px dashed #bfdbfe; font-size:11.5px; color:#1e40af; line-height:1.4;">
                    Diselesaikan oleh: <strong>Admin</strong> pada {{ $deliveryReport->overpayment_resolved_at->format('d M Y') }}<br>
                    Catatan Admin: <span style="font-style:italic;">"{{ $deliveryReport->overpayment_resolution_note }}"</span>
                </div>
                @else
                <div style="margin-top:4px; font-size:11px; color:#60a5fa; font-style:italic;">
                    Bayar Lebih akan diselesaikan oleh admin.
                </div>
                @endif
            </div>
            @endif

            {{-- Tombol Ajukan Return --}}
            <div style="border-top:1px solid #f5f0eb;padding:12px 16px;">
                <a href="{{ route('sales.returns.create', ['delivery_report_id' => $deliveryReport->id]) }}"
                   style="background:#fff;border:1px solid #d6d3d1;color:#57534e;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:8px;border-radius:6px;font-size:12.5px;font-weight:600;width:100%;box-sizing:border-box;">
                    <i data-lucide="corner-up-left" style="width:14px;height:14px;"></i> Ajukan Return Barang
                </a>
            </div>

            @if($deliveryReport->note)
            <div class="note-box">
                <div class="note-label">Catatan</div>
                <div class="note-text">{{ $deliveryReport->note }}</div>
            </div>
            @endif
        </div>

    </div>

</x-layouts.user>

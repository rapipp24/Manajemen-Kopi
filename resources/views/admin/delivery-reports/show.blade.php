<x-layouts.admin>
    <x-slot name="title">Detail Laporan {{ $deliveryReport->report_number }}</x-slot>

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <a href="{{ route('admin.delivery-reports.index') }}"
           style="color:#64748b;text-decoration:none;font-size:13px;">← Semua Laporan</a>
        <h1 style="font-size:20px;font-weight:800;color:#0f172a;font-family:monospace;margin:0;">
            {{ $deliveryReport->report_number }}
        </h1>
        <span style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:3px 12px;border-radius:20px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="check" style="width:12px;height:12px;"></i> Terkirim</span>
    </div>

    <div style="display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start;">

        {{-- Kiri: Tabel Produk --}}
        <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;">
            <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">Produk yang Dikirim</h3>
            </div>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                        <th style="padding:11px 20px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Produk</th>
                        <th style="padding:11px 20px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Qty</th>
                        <th style="padding:11px 20px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Harga</th>
                        <th style="padding:11px 20px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveryReport->items as $item)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:14px 20px;">
                            <div style="font-weight:600;color:#0f172a;">{{ $item->product->name }}</div>
                            <div style="font-size:11px;color:#94a3b8;">Kemasan: {{ $item->product->weight }}gr</div>
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-weight:700;">{{ number_format($item->qty, 0, ',', '.') }}</td>
                        <td style="padding:14px 20px;text-align:right;color:#475569;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td style="padding:14px 20px;text-align:right;font-weight:700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f8fafc;">
                        <td style="padding:14px 20px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Total</td>
                        <td style="padding:14px 20px;text-align:center;font-size:14px;font-weight:800;color:#0f172a;">
                            {{ number_format($deliveryReport->items->sum('qty'), 0, ',', '.') }}
                        </td>
                        <td style="padding:14px 20px;"></td>
                        <td style="padding:14px 20px;text-align:right;font-size:18px;font-weight:800;color:#0f172a;">
                            Rp {{ number_format($deliveryReport->items->sum('subtotal'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Kanan: Info --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Panel Penyelesaian Bayar Lebih --}}
            @if($deliveryReport->is_overpaid)
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#f8fafc;display:flex;justify-content:space-between;align-items:center;">
                    <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">Penyelesaian Bayar Lebih</h3>
                    @if($deliveryReport->overpayment_resolved_at)
                        <span style="font-size:10px;font-weight:800;background:#dcfce7;color:#166534;padding:3px 8px;border-radius:10px;">SELESAI</span>
                    @else
                        <span style="font-size:10px;font-weight:800;background:#fee2e2;color:#991b1b;padding:3px 8px;border-radius:10px;">BELUM SELESAI</span>
                    @endif
                </div>
                <div style="padding:16px 18px;">
                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px;margin-bottom:12px;color:#1e40af;font-size:13px;line-height:1.4;">
                        Nominal yang perlu diselesaikan:<br>
                        <strong style="font-size:18px;color:#1d4ed8;display:block;margin-top:4px;">Rp {{ number_format($deliveryReport->overpayment_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div style="font-size:12px;color:#64748b;line-height:1.5;margin-bottom:16px;">
                        Toko sudah membayar lebih dari tagihan setelah return. Penyelesaian dilakukan manual oleh admin.
                    </div>

                    @if(!$deliveryReport->overpayment_resolved_at)
                        <form action="{{ route('admin.delivery-reports.resolve-overpayment', $deliveryReport) }}" method="POST">
                            @csrf
                            <div style="margin-bottom:12px;">
                                <label style="font-size:12px;font-weight:700;color:#475569;display:block;margin-bottom:6px;">Catatan Penyelesaian <span style="color:#ef4444;">*</span></label>
                                <textarea name="overpayment_resolution_note" rows="3" required
                                          placeholder="Contoh: Dikembalikan tunai ke toko / disepakati untuk potongan transaksi berikutnya."
                                          style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;resize:vertical;"></textarea>
                                @error('overpayment_resolution_note')
                                    <div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit"
                                    style="background:#1d4ed8;color:#fff;border:none;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;width:100%;display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                                <i data-lucide="check" style="width:14px;height:14px;"></i> Tandai Sudah Diselesaikan
                            </button>
                        </form>
                    @else
                        <div style="font-size:12.5px;color:#475569;display:flex;flex-direction:column;gap:8px;">
                            <div>
                                <span style="font-weight:700;color:#0f172a;display:block;">Diselesaikan Oleh:</span>
                                <span>{{ $deliveryReport->overpaymentResolver->name ?? '—' }}</span>
                            </div>
                            <div>
                                <span style="font-weight:700;color:#0f172a;display:block;">Tanggal Selesai:</span>
                                <span>{{ $deliveryReport->overpayment_resolved_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px;margin-top:4px;">
                                <span style="font-weight:700;color:#0f172a;display:block;margin-bottom:4px;font-size:11.5px;text-transform:uppercase;">Catatan:</span>
                                <span style="font-style:italic;color:#334155;">"{{ $deliveryReport->overpayment_resolution_note }}"</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Info Sales --}}
            <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#f8fafc;">
                    <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">Sales</h3>
                </div>
                <div style="padding:14px 18px;">
                    <div style="font-weight:700;color:#0f172a;">{{ $deliveryReport->sales->name ?? '—' }}</div>
                    <div style="font-size:12px;color:#94a3b8;">{{ $deliveryReport->sales->email ?? '' }}</div>
                    <div style="font-size:12px;color:#64748b;margin-top:6px;">
                        Dicatat: {{ $deliveryReport->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            {{-- Info Toko --}}
            <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#f8fafc;display:flex;justify-content:space-between;align-items:center;">
                    <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">Toko Tujuan</h3>
                    @if(!$deliveryReport->customer_id)
                        <span style="font-size:10px;font-weight:700;background:#f1f5f9;color:#64748b;padding:2px 8px;border-radius:10px;">Input Manual</span>
                    @else
                        <span style="font-size:10px;font-weight:700;background:#f0fdf4;color:#166534;padding:2px 8px;border-radius:10px;">Master Customer</span>
                    @endif
                </div>
                <div style="padding:0;">
                    @php
                        $rows = [
                            ['label' => 'Nama', 'value' => $deliveryReport->toko_name],
                            ['label' => 'Alamat', 'value' => $deliveryReport->customer_address_manual ?? ($deliveryReport->customer?->address ?? null)],
                            ['label' => 'No. HP', 'value' => $deliveryReport->customer_phone_manual ?? ($deliveryReport->customer?->phone ?? null)],
                            ['label' => 'Tgl Kirim', 'value' => \Carbon\Carbon::parse($deliveryReport->delivery_date)->format('d M Y')],
                        ];
                    @endphp
                    @foreach($rows as $row)
                        @if($row['value'])
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:11px 18px;border-bottom:1px solid #f1f5f9;">
                            <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;">{{ $row['label'] }}</span>
                            <span style="font-size:13px;font-weight:600;color:#0f172a;text-align:right;max-width:65%;">{{ $row['value'] }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Info Pembayaran --}}
            <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#f8fafc;display:flex;justify-content:space-between;align-items:center;">
                    <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">Pembayaran</h3>
                    @if($deliveryReport->payment_status === 'lunas')
                        <span style="font-size:10px;font-weight:800;background:#dcfce7;color:#166534;padding:3px 8px;border-radius:10px;letter-spacing:0.05em;">LUNAS</span>
                    @elseif($deliveryReport->payment_status === 'dp')
                        <span style="font-size:10px;font-weight:800;background:#fef08a;color:#854d0e;padding:3px 8px;border-radius:10px;letter-spacing:0.05em;">DP</span>
                    @else
                        <span style="font-size:10px;font-weight:800;background:#fee2e2;color:#991b1b;padding:3px 8px;border-radius:10px;letter-spacing:0.05em;">BELUM BAYAR</span>
                    @endif
                </div>
                <div style="padding:0;">
                    <div style="display:flex;justify-content:space-between;padding:11px 18px;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;">Total Tagihan</span>
                        <span style="font-size:13px;font-weight:600;color:#0f172a;">Rp {{ number_format($deliveryReport->total_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($deliveryReport->payment_status === 'dp')
                    <div style="display:flex;justify-content:space-between;padding:11px 18px;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;">Uang Muka (DP)</span>
                        <span style="font-size:13px;font-weight:600;color:#0f172a;">Rp {{ number_format($deliveryReport->down_payment_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;padding:11px 18px;background:#f8fafc;border-top:1px dashed #cbd5e1;">
                        <span style="font-size:11px;font-weight:800;color:#b91c1c;text-transform:uppercase;">Sisa Tagihan</span>
                        <span style="font-size:14px;font-weight:800;color:#b91c1c;">Rp {{ number_format($deliveryReport->remaining_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($deliveryReport->due_date)
                    <div style="padding:10px 18px;background:#fff7ed;border-top:1px solid #fed7aa;display:flex;align-items:center;gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <span style="font-size:12px;color:#ea580c;font-weight:700;">
                            Jatuh Tempo: {{ \Carbon\Carbon::parse($deliveryReport->due_date)->format('d M Y') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Catatan --}}
            @if($deliveryReport->note)
            <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;padding:16px 18px;">
                <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:6px;">Catatan</div>
                <div style="font-size:13px;color:#475569;font-style:italic;">{{ $deliveryReport->note }}</div>
            </div>
            @endif

            {{-- Informasi Return (hitung dinamis) --}}
            @php
                $totalReturnDiterima = $deliveryReport->total_return_diterima;
                $tagihanEfektif      = $deliveryReport->total_amount - $totalReturnDiterima;
                $sisaTagihanReturn   = $tagihanEfektif - $deliveryReport->down_payment_amount;
                $returnsRelated      = $deliveryReport->salesReturns()->with('items.product')->orderBy('created_at','desc')->get();
            @endphp
            @if($totalReturnDiterima > 0 || $returnsRelated->isNotEmpty())
            <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#f8fafc;display:flex;justify-content:space-between;align-items:center;">
                    <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">Tagihan Efektif (Setelah Return)</h3>
                </div>
                <div style="padding:0;">
                    <div style="display:flex;justify-content:space-between;padding:11px 18px;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;">Total Return Diterima</span>
                        <span style="font-size:13px;font-weight:600;color:#dc2626;">- Rp {{ number_format($totalReturnDiterima, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:11px 18px;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;">Tagihan Efektif</span>
                        <span style="font-size:13px;font-weight:800;color:#0f172a;">Rp {{ number_format(max(0,$tagihanEfektif), 0, ',', '.') }}</span>
                    </div>
                    @if($sisaTagihanReturn < 0)
                    <div style="display:flex;justify-content:space-between;padding:11px 18px;background:#eff6ff;">
                        <span style="font-size:11px;font-weight:800;color:#1d4ed8;text-transform:uppercase;">Kelebihan Bayar</span>
                        <span style="font-size:14px;font-weight:800;color:#1d4ed8;">Rp {{ number_format(abs($sisaTagihanReturn), 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Daftar Return Terkait --}}
            @if($returnsRelated->isNotEmpty())
            <div style="background:white;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#f8fafc;display:flex;justify-content:space-between;align-items:center;">
                    <h3 style="font-size:13px;font-weight:700;color:#0f172a;margin:0;">Riwayat Return ({{ $returnsRelated->count() }})</h3>
                </div>
                @foreach($returnsRelated as $ret)
                <div style="padding:12px 18px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-family:monospace;font-weight:700;font-size:12px;color:#92400e;">{{ $ret->return_number }}</div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $ret->return_date->format('d M Y') }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:12px;font-weight:700;color:#0f172a;">Rp {{ number_format($ret->total_return, 0, ',', '.') }}</div>
                        @if($ret->status === 'diterima')
                            <span style="background:#dcfce7;color:#166534;font-size:10px;font-weight:700;padding:1px 6px;border-radius:4px;">DITERIMA</span>
                        @elseif($ret->status === 'ditolak')
                            <span style="background:#fee2e2;color:#991b1b;font-size:10px;font-weight:700;padding:1px 6px;border-radius:4px;">DITOLAK</span>
                        @else
                            <span style="background:#fef08a;color:#854d0e;font-size:10px;font-weight:700;padding:1px 6px;border-radius:4px;">MENUNGGU</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.returns.show', $ret) }}"
                       style="font-size:11.5px;font-weight:600;color:#475569;text-decoration:none;border:1px solid #cbd5e1;padding:4px 10px;border-radius:6px;">Detail</a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot name="title">Detail Penjualan</x-slot>

    <div style="max-width: 900px; margin-bottom: 50px;">
        {{-- Flash Message --}}
        @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:14px 18px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600;">
            {{ session('success') }}
        </div>
        @endif

        {{-- Top Actions --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('admin.sales.index') }}"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 600; color: #475569; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Daftar Penjualan
            </a>
            
            <a href="{{ route('admin.sales.print', $sale) }}" target="_blank"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: #0f172a; border-radius: 8px; font-size: 13px; font-weight: 600; color: white; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Nota
            </a>
        </div>

        {{-- HEADER INFO --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <span style="font-family: monospace; font-size: 18px; font-weight: 700; color: #0f172a; background: #e2e8f0; padding: 4px 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                        {{ $sale->invoice_number }}
                    </span>
                    <p style="margin-top: 10px; font-size: 13px; color: #64748b;">
                        Dicatat oleh {{ $sale->creator->name ?? '-' }} pada
                        {{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    @if($sale->payment_status === 'lunas')
                        <span style="font-size:13px;font-weight:700;background:#dcfce7;color:#166534;padding:6px 12px;border-radius:20px;border:1px solid #bbf7d0;">LUNAS</span>
                    @elseif($sale->payment_status === 'sebagian')
                        <span style="font-size:13px;font-weight:700;background:#fef9c3;color:#854d0e;padding:6px 12px;border-radius:20px;border:1px solid #fef08a;">DP/SEBAGIAN</span>
                    @else
                        <span style="font-size:13px;font-weight:700;background:#fee2e2;color:#991b1b;padding:6px 12px;border-radius:20px;border:1px solid #fecaca;">BELUM BAYAR</span>
                    @endif
                    @if($sale->payment_status !== 'belum_bayar')
                        <div style="font-size:12px;font-weight:600;color:#64748b;margin-top:8px;text-align:right;">
                            Metode: {{ strtoupper($sale->payment_method) }}
                        </div>
                    @endif
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Tanggal Transaksi</p>
                    <p style="font-size: 15px; font-weight: 700; color: #1e293b;">
                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                    </p>
                </div>
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Member / Pelanggan</p>
                    <p style="font-size: 15px; font-weight: 700; color: #1e293b;">
                        {{ $sale->customer->name ?? $sale->customer_name }}
                    </p>
                </div>
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Catatan</p>
                    <p style="font-size: 14px; color: #475569;">{{ $sale->note ?: '-' }}</p>
                </div>
            </div>
        </div>

        {{-- RINGKASAN PEMBAYARAN --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            {{-- List Riwayat --}}
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 13px; font-weight: 700; color: #1e293b; margin: 0;">Riwayat Pembayaran</h3>
                    <span style="font-size: 11px; font-weight: 700; color: #64748b; background: #e2e8f0; padding: 2px 8px; border-radius: 4px;">{{ $sale->payments->count() }} Kali</span>
                </div>
                <div style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        @forelse($sale->payments as $payment)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 20px;">
                                <div style="font-weight: 600; color: #1e293b;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                <div style="font-size: 11px; color: #94a3b8;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }} • {{ strtoupper($payment->payment_method) }}</div>
                            </td>
                            <td style="padding: 12px 20px; text-align: right; color: #64748b; font-size: 11px;">
                                oleh {{ $payment->creator->name ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td style="padding: 30px 20px; text-align: center; color: #94a3b8; font-style: italic;">Belum ada riwayat pembayaran.</td>
                        </tr>
                        @endforelse
                    </table>
                </div>
                @if($sale->payments->count() > 0)
                <div style="padding: 12px 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
                    <span style="font-size: 12px; font-weight: 600; color: #64748b;">Total Terbayar</span>
                    <span style="font-size: 13px; font-weight: 700; color: #1e293b;">Rp {{ number_format($sale->total_paid, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>

            {{-- Form Catat Pembayaran / Status Lunas --}}
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                @if($sale->remaining_balance > 0)
                <div style="padding: 16px 20px; background: #fff7ed; border-bottom: 1px solid #ffedd5;">
                    <h3 style="font-size: 13px; font-weight: 700; color: #9a3412; margin: 0;">Catat Pembayaran</h3>
                </div>
                <form action="{{ route('admin.sales.payments.store', $sale) }}" method="POST" style="padding: 20px;">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <label style="font-size: 12px; font-weight: 600; color: #64748b;">Jumlah Bayar (Rp)</label>
                            <span style="font-size: 11px; font-weight: 700; color: #dc2626;">Sisa: Rp {{ number_format($sale->remaining_balance, 0, ',', '.') }}</span>
                        </div>
                        <input type="number" name="amount" id="input-amount-detail" value="{{ $sale->remaining_balance }}" max="{{ $sale->remaining_balance }}" required
                               oninput="updatePaymentPreview(this)"
                               style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; font-weight: 700;">
                        <div id="payment-preview" style="font-size: 12px; color: #16a34a; font-weight: 600; margin-top: 4px;">
                            Konfirmasi: Rp {{ number_format($sale->remaining_balance, 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 6px;">Tanggal</label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                                   style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 6px;">Metode</label>
                            <select name="payment_method" required
                                    style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: white;">
                                <option value="cash">Tunai / Cash</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                                <option value="cod">COD (Bayar di Tempat)</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" 
                            style="width: 100%; padding: 12px; background: #ea580c; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.2s;">
                        Simpan Pembayaran
                    </button>
                </form>
                @else
                <div style="padding: 40px 20px; text-align: center;">
                    <div style="width: 48px; height: 48px; background: #dcfce7; color: #166534; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 style="font-size: 15px; font-weight: 700; color: #166534; margin: 0;">Pembayaran Lunas</h3>
                    <p style="font-size: 12px; color: #64748b; margin-top: 6px;">Transaksi ini sudah dibayar penuh.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- DETAIL ITEM --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h3 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0;">Rincian Produk</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left;">Produk</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right;">Harga Satuan</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: center;">Qty</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotalQty = 0; @endphp
                    @foreach($sale->items as $item)
                    @php $grandTotalQty += $item->qty; @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 20px;">
                            <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $item->product->name ?? '-' }}</div>
                            @if($item->product->variant)
                                <div style="font-size: 12px; color: #64748b;">{{ $item->product->variant }}</div>
                            @endif
                        </td>
                        <td style="padding: 12px 20px; font-size: 14px; color: #475569; text-align: right;">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td style="padding: 12px 20px; font-size: 14px; font-weight: 700; color: #1e293b; text-align: center;">
                            {{ number_format($item->qty) }}
                        </td>
                        <td style="padding: 12px 20px; font-size: 14px; font-weight: 700; color: #1e293b; text-align: right;">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                        <td colspan="2" style="padding: 16px 20px; font-size: 14px; font-weight: 700; color: #475569; text-align: right;">Total Transaksi</td>
                        <td style="padding: 16px 20px; font-size: 14px; font-weight: 800; color: #1e293b; text-align: center;">
                            {{ number_format($grandTotalQty) }}
                        </td>
                        <td style="padding: 16px 20px; font-size: 18px; font-weight: 800; color: #16a34a; text-align: right;">
                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script>
        function formatMataUang(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number).replace('Rp', 'Rp ');
        }

        function updatePaymentPreview(input) {
            const preview = document.getElementById('payment-preview');
            const val = parseFloat(input.value) || 0;
            preview.textContent = 'Konfirmasi: ' + formatMataUang(val);
        }
    </script>
</x-layouts.admin>

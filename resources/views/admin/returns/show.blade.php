<x-layouts.admin>
    <x-slot name="title">Verifikasi Return {{ $return->return_number }}</x-slot>

    <div style="margin-bottom:16px;">
        <a href="{{ route('admin.returns.index') }}"
           style="background:#fff;border:1px solid #cbd5e1;color:#475569;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            ← Kembali ke Daftar
        </a>
    </div>

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;flex-wrap:wrap;">
        <h1 style="font-size:20px;font-weight:800;color:#0f172a;font-family:monospace;margin:0;">{{ $return->return_number }}</h1>
        @if($return->status === 'diterima')
            <span style="background:#dcfce7;color:#166534;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="check" style="width:12px;height:12px;"></i> DITERIMA</span>
        @elseif($return->status === 'ditolak')
            <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="x" style="width:12px;height:12px;"></i> DITOLAK</span>
        @else
            <span style="background:#fef08a;color:#854d0e;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="clock" style="width:12px;height:12px;"></i> MENUNGGU VERIFIKASI</span>
        @endif
    </div>

    {{-- Helper: Penjelasan flow verifikasi return --}}
    <div style="background:#f0f9ff;border:1px solid #bae6fd;border-left:3px solid #38bdf8;border-radius:8px;padding:11px 15px;margin-bottom:22px;font-size:12.5px;color:#0369a1;line-height:1.6;">
        <strong>Info Sistem:</strong> Saat menerima return, Admin wajib memilih kondisi barang.
        Jika <strong>Layak Jual</strong>, stok produk siap jual di gudang bertambah dan stock movement IN tercatat.
        Jika <strong>Perlu Proses Ulang</strong>, stok tidak bertambah, stock movement tidak tercatat, dan proses fisik packing ulang ditangani manual oleh gudang.
        Kedua kondisi ini sama-sama mengurangi tagihan efektif toko.
    </div>

    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

        {{-- Kiri: Tabel Item --}}
        <div>
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:16px;">
                <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                    <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">Produk yang Dikembalikan</h3>
                </div>
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                            <th style="padding:10px 20px;text-align:left;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Produk</th>
                            <th style="padding:10px 20px;text-align:center;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Qty Return</th>
                            <th style="padding:10px 20px;text-align:right;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Harga (Snapshot)</th>
                            <th style="padding:10px 20px;text-align:right;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Subtotal Return</th>
                            <th style="padding:10px 20px;text-align:left;font-size:10.5px;font-weight:700;color:#64748b;text-transform:uppercase;">Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($return->items as $item)
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:13px 20px;">
                                <div style="font-weight:600;color:#0f172a;">{{ $item->product->name }}</div>
                                <div style="font-size:11px;color:#94a3b8;">{{ $item->product->weight ?? '' }}gr</div>
                            </td>
                            <td style="padding:13px 20px;text-align:center;font-weight:700;font-size:15px;">{{ number_format($item->qty_return, 0, ',', '.') }}</td>
                            <td style="padding:13px 20px;text-align:right;color:#475569;">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                            <td style="padding:13px 20px;text-align:right;font-weight:700;">Rp {{ number_format($item->subtotal_return, 0, ',', '.') }}</td>
                            <td style="padding:13px 20px;color:#64748b;font-size:12px;">{{ $item->reason ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc;">
                            <td colspan="3" style="padding:13px 20px;text-align:right;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">Total Nilai Return</td>
                            <td style="padding:13px 20px;text-align:right;font-size:18px;font-weight:800;color:#92400e;">
                                Rp {{ number_format($return->total_return, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Info Laporan Pengiriman terkait --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                    <h3 style="font-size:13px;font-weight:700;color:#0f172a;margin:0;">Laporan Pengiriman Terkait</h3>
                </div>
                <div style="padding:14px 20px;display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:12px;color:#64748b;">No. Laporan</span>
                        <a href="{{ route('admin.delivery-reports.show', $return->deliveryReport) }}"
                           style="font-family:monospace;font-weight:700;color:#92400e;text-decoration:none;font-size:12.5px;">
                            {{ $return->deliveryReport->report_number }}
                        </a>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:12px;color:#64748b;">Total Tagihan Awal</span>
                        <span style="font-size:12px;font-weight:600;color:#0f172a;">Rp {{ number_format($return->deliveryReport->total_amount, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $dr = $return->deliveryReport;
                        $totalReturnDiterima = $dr->total_return_diterima;
                        $tagihanEfektif = $dr->total_amount - $totalReturnDiterima;
                        $sisaTagihan = $tagihanEfektif - $dr->down_payment_amount;
                    @endphp
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:12px;color:#64748b;">Total Return Diterima</span>
                        <span style="font-size:12px;font-weight:600;color:#dc2626;">- Rp {{ number_format($totalReturnDiterima, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:1px dashed #e2e8f0;">
                        <span style="font-size:12px;font-weight:700;color:#0f172a;">Tagihan Efektif</span>
                        <span style="font-size:13px;font-weight:800;color:#0f172a;">Rp {{ number_format(max(0, $tagihanEfektif), 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:12px;color:#64748b;">Total Pembayaran Masuk</span>
                        <span style="font-size:12px;font-weight:600;color:#166534;">Rp {{ number_format($dr->down_payment_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($sisaTagihan < 0)
                    <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:1px dashed #e2e8f0;background:#eff6ff;padding:8px 10px;border-radius:8px;margin-top:4px;">
                        <span style="font-size:12px;font-weight:700;color:#1d4ed8;">Kelebihan Bayar</span>
                        <span style="font-size:13px;font-weight:800;color:#1d4ed8;">Rp {{ number_format(abs($sisaTagihan), 0, ',', '.') }}</span>
                    </div>
                    @else
                    <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:1px dashed #e2e8f0;">
                        <span style="font-size:12px;font-weight:700;color:#b91c1c;">Sisa Tagihan</span>
                        <span style="font-size:13px;font-weight:800;color:#b91c1c;">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kanan: Aksi & Info --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Info Return --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#f8fafc;">
                    <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">Informasi Return</h3>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;padding:11px 18px;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:12px;color:#64748b;">Diajukan oleh</span>
                        <span style="font-size:12px;font-weight:600;color:#0f172a;">{{ $return->sales->name }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:11px 18px;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:12px;color:#64748b;">Tanggal Return</span>
                        <span style="font-size:12px;font-weight:600;color:#0f172a;">{{ $return->return_date->format('d M Y') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:11px 18px;border-bottom:1px solid #f1f5f9;">
                        <span style="font-size:12px;color:#64748b;">Tanggal Ajuan</span>
                        <span style="font-size:12px;font-weight:600;color:#0f172a;">{{ $return->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($return->note)
                    <div style="padding:11px 18px;">
                        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px;">Catatan Sales</div>
                        <div style="font-size:12px;color:#475569;font-style:italic;">{{ $return->note }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Panel Aksi Verifikasi --}}
            @if($return->status === 'menunggu')
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px;">
                <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0 0 12px;">Aksi Verifikasi</h3>

                <div style="background:#fdf9f5;border:1px solid #fed7aa;border-radius:8px;padding:12px;margin-bottom:16px;font-size:12.5px;color:#ea580c;font-weight:500;display:flex;align-items:flex-start;gap:8px;">
                    <i data-lucide="alert-triangle" style="width:16px;height:16px;flex-shrink:0;margin-top:2px;"></i>
                    <span>
                        Pastikan barang fisik sudah diterima di gudang sebelum menekan <strong>Terima Return</strong>. Tindakan ini akan menambah stok gudang.<br>
                        <span style="font-weight:400;color:#9a3412;font-size:12px;">Jika barang perlu proses ulang atau packing ulang, proses fisiknya ditangani manual oleh gudang. Cek catatan sales sebelum menerima.</span>
                    </span>
                </div>



                {{-- Tolak Return --}}
                <div style="border-top:1px dashed #cbd5e1;padding-top:16px;">
                    <h4 style="font-size:13px;font-weight:700;color:#0f172a;margin:0 0 10px;">Tolak Return</h4>
                    <form action="{{ route('admin.returns.reject', $return) }}" method="POST">
                        @csrf
                        <div style="margin-bottom:10px;">
                            <label style="font-size:11.5px;font-weight:700;color:#475569;display:block;margin-bottom:5px;">Alasan Penolakan <span style="color:#ef4444;">*</span></label>
                            <textarea name="rejection_reason" rows="3" required
                                      placeholder="Contoh: Barang belum dikembalikan secara fisik."
                                      style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;box-sizing:border-box;"></textarea>
                        </div>
                        <button type="submit"
                                style="background:#991b1b;color:#fff;border:none;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;width:100%;display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                            <i data-lucide="x" style="width:14px;height:14px;"></i> Tolak Return
                        </button>
                    </form>
                </div>
                
                {{-- Terima Return --}}
                <form id="receiveForm" action="{{ route('admin.returns.receive', $return) }}" method="POST" style="margin-bottom:16px; margin-top:20px;">
                    @csrf
                    
                    <div style="margin-bottom:18px;">
                        <label style="font-size:12px;font-weight:700;color:#334155;display:block;margin-bottom:8px;">Kondisi Barang Return <span style="color:#ef4444;">*</span></label>
                        
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <label style="display:flex;align-items:flex-start;gap:10px;padding:12px;border:1px solid #cbd5e1;border-radius:8px;cursor:pointer;background:#fff;transition:all 0.2s;" id="label_layak_jual">
                                <input type="radio" name="return_condition" value="layak_jual" required style="margin-top:3px;" onchange="updateConditionStyles()">
                                <div>
                                    <span style="font-size:13px;font-weight:700;color:#0f172a;display:block;">Layak Jual</span>
                                    <span style="font-size:11.5px;color:#64748b;display:block;margin-top:2px;">Barang masih bisa dijual dan stok produk siap jual di gudang bertambah otomatis.</span>
                                </div>
                            </label>
                            
                            <label style="display:flex;align-items:flex-start;gap:10px;padding:12px;border:1px solid #cbd5e1;border-radius:8px;cursor:pointer;background:#fff;transition:all 0.2s;" id="label_perlu_proses_ulang">
                                <input type="radio" name="return_condition" value="perlu_proses_ulang" required style="margin-top:3px;" onchange="updateConditionStyles()">
                                <div>
                                    <span style="font-size:13px;font-weight:700;color:#0f172a;display:block;">Perlu Proses Ulang</span>
                                    <span style="font-size:11.5px;color:#64748b;display:block;margin-top:2px;">Stok tidak bertambah, barang diterima gudang dan fisik packing ulang ditangani manual.</span>
                                </div>
                            </label>
                        </div>
                        @error('return_condition')
                            <div style="color:#ef4444;font-size:12px;margin-top:6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="button" onclick="openReceiveModal()"
                            style="background:#166534;color:#fff;border:none;padding:11px 20px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;width:100%;text-align:center;display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                        <i data-lucide="check" style="width:16px;height:16px;"></i> Terima Return
                    </button>
                </form>
            </div>

            @elseif($return->status === 'diterima')
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:18px;display:flex;flex-direction:column;gap:12px;">
                <div style="color:#166534;font-size:13px;font-weight:700;margin:0;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="check" style="width:14px;height:14px;"></i> Return Diterima</div>
                
                <div>
                    <span style="font-size:11px;color:#64748b;text-transform:uppercase;font-weight:700;display:block;margin-bottom:4px;">Kondisi Barang</span>
                    @if($return->return_condition === 'layak_jual')
                        <span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
                            <i data-lucide="check-circle-2" style="width:14px;height:14px;"></i> Layak Jual
                        </span>
                        <div style="font-size:12px;color:#166534;margin-top:6px;line-height:1.4;">Stok gudang bertambah dan stock movement IN tercatat.</div>
                    @elseif($return->return_condition === 'perlu_proses_ulang')
                        <span style="background:#fefce8;color:#a16207;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
                            <i data-lucide="refresh-cw" style="width:14px;height:14px;"></i> Perlu Proses Ulang
                        </span>
                        <div style="font-size:12px;color:#a16207;margin-top:6px;line-height:1.4;">Barang diterima di gudang, tetapi tidak masuk ke stok siap jual. Proses fisik packing ulang ditangani manual.</div>
                    @else
                        <span style="background:#f1f5f9;color:#475569;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;display:inline-block;">
                            Belum Diisi (Data Lama)
                        </span>
                    @endif
                </div>

                @if($return->approver)
                <div style="margin-top:4px;font-size:11.5px;color:#15803d;border-top:1px dashed #bbf7d0;padding-top:8px;">
                    Diproses oleh: <strong>{{ $return->approver->name }}</strong><br>
                    Pada: {{ $return->approved_at->format('d M Y, H:i') }}
                </div>
                @endif
            </div>

            @elseif($return->status === 'ditolak')
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:18px;">
                <div style="color:#991b1b;font-size:13px;font-weight:600;margin-bottom:8px;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="x" style="width:14px;height:14px;"></i> Return Ditolak</div>
                <div style="font-size:12px;color:#991b1b;">Stok tidak berubah. Tagihan tidak berubah.</div>
                @if($return->rejection_reason)
                <div style="margin-top:10px;font-size:12px;color:#7f1d1d;background:#fee2e2;padding:10px;border-radius:6px;">
                    <strong>Alasan:</strong> {{ $return->rejection_reason }}
                </div>
                @endif
                @if($return->approver)
                <div style="margin-top:8px;font-size:11.5px;color:#991b1b;">
                    Diproses oleh: <strong>{{ $return->approver->name }}</strong><br>
                    Pada: {{ $return->approved_at->format('d M Y, H:i') }}
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Modal Konfirmasi Terima Return --}}
    <div id="receiveModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:12px;padding:24px;max-width:400px;width:90%;box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;color:#166534;"><i data-lucide="check" style="width:20px;height:20px;"></i></div>
                <h3 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;">Terima Return Barang</h3>
            </div>
            <p style="font-size:14px;color:#475569;margin-bottom:8px;line-height:1.5;">
                Apakah Anda yakin ingin menerima return ini dengan kondisi: <strong id="selected_condition_text" style="color:#0f172a;"></strong>?
            </p>
            <ul id="modal_stock_info" style="font-size:13px;color:#475569;margin:0 0 16px;padding-left:16px;line-height:1.8;">
                {{-- Diisi secara dinames oleh JS --}}
            </ul>
            <ul style="font-size:13px;color:#475569;margin:0 0 20px;padding-left:0;line-height:1.8;list-style-type:none;">
                <li style="display:flex;align-items:center;gap:6px;"><i data-lucide="check-circle" style="width:14px;height:14px;color:#166534;flex-shrink:0;"></i> Tagihan efektif toko akan <strong>berkurang</strong>.</li>
                <li style="display:flex;align-items:center;gap:6px;"><i data-lucide="alert-circle" style="width:14px;height:14px;color:#ea580c;flex-shrink:0;"></i> Tindakan ini <strong>tidak bisa dibatalkan</strong>.</li>
            </ul>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeReceiveModal()"
                        style="background:#fff;border:1px solid #cbd5e1;color:#475569;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">
                    Batal
                </button>
                <button type="button" onclick="document.getElementById('receiveForm').submit()"
                        style="background:#166534;color:#fff;border:none;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;">
                    Ya, Terima Return
                </button>
            </div>
        </div>
    </div>

    <script>
        function updateConditionStyles() {
            const layakJual = document.getElementById('label_layak_jual');
            const perluProsesUlang = document.getElementById('label_perlu_proses_ulang');
            const checked = document.querySelector('input[name="return_condition"]:checked');
            
            // reset styles
            layakJual.style.borderColor = '#cbd5e1';
            layakJual.style.background = '#fff';
            perluProsesUlang.style.borderColor = '#cbd5e1';
            perluProsesUlang.style.background = '#fff';
            
            if (checked) {
                if (checked.value === 'layak_jual') {
                    layakJual.style.borderColor = '#166534';
                    layakJual.style.background = '#f0fdf4';
                } else if (checked.value === 'perlu_proses_ulang') {
                    perluProsesUlang.style.borderColor = '#ca8a04';
                    perluProsesUlang.style.background = '#fefce8';
                }
            }
        }

        function openReceiveModal() {
            const checked = document.querySelector('input[name="return_condition"]:checked');
            if (!checked) {
                alert('Silakan pilih kondisi barang terlebih dahulu!');
                return;
            }
            
            const selectedText = checked.value === 'layak_jual' ? 'Layak Jual' : 'Perlu Proses Ulang';
            document.getElementById('selected_condition_text').innerText = selectedText;
            
            const stockInfo = document.getElementById('modal_stock_info');
            if (checked.value === 'layak_jual') {
                stockInfo.innerHTML = '<li>Stok siap jual di gudang akan <strong>bertambah</strong>.</li><li>Stock movement IN akan <strong>tercatat</strong>.</li>';
            } else {
                stockInfo.innerHTML = '<li>Stok siap jual di gudang <strong>TIDAK bertambah</strong>.</li><li>Stock movement <strong>TIDAK tercatat</strong>.</li><li>Proses fisik packing ulang ditangani manual di gudang.</li>';
            }
            
            document.getElementById('receiveModal').style.display = 'flex';
        }
        
        function closeReceiveModal() {
            document.getElementById('receiveModal').style.display = 'none';
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</x-layouts.admin>

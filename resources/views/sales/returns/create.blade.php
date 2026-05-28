<x-layouts.user>
    <x-slot name="title">Ajukan Return Barang</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:5px;font-size:13.5px;font-weight:600;color:var(--muted);text-decoration:none;margin-bottom:20px;transition:color 0.15s; }
        .back-link:hover { color:var(--text); }

        .page-header { margin-bottom:24px; }
        .page-title  { font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.02em; }

        /* Card panels */
        .panel-card { background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;margin-bottom:20px;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01); }
        .panel-header { margin-bottom: 16px; }
        .panel-title { font-size:13.5px;font-weight:800;color:var(--text);text-transform:uppercase;letter-spacing:0.06em;margin:0; }
        .panel-subtitle { font-size:12px;color:var(--muted);margin-top:2px; }

        .form-control {
            width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;
            font-size:13.5px;color:var(--text);background:#fff;transition:border-color 0.15s,box-shadow 0.15s;
        }
        .form-control:focus { border-color:var(--accent);outline:none;box-shadow:0 0 0 3px rgba(197, 160, 89, 0.15); }

        .btn-primary {
            background:var(--brown);color:#fff;border:none;padding:9.5px 18px;border-radius:8px;
            font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;
            transition:background 0.15s;
        }
        .btn-primary:hover { background:var(--brown-hover); }

        .btn-secondary {
            background:#fff;border:1px solid var(--border);color:var(--text);padding:9px 18px;border-radius:8px;
            text-decoration:none;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;
            transition:background 0.15s;
        }
        .btn-secondary:hover { background:var(--cream); }

        /* Table wrap with swipe info */
        .table-scroll-container { width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; border-radius:8px; border:1px solid var(--border); }
        .table-scroll-container table { width:100%; border-collapse:collapse; min-width:650px; }
        
        thead tr { background:var(--cream); border-bottom:1px solid var(--border); }
        th { padding:12px 16px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:0.07em; }
        td { padding:14px 16px; border-bottom:1px solid var(--border); font-size:13.5px; color:var(--text); vertical-align:middle; }
        tr:last-child td { border-bottom:none; }

        .qty-badge { background:#fffbeb; color:#b45309; font-weight:700; font-size:11px; padding:3px 8px; border-radius:6px; border:1px solid #fef3c7; display:inline-block; }

        .input-qty { width:70px; padding:6px 8px; border:1px solid var(--border); border-radius:6px; font-size:13px; text-align:center; font-weight:600; color:var(--text); }
        .input-qty:focus { border-color:var(--accent); outline:none; }

        .input-text { width:100%; padding:6px 10px; border:1px solid var(--border); border-radius:6px; font-size:12.5px; color:var(--text); }
        .input-text:focus { border-color:var(--accent); outline:none; }

        .swipe-hint {
            display:none; align-items:center; gap:6px; font-size:11.5px; color:var(--accent); font-weight:600;
            background:var(--cream); padding:8px 12px; border-radius:8px; margin-bottom:10px; border:1px solid var(--border);
        }

        @media (max-width: 768px) {
            .swipe-hint { display:flex; }
        }
    </style>

    <a href="{{ route('sales.returns.index') }}" class="back-link">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali ke Daftar Return
    </a>

    <div class="page-header">
        <h1 class="page-title">Ajukan Return Barang</h1>
    </div>

    {{-- Info Card --}}
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:12.5px;color:#1e40af;line-height:1.5; display:flex; align-items:flex-start; gap:10px;">
        <i data-lucide="info" style="width:18px;height:18px;color:#1e40af;flex-shrink:0;margin-top:1px;"></i>
        <div>
            <strong>Catatan Operasional:</strong> Return yang disetujui admin akan dikembalikan ke stok gudang.
            Jika barang dalam kondisi rusak atau butuh proses ulang fisik di pabrik, mohon catat keterangannya pada kolom <strong>Catatan Tambahan</strong>.
        </div>
    </div>

    @if(session('error'))
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
            <ul style="margin:0;padding-left:16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STEP 1: Pilih Laporan Pengiriman --}}
    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-title">1. Pilih Laporan Pengiriman</h3>
            <p class="panel-subtitle">Pilih laporan pengiriman dari toko yang mengajukan pengembalian barang.</p>
        </div>
        <form method="GET" action="{{ route('sales.returns.create') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <select name="delivery_report_id" class="form-control" required style="height:42px;">
                    <option value="">-- Pilih Laporan --</option>
                    @foreach($reports as $report)
                        <option value="{{ $report->id }}" {{ (isset($selectedReport) && $selectedReport->id == $report->id) ? 'selected' : '' }}>
                            {{ $report->report_number }} — {{ $report->toko_name }} ({{ $report->delivery_date->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary" style="height:42px; padding:0 24px;">
                Pilih Laporan
            </button>
        </form>
    </div>

    {{-- STEP 2: Form Item Return (hanya muncul setelah laporan dipilih) --}}
    @if(isset($selectedReport) && $itemsWithMaxReturn->isNotEmpty())
    <form method="POST" action="{{ route('sales.returns.store') }}" id="returnForm">
        @csrf

        <input type="hidden" name="delivery_report_id" value="{{ $selectedReport->id }}">

        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title">2. Informasi Return</h3>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;flex-wrap:wrap;">
                <div>
                    <label class="form-label">Laporan Pengiriman Terpilih</label>
                    <div style="padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--cream);font-size:13.5px;color:var(--text);font-weight:600;">
                        {{ $selectedReport->report_number }} ({{ $selectedReport->toko_name }})
                    </div>
                </div>
                <div>
                    <label class="form-label" for="return_date">Tanggal Return <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="return_date" id="return_date" class="form-control" value="{{ old('return_date', date('Y-m-d')) }}" required>
                </div>
            </div>

            <div>
                <label class="form-label" for="note">Catatan Tambahan (Opsional)</label>
                <textarea name="note" id="note" class="form-control" rows="2" placeholder="Tulis catatan jika barang rusak/perlu proses ulang fisiknya di pabrik.">{{ old('note') }}</textarea>
            </div>
        </div>

        {{-- Tabel Item Return --}}
        <div class="panel-card" style="padding:0; overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);background:var(--cream);">
                <h3 class="panel-title">3. Produk yang Dikembalikan</h3>
                <p class="panel-subtitle">Isi jumlah barang yang direturn. Kosongkan (0) jika tidak ada return untuk produk tersebut.</p>
            </div>

            <div class="swipe-hint" style="margin: 12px 16px 4px;">
                <i data-lucide="info" style="width:14px;height:14px;"></i>
                <span>Geser tabel ke samping untuk melihat seluruh kolom input</span>
            </div>

            <div class="table-scroll-container">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align:center;">Qty Kirim</th>
                            <th style="text-align:center;">Maks Return</th>
                            <th style="text-align:right;">Harga/pcs</th>
                            <th style="text-align:center;">Qty Return</th>
                            <th>Alasan Return</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itemsWithMaxReturn as $i => $item)
                        <tr>
                            <td>
                                <input type="hidden" name="items[{{ $i }}][delivery_report_item_id]" value="{{ $item->id }}">
                                <div style="font-weight:700;color:var(--text);font-size:13.5px;">{{ $item->product->name }}</div>
                                <div style="font-size:11px;color:var(--muted);margin-top:2px;">Kemasan: {{ $item->product->weight }} Gram</div>
                            </td>
                            <td style="text-align:center;font-weight:600;color:var(--muted);">{{ number_format($item->qty, 0, ',', '.') }} pcs</td>
                            <td style="text-align:center;">
                                <span class="qty-badge">
                                    Maks {{ number_format($item->max_return, 0, ',', '.') }} pcs
                                </span>
                            </td>
                            <td style="text-align:right;color:var(--muted);font-weight:500;">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td style="text-align:center;">
                                <input type="number" name="items[{{ $i }}][qty_return]"
                                       value="{{ old('items.' . $i . '.qty_return', 0) }}"
                                       min="0" max="{{ $item->max_return }}" class="input-qty">
                            </td>
                            <td style="padding-right: 18px;">
                                <input type="text" name="items[{{ $i }}][reason]"
                                       value="{{ old('items.' . $i . '.reason') }}"
                                       placeholder="Misal: Kemasan rusak, toko tutup" class="input-text">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;margin-bottom:30px;">
            <a href="{{ route('sales.returns.index') }}" class="btn-secondary">
                Batal
            </a>
            <button type="submit" id="submitBtn" class="btn-primary" style="padding:0 24px;">
                <i data-lucide="check" style="width:16px;height:16px;"></i> Ajukan Return
            </button>
        </div>
    </form>

    @elseif(isset($selectedReport) && $itemsWithMaxReturn->isEmpty())
        <div style="background:#fefbeb;border:1px solid #fef3c7;border-radius:12px;padding:24px;text-align:center;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);">
            <i data-lucide="alert-triangle" style="width:36px;height:36px;color:var(--accent);margin:0 auto 12px;display:block;"></i>
            <div style="font-size:14px;font-weight:700;color:var(--text);">Tidak ada item yang bisa direturn dari laporan ini.</div>
            <div style="font-size:12.5px;color:var(--muted);margin-top:4px;">Semua item sudah direturn maksimal, atau belum ada item terdaftar di laporan ini.</div>
        </div>
    @endif

    <script>
        document.getElementById('returnForm')?.addEventListener('submit', function(e) {
            const qtys = document.querySelectorAll('input[name*="[qty_return]"]');
            const hasQty = Array.from(qtys).some(input => parseInt(input.value) > 0);
            if (!hasQty) {
                e.preventDefault();
                alert('Minimal satu produk harus memiliki qty return lebih dari 0.');
            }
        });
    </script>
</x-layouts.user>

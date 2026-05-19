<x-layouts.user>
    <x-slot name="title">Buat Laporan Pengiriman</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:#78716c;text-decoration:none;margin-bottom:20px; }
        .back-link:hover { color:#1c1917; }
        .page-title { font-size:20px;font-weight:700;color:#1c1917;letter-spacing:-0.02em;margin-bottom:4px; }
        .page-desc  { font-size:13px;color:#78716c;margin-bottom:24px; }

        .empty-stok { background:#fff7ed;border:1px solid #fed7aa;border-radius:10px;padding:16px 20px;font-size:13.5px;color:#92400e; }
        .empty-stok strong { display:block;margin-bottom:4px; }

        .layout { display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start; }
        .card { background:#fff;border:1px solid #e7e5e4;border-radius:12px;overflow:hidden; }
        .card-header { padding:14px 18px;border-bottom:1px solid #f5f5f4;background:#fafaf9; }
        .card-header h3 { font-size:13.5px;font-weight:700;color:#1c1917;margin:0; }
        .card-body { padding:18px; }
        .field { margin-bottom:14px; }
        .field:last-child { margin-bottom:0; }
        label { display:block;font-size:12px;font-weight:600;color:#44403c;margin-bottom:5px; }
        .opt-label { font-weight:400;color:#a8a29e; }
        input[type="text"], input[type="tel"], input[type="date"], select, textarea {
            width:100%;padding:9px 11px;border:1px solid #d6d3d1;border-radius:8px;
            font-size:13.5px;font-family:inherit;background:#fff;transition:border-color 0.15s;
        }
        input:focus, select:focus, textarea:focus { outline:none;border-color:#92400e;box-shadow:0 0 0 3px rgba(146,64,14,0.08); }
        .err { font-size:11px;color:#dc2626;margin-top:3px; }

        /* Toggle: master vs manual */
        .toko-toggle { display:flex;gap:0;border:1px solid #d6d3d1;border-radius:8px;overflow:hidden;margin-bottom:14px; }
        .toko-toggle button {
            flex:1;padding:8px;font-size:12.5px;font-weight:600;border:none;background:#fff;color:#78716c;cursor:pointer;font-family:inherit;transition:all 0.15s;
        }
        .toko-toggle button.active { background:#92400e;color:#fff; }
        .toko-toggle button:first-child { border-right:1px solid #d6d3d1; }

        .hint-box { background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:10px 14px;font-size:12px;color:#1e40af;margin-bottom:14px; }

        /* Tabel items */
        .items-table { width:100%;border-collapse:collapse; }
        .items-table th { padding:10px 12px;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;background:#fafaf9;border-bottom:1px solid #e7e5e4;text-align:left; }
        .items-table td { padding:9px 8px;border-bottom:1px solid #f5f5f4;vertical-align:middle; }
        .items-table select, .items-table input[type="number"] { padding:7px 9px;font-size:13px; }
        .stok-hint { font-size:11px;color:#78716c;margin-top:3px; }
        .stok-warn-txt { color:#dc2626;font-weight:700;font-size:10px;margin-top:2px; }
        .btn-remove { background:none;border:none;color:#d6d3d1;cursor:pointer;font-size:20px;line-height:1;padding:0 4px;transition:color 0.15s; }
        .btn-remove:hover { color:#ef4444; }
        .btn-add-row { margin:10px 16px;background:none;border:1px dashed #d6d3d1;color:#78716c;padding:7px 14px;border-radius:8px;font-size:12.5px;font-weight:600;cursor:pointer;transition:all 0.15s;font-family:inherit; }
        .btn-add-row:hover { border-color:#92400e;color:#92400e;background:#fff7ed; }
        .total-row { display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-top:1px solid #e7e5e4;background:#fafaf9; }
        .total-label { font-size:12px;font-weight:700;color:#a8a29e;text-transform:uppercase; }
        .total-value { font-size:20px;font-weight:800;color:#92400e; }
        .btn-submit { width:100%;padding:12px;background:#92400e;color:white;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;transition:background 0.15s;font-family:inherit; }
        .btn-submit:hover:not(:disabled) { background:#78350f; }
        .btn-submit:disabled { background:#d6d3d1;cursor:not-allowed; }
    </style>

    <a href="{{ route('sales.delivery-reports.index') }}" class="back-link">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Kembali
    </a>
    <h1 class="page-title">Buat Laporan Pengiriman</h1>
    <p class="page-desc">Catat pengiriman/titipan barang ke toko. Stok Anda akan berkurang otomatis.</p>

    @if($salesStocks->isEmpty())
        <div class="empty-stok">
            <strong>Stok barang Anda kosong</strong>
            Anda belum memiliki stok barang. Ajukan barang ke gudang dan tunggu persetujuan admin.
            <a href="{{ route('sales.orders.create') }}" style="display:inline-block;margin-top:10px;font-weight:600;color:#92400e;text-decoration:none;">Buat Pengajuan Barang →</a>
        </div>
    @else

    @php
        $stockData = $salesStocks->map(fn($s) => [
            'id'    => $s->product_id,
            'name'  => $s->product->name . ' — ' . $s->product->weight . 'gr (Stok Anda: ' . number_format($s->qty, 0, ',', '.') . ' ' . ($s->product->unit->code ?? '') . ')',
            'unit'  => $s->product->unit->code ?? '',
            'price' => $s->product->price,
            'stok'  => $s->qty,
        ])->values();
    @endphp
    <script>const STOK = @json($stockData);</script>

    <form action="{{ route('sales.delivery-reports.store') }}" method="POST" id="form-del">
        @csrf
        <div class="layout">

            {{-- ── Kiri: Info ──────────────────────── --}}
            <div>

                {{-- Toko Tujuan --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header"><h3>Toko Tujuan</h3></div>
                    <div class="card-body">

                        {{-- Toggle: dari master atau input manual --}}
                        <div class="toko-toggle">
                            <button type="button" id="btn-manual" class="active" onclick="switchMode('manual')">Input Manual</button>
                            <button type="button" id="btn-master" onclick="switchMode('master')">Dari Daftar</button>
                        </div>

                        {{-- Mode: Input Manual --}}
                        <div id="section-manual">
                            <div class="hint-box">
                                Toko belum terdaftar? Isi nama toko langsung di bawah.
                            </div>
                            <div class="field">
                                <label>Nama Toko / Customer *</label>
                                <input type="text" name="customer_name_manual"
                                       value="{{ old('customer_name_manual') }}"
                                       placeholder="Contoh: Warung Bu Siti">
                                @error('customer_name_manual')<div class="err">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label>Alamat Toko *</label>
                                <input type="text" name="customer_address_manual"
                                       value="{{ old('customer_address_manual') }}"
                                       placeholder="Contoh: Jl. Mawar No. 5, Kota X">
                                @error('customer_address_manual')<div class="err">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label>No. Telepon *</label>
                                <input type="tel" name="customer_phone_manual"
                                       value="{{ old('customer_phone_manual') }}"
                                       placeholder="Contoh: 0812xxxxxxxx">
                                @error('customer_phone_manual')<div class="err">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Mode: Dari Master Customer --}}
                        <div id="section-master" style="display:none;">
                            <div class="field">
                                <label>Pilih dari Daftar Customer</label>
                                <select name="customer_id" id="sel-customer">
                                    <option value="">— Pilih Customer —</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id')==$c->id ? 'selected':'' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')<div class="err">{{ $message }}</div>@enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Info Pengiriman --}}
                <div class="card">
                    <div class="card-header"><h3>Info Pengiriman</h3></div>
                    <div class="card-body">
                        <div class="field">
                            <label>Tanggal Pengiriman *</label>
                            <input type="date" name="delivery_date"
                                   value="{{ old('delivery_date', date('Y-m-d')) }}" required>
                            @error('delivery_date')<div class="err">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>Tempo Pembayaran <span class="opt-label">(opsional)</span></label>
                            <select name="payment_term_days">
                                <option value="">— Tidak ada / Langsung —</option>
                                <option value="15" {{ old('payment_term_days')=='15' ? 'selected':'' }}>15 Hari</option>
                                <option value="30" {{ old('payment_term_days')=='30' ? 'selected':'' }}>30 Hari</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Catatan <span class="opt-label">(opsional)</span></label>
                            <textarea name="note" rows="2" placeholder="Contoh: Titip rutin minggu ini">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Kanan: Produk ────────────────────── --}}
            <div class="card" style="display:flex;flex-direction:column;">
                <div class="card-header"><h3>Produk yang Dikirim</h3></div>
                <div style="overflow-x:auto;">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width:35%;">Produk</th>
                                <th style="width:16%;">Stok Anda</th>
                                <th style="width:14%;">Qty Kirim</th>
                                <th style="width:15%;">Harga Jual</th>
                                <th style="width:14%;text-align:right;">Subtotal</th>
                                <th style="width:6%;"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr class="baris" data-idx="0">
                                <td>
                                    <select name="items[0][product_id]" onchange="onPilih(this,0)" style="width:100%;" required>
                                        <option value="">— Pilih —</option>
                                        @foreach($stockData as $s)
                                            <option value="{{ $s['id'] }}" data-stok="{{ $s['stok'] }}" data-price="{{ $s['price'] }}" data-unit="{{ $s['unit'] }}">{{ $s['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="stok-hint" id="hint-0">—</div>
                                </td>
                                <td>
                                    <input type="number" name="items[0][qty]" id="qty-0" min="0.01" step="0.01" placeholder="0" style="width:100%;" oninput="calc(0)">
                                    <div id="warn-0" class="stok-warn-txt" style="display:none;">⚠ Melebihi stok!</div>
                                </td>
                                <td>
                                    <input type="number" name="items[0][price]" id="price-0" style="width:100%;background:#f5f5f4;color:#a8a29e;" readonly>
                                </td>
                                <td style="text-align:right;font-weight:700;font-size:13px;" id="sub-0">Rp 0</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn-add-row" onclick="addRow()">+ Tambah Produk</button>

                <div class="total-row">
                    <span class="total-label">Total Nilai</span>
                    <span class="total-value" id="grand">Rp 0</span>
                </div>
                <div style="padding:0 18px 18px;">
                    <button type="submit" class="btn-submit" id="btn-sub">Simpan Laporan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        let rowCount = 1;
        const fmt = n => 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        /* ── Toggle mode toko ── */
        function switchMode(mode) {
            const isManual = mode === 'manual';
            document.getElementById('section-manual').style.display = isManual ? '' : 'none';
            document.getElementById('section-master').style.display = isManual ? 'none' : '';
            document.getElementById('btn-manual').classList.toggle('active', isManual);
            document.getElementById('btn-master').classList.toggle('active', !isManual);

            // Kosongkan field yang tidak aktif agar tidak terkirim ke server
            if (isManual) {
                document.getElementById('sel-customer').value = '';
            } else {
                ['customer_name_manual','customer_address_manual','customer_phone_manual']
                    .forEach(n => { const el = document.querySelector(`[name="${n}"]`); if(el) el.value = ''; });
            }
        }

        // Restore mode dari old() jika validasi gagal
        @if(old('customer_id'))
            switchMode('master');
        @endif

        /* ── Item table logic ── */
        function buildOpts() {
            return '<option value="">— Pilih —</option>' +
                STOK.map(s => `<option value="${s.id}" data-stok="${s.stok}" data-price="${s.price}" data-unit="${s.unit}">${s.name}</option>`).join('');
        }

        function onPilih(sel, idx) {
            const opt   = sel.options[sel.selectedIndex];
            const stok  = parseFloat(opt.dataset.stok)  || 0;
            const unit  = opt.dataset.unit  || '';
            const price = parseFloat(opt.dataset.price) || 0;

            document.getElementById('hint-' + idx).textContent = stok > 0 ? `${stok} ${unit}` : '⚠ Stok 0';
            const priceEl = document.getElementById('price-' + idx);
            if (priceEl) priceEl.value = price;
            calc(idx);
        }

        function calc(idx) {
            const sel   = document.querySelector(`[name="items[${idx}][product_id]"]`);
            const opt   = sel ? sel.options[sel.selectedIndex] : null;
            const stok  = opt ? parseFloat(opt.dataset.stok) || 0 : 0;
            const qty   = parseFloat(document.getElementById('qty-' + idx)?.value)   || 0;
            const price = parseFloat(document.getElementById('price-' + idx)?.value) || 0;

            document.getElementById('sub-' + idx).textContent = fmt(qty * price);
            const warn = document.getElementById('warn-' + idx);
            if (warn) warn.style.display = (qty > stok && stok > 0) ? '' : 'none';
            calcTotal();
        }

        function calcTotal() {
            let total = 0, hasWarn = false;
            document.querySelectorAll('.baris').forEach(tr => {
                const i = tr.dataset.idx;
                total += (parseFloat(document.getElementById('qty-'+i)?.value)||0)
                       * (parseFloat(document.getElementById('price-'+i)?.value)||0);
                if (document.getElementById('warn-'+i)?.style.display !== 'none') hasWarn = true;
            });
            document.getElementById('grand').textContent = fmt(total);
            document.getElementById('btn-sub').disabled = hasWarn;
        }

        function addRow() {
            const idx = rowCount++;
            const tr = document.createElement('tr');
            tr.className = 'baris';
            tr.dataset.idx = idx;
            tr.innerHTML = `
                <td><select name="items[${idx}][product_id]" onchange="onPilih(this,${idx})" style="width:100%;" required>${buildOpts()}</select></td>
                <td>
                    <div class="stok-hint" id="hint-${idx}">—</div>
                    <div id="warn-${idx}" class="stok-warn-txt" style="display:none;">⚠ Melebihi stok!</div>
                </td>
                <td><input type="number" name="items[${idx}][qty]"   id="qty-${idx}"   min="0.01" step="0.01" placeholder="0" style="width:100%;" oninput="calc(${idx})"></td>
                <td><input type="number" name="items[${idx}][price]" id="price-${idx}" style="width:100%;background:#f5f5f4;color:#a8a29e;" readonly></td>
                <td style="text-align:right;font-weight:700;font-size:13px;" id="sub-${idx}">Rp 0</td>
                <td><button type="button" class="btn-remove" onclick="this.closest('tr').remove();calcTotal();">×</button></td>
            `;
            document.getElementById('tbody').appendChild(tr);
        }

        lucide.createIcons();
    </script>
    @endif
</x-layouts.user>

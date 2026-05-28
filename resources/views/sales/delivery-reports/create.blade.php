<x-layouts.user>
    <x-slot name="title">Buat Laporan Pengiriman</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;margin-bottom:16px;transition:color 0.15s; }
        .back-link:hover { color:var(--text); }
        .page-title { font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.02em;margin-bottom:4px; }
        .page-desc  { font-size:13.5px;color:var(--muted);margin-bottom:24px; }

        .empty-stok {
            background:#fff;border:1px solid var(--border);border-radius:12px;
            padding:32px 24px;text-align:center;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
        }
        .empty-stok-emoji { font-size:38px;opacity:0.3;margin-bottom:12px; }
        .empty-stok strong { display:block;font-size:15px;color:var(--text);margin-bottom:6px; }
        .empty-stok p { font-size:13.5px;color:var(--muted);max-width:350px;margin:0 auto 18px;line-height:1.5; }
        .empty-stok-link {
            display:inline-block;background:var(--brown);color:#fff;font-weight:700;font-size:13px;
            padding:9.5px 18px;border-radius:8px;text-decoration:none;transition:all 0.15s ease-in-out;
            box-shadow:0 2px 4px rgba(42,23,14,0.1);
        }
        .empty-stok-link:hover { background:var(--brown-hover);box-shadow:0 4px 12px rgba(42,23,14,0.15); }

        /* ── Layout ──────────────────────── */
        .layout { display:grid;grid-template-columns:320px 1fr;gap:18px;align-items:start; }

        /* ── Card ────────────────────────── */
        .card { background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01); }
        .card + .card { margin-top:16px; }
        .card-header { padding:14px 18px;border-bottom:1px solid var(--border);background:var(--cream); }
        .card-header h3 { font-size:13.5px;font-weight:700;color:var(--text);margin:0; }
        .card-body { padding:16px 18px; }

        .field { margin-bottom:14px; }
        .field:last-child { margin-bottom:0; }
        label { display:block;font-size:11.5px;font-weight:700;color:var(--text);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px; }
        .opt-label { font-weight:400;color:var(--muted);text-transform:none;letter-spacing:0; }

        input[type="text"], input[type="tel"], input[type="date"], select, textarea {
            width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;
            font-size:13px;font-family:inherit;background:#fff;color:var(--text);
            transition:border-color 0.15s,box-shadow 0.15s;
        }
        input:focus, select:focus, textarea:focus {
            outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(197,160,89,0.12);
        }
        .err { font-size:11.5px;color:#dc2626;margin-top:4px;font-weight:500; }

        /* Segmented Toko Toggle */
        .toko-toggle {
            display:flex;background:var(--brown-light);padding:3px;border-radius:8px;
            margin-bottom:16px;border:1px solid var(--border);
        }
        .toko-toggle button {
            flex:1;padding:8px 12px;font-size:12.5px;font-weight:600;border:none;
            background:transparent;color:var(--muted);cursor:pointer;font-family:inherit;transition:all 0.2s ease-in-out;
            border-radius:6px;
        }
        .toko-toggle button.active { background:var(--brown);color:#fff;box-shadow:0 2px 6px rgba(42,23,14,0.15); }

        .hint-box {
            background:var(--cream);border:1px dashed var(--border);border-radius:8px;
            padding:10px 12px;font-size:12px;color:var(--muted);margin-bottom:14px;
            text-align:center;font-weight:500;
        }

        /* Items table */
        .items-table { width:100%;border-collapse:collapse; }
        .items-table th {
            padding:12px 14px;font-size:10px;font-weight:800;color:var(--muted);
            text-transform:uppercase;letter-spacing:0.07em;background:var(--cream);
            border-bottom:1px solid var(--border);text-align:left;
        }
        .items-table td { padding:12px 14px;border-bottom:1px solid var(--border);vertical-align:middle; }
        .items-table select, .items-table input[type="number"] { padding:8px 10px;font-size:13px; }

        .stok-hint     { font-size:11.5px;color:#166534;font-weight:700;margin-top:4px; }
        .stok-warn-txt { color:#991b1b;font-weight:700;font-size:11px;margin-top:4px; }

        .btn-remove { background:none;border:none;color:#d6d3d1;cursor:pointer;font-size:22px;line-height:1;padding:0 4px;transition:color 0.12s; }
        .btn-remove:hover { color:#ef4444; }

        .btn-add-row {
            margin:16px 18px;background:none;border:1px dashed var(--border);color:var(--muted);
            padding:9px 14px;border-radius:8px;font-size:13px;font-weight:600;
            cursor:pointer;transition:all 0.15s;font-family:inherit;
        }
        .btn-add-row:hover { border-color:var(--brown);color:var(--brown);background:var(--cream); }

        .total-row {
            display:flex;justify-content:space-between;align-items:center;
            padding:14px 18px;border-top:1px solid var(--border);background:var(--cream);
        }
        .total-label { font-size:11px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em; }
        .total-value { font-size:21px;font-weight:800;color:var(--text);letter-spacing:-0.02em; }

        .btn-submit {
            width:100%;padding:12px;background:var(--brown);color:#fff;border:none;
            border-radius:8px;font-size:13.5px;font-weight:700;cursor:pointer;
            transition:all 0.15s ease-in-out;font-family:inherit;
            box-shadow:0 2px 4px rgba(42,23,14,0.1);
        }
        .btn-submit:hover:not(:disabled) { background:var(--brown-hover);box-shadow:0 4px 12px rgba(42,23,14,0.15); }
        .btn-submit:disabled { background:#e7e5e4;color:#a8a29e;cursor:not-allowed;box-shadow:none; }

        @media (max-width: 767px) {
            .layout { grid-template-columns: 1fr; }
            .items-table { min-width: 650px; } /* Prevent squishing and allow smooth thumb scroll on mobile! */
        }
    </style>

    <a href="{{ route('sales.delivery-reports.index') }}" class="back-link">← Kembali ke Riwayat</a>
    <h1 class="page-title">Buat Laporan Pengiriman</h1>
    <p class="page-desc">Catat barang yang Anda kirimkan ke toko. Stok Anda akan berkurang otomatis setelah disimpan.</p>

    @if($salesStocks->isEmpty())
        <div class="empty-stok">
            <div class="empty-stok-emoji">📦</div>
            <strong>Stok barang Anda kosong</strong>
            <p>Anda belum memiliki stok barang untuk dikirimkan. Ajukan permintaan barang ke gudang terlebih dahulu.</p>
            <a href="{{ route('sales.orders.create') }}" class="empty-stok-link">+ Buat Pengajuan Barang</a>
        </div>
    @else

    @php
        $stockData = $salesStocks->map(fn($s) => [
            'id'    => $s->product_id,
            'name'  => $s->product->name . ' — ' . $s->product->weight . ' Gram',
            'unit'  => $s->product->unit->code ?? '',
            'price' => $s->product->price,
            'stok'  => $s->qty,
        ])->values();
    @endphp
    <script>const STOK = @json($stockData);</script>

    <form action="{{ route('sales.delivery-reports.store') }}" method="POST" id="form-del">
        @csrf
        <div class="layout">

            {{-- ── Kiri: Info ─────────────────────────────── --}}
            <div>

                {{-- Toko Tujuan --}}
                <div class="card">
                    <div class="card-header"><h3>Toko Tujuan</h3></div>
                    <div class="card-body">

                        <div class="toko-toggle">
                            <button type="button" id="btn-manual" class="active" onclick="switchMode('manual')">Toko Baru</button>
                            <button type="button" id="btn-master" onclick="switchMode('master')">Dari Data Admin</button>
                        </div>

                        {{-- Mode: Input Manual --}}
                        <div id="section-manual">
                            <div class="hint-box">Gunakan ini jika toko belum ada di daftar admin.</div>
                            <div class="field">
                                <label>Nama Toko *</label>
                                <input type="text" name="customer_name_manual"
                                       value="{{ old('customer_name_manual') }}"
                                       placeholder="Contoh: Warung Bu Siti">
                                @error('customer_name_manual')<div class="err">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label>Alamat *</label>
                                <input type="text" name="customer_address_manual"
                                       value="{{ old('customer_address_manual') }}"
                                       placeholder="Contoh: Jl. Mawar No. 5">
                                @error('customer_address_manual')<div class="err">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label>No. HP *</label>
                                <input type="tel" name="customer_phone_manual"
                                       value="{{ old('customer_phone_manual') }}"
                                       placeholder="Contoh: 0812xxxxxxxx">
                                @error('customer_phone_manual')<div class="err">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Mode: Dari Master Customer --}}
                        <div id="section-master" style="display:none;">
                            <div class="hint-box">Pilih toko/customer yang sudah terdaftar oleh admin.</div>
                            <div class="field">
                                <label>Pilih Toko</label>
                                <select name="customer_id" id="sel-customer">
                                    <option value="">— Pilih Toko —</option>
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
                    <div class="card-header"><h3>Info Pengiriman & Pembayaran</h3></div>
                    <div class="card-body">
                        <div class="field">
                            <label>Tanggal Kirim *</label>
                            <input type="date" name="delivery_date"
                                   value="{{ old('delivery_date', date('Y-m-d')) }}" required>
                            @error('delivery_date')<div class="err">{{ $message }}</div>@enderror
                        </div>

                        <div style="background:#fdf9f5;border:1px solid #f5ebe0;border-radius:8px;padding:12px 14px;margin-bottom:8px;">
                            <div style="font-size:12px;font-weight:700;color:#92400e;margin-bottom:4px;">ℹ Pembayaran / DP</div>
                            <div style="font-size:12px;color:#78716c;line-height:1.5;">Pembayaran dan DP dicatat melalui menu <strong>Setoran Uang</strong> setelah laporan ini disimpan. Setoran akan diverifikasi oleh Admin.</div>
                        </div>

                        <div class="field">
                            <label>Tempo Pembayaran <span class="opt-label">(opsional)</span></label>
                            <select name="payment_term_days" id="payment_term_days" onchange="calcDueDate()">
                                <option value="">— Langsung / Cash —</option>
                                <option value="15" {{ old('payment_term_days')=='15' ? 'selected':'' }}>15 Hari</option>
                                <option value="30" {{ old('payment_term_days')=='30' ? 'selected':'' }}>30 Hari</option>
                            </select>
                            <div class="stok-hint" id="due_date_hint" style="display:none; margin-top:4px;">Jatuh Tempo: <span></span></div>
                        </div>

                        <div class="field">
                            <label>Catatan <span class="opt-label">(opsional)</span></label>
                            <textarea name="note" rows="2" placeholder="Contoh: Titip rutin minggu ini">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Kanan: Produk ────────────────────────────── --}}
            <div class="card" style="display:flex;flex-direction:column;">
                <div class="card-header"><h3>Produk yang Dikirim</h3></div>
                <div style="overflow-x:auto;">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width:38%;">Produk</th>
                                <th style="width:14%;">Stok Anda</th>
                                <th style="width:14%;">Qty Kirim</th>
                                <th style="width:15%;">Harga Jual</th>
                                <th style="width:15%;text-align:right;">Subtotal</th>
                                <th style="width:4%;"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr class="baris" data-idx="0">
                                <td>
                                    <select name="items[0][product_id]" onchange="onPilih(this,0)" style="width:100%;" required>
                                        <option value="">— Pilih Produk —</option>
                                        @foreach($stockData as $s)
                                            <option value="{{ $s['id'] }}" data-stok="{{ $s['stok'] }}" data-price="{{ $s['price'] }}" data-unit="{{ $s['unit'] }}">{{ $s['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="stok-hint" id="hint-0">—</div>
                                </td>
                                <td>
                                    <input type="number" name="items[0][qty]" id="qty-0" min="1" step="1" placeholder="0" style="width:100%;" oninput="calc(0)" required>
                                    <div id="warn-0" class="stok-warn-txt" style="display:none;">⚠ Melebihi stok!</div>
                                </td>
                                <td>
                                    <input type="number" name="items[0][price]" id="price-0" style="width:100%;background:#fafaf8;color:#a8a29e;font-weight:600;" readonly>
                                </td>
                                <td style="text-align:right;font-weight:700;font-size:13px;color:#1c1917;" id="sub-0">Rp 0</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn-add-row" onclick="addRow()">+ Tambah Baris Produk</button>

                <div class="total-row">
                    <span class="total-label">Total Nilai</span>
                    <span class="total-value" id="grand">Rp 0</span>
                </div>
                <div style="padding:0 18px 18px;">
                    <button type="submit" class="btn-submit" id="btn-sub" disabled>Simpan Laporan Pengiriman</button>
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

            if (isManual) {
                document.getElementById('sel-customer').value = '';
            } else {
                ['customer_name_manual','customer_address_manual','customer_phone_manual']
                    .forEach(n => { const el = document.querySelector(`[name="${n}"]`); if(el) el.value = ''; });
            }
        }

        @if(old('customer_id'))
            switchMode('master');
        @endif

        /* ── Item table logic ── */
        function buildOpts() {
            return '<option value="">— Pilih Produk —</option>' +
                STOK.map(s => `<option value="${s.id}" data-stok="${s.stok}" data-price="${s.price}" data-unit="${s.unit}">${s.name}</option>`).join('');
        }

        function onPilih(sel, idx) {
            const opt   = sel.options[sel.selectedIndex];
            const stok  = parseFloat(opt.dataset.stok)  || 0;
            const unit  = opt.dataset.unit  || '';
            const price = parseFloat(opt.dataset.price) || 0;

            const hintEl = document.getElementById('hint-' + idx);
            if (stok > 0) {
                hintEl.textContent = `${stok} ${unit}`;
                hintEl.style.color = '#16a34a';
            } else {
                hintEl.textContent = '⚠ Stok 0';
                hintEl.style.color = '#dc2626';
            }

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
            let total = 0, hasWarn = false, hasItems = false;
            document.querySelectorAll('.baris').forEach(tr => {
                const i = tr.dataset.idx;
                const qty = parseFloat(document.getElementById('qty-'+i)?.value)||0;
                const price = parseFloat(document.getElementById('price-'+i)?.value)||0;
                if (qty > 0) hasItems = true;
                total += qty * price;
                if (document.getElementById('warn-'+i)?.style.display !== 'none') hasWarn = true;
            });
            document.getElementById('grand').textContent = fmt(total);
            document.getElementById('btn-sub').disabled = hasWarn || !hasItems;
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
                <td><input type="number" name="items[${idx}][qty]" id="qty-${idx}" min="1" step="1" placeholder="0" style="width:100%;" oninput="calc(${idx})" required></td>
                <td><input type="number" name="items[${idx}][price]" id="price-${idx}" style="width:100%;background:#fafaf8;color:#a8a29e;font-weight:600;" readonly></td>
                <td style="text-align:right;font-weight:700;font-size:13px;color:#1c1917;" id="sub-${idx}">Rp 0</td>
                <td><button type="button" class="btn-remove" onclick="this.closest('tr').remove();calcTotal();">×</button></td>
            `;
            document.getElementById('tbody').appendChild(tr);
        }

        /* ── Hitung Jatuh Tempo ── */

        function calcDueDate() {
            const term = document.getElementById('payment_term_days').value;
            const deliveryDateStr = document.querySelector('input[name="delivery_date"]').value;
            const hint = document.getElementById('due_date_hint');
            
            if (term && deliveryDateStr) {
                const date = new Date(deliveryDateStr);
                date.setDate(date.getDate() + parseInt(term));
                
                const d = String(date.getDate()).padStart(2, '0');
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const y = date.getFullYear();
                
                hint.querySelector('span').textContent = `${d}-${m}-${y}`;
                hint.style.display = '';
            } else {
                hint.style.display = 'none';
            }
        }

        calcDueDate();
        document.querySelector('input[name="delivery_date"]').addEventListener('change', calcDueDate);

    </script>
    @endif
</x-layouts.user>

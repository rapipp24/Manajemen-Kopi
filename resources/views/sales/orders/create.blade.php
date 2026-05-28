<x-layouts.user>
    <x-slot name="title">Buat Pengajuan Barang</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;margin-bottom:18px;transition:color 0.15s; }
        .back-link:hover { color:var(--text); }

        .page-title { font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.02em;margin-bottom:4px; }
        .page-desc  { font-size:13.5px;color:var(--muted);margin-bottom:24px; }

        /* ── Layout ──────────────────────────── */
        .layout { display:grid;grid-template-columns:320px 1fr;gap:18px;align-items:start; }

        /* ── Card ────────────────────────────── */
        .card { background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.02); }
        .card + .card { margin-top:16px; }
        .card-header { padding:14px 18px;border-bottom:1px solid var(--border);background:var(--cream); }
        .card-header h3 { font-size:13.5px;font-weight:700;color:var(--text);margin:0; }
        .card-header p  { font-size:11.5px;color:var(--muted);margin:3px 0 0;font-weight:500; }
        .card-body { padding:16px 18px; }

        /* ── Form fields ─────────────────────── */
        .field { margin-bottom:14px; }
        .field:last-child { margin-bottom:0; }
        label { display:block;font-size:11.5px;font-weight:700;color:var(--text);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px; }
        select, textarea, input[type="number"] {
            width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;
            font-size:13px;font-family:inherit;background:#fff;color:var(--text);
            transition:border-color 0.15s,box-shadow 0.15s;
        }
        select:focus, textarea:focus, input:focus {
            outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(197,160,89,0.12);
        }

        /* ── Add-item button ─────────────────── */
        .btn-add {
            width:100%;padding:10px;background:var(--brown);color:#fff;
            border:none;border-radius:8px;font-size:13px;font-weight:700;
            cursor:pointer;margin-top:6px;transition:all 0.15s ease-in-out;font-family:inherit;
        }
        .btn-add:hover { background:var(--brown-hover); }

        /* ── Item list ───────────────────────── */
        .item-empty { padding:48px 20px;text-align:center; }
        .item-empty-emoji { font-size:32px;opacity:0.25;margin-bottom:8px; }
        .item-empty p { font-size:13px;color:var(--muted);line-height:1.5; }

        .item-row {
            display:flex;align-items:center;gap:12px;
            padding:12px 18px;border-bottom:1px solid var(--border);
            transition: background 0.15s;
        }
        .item-row:hover { background: var(--cream); }
        .item-row:last-child { border-bottom:none; }
        .item-name { flex:1;font-size:13.5px;font-weight:700;color:var(--text);line-height:1.4; }
        .item-qty  { font-size:13px;font-weight:700;color:var(--text);min-width:32px;text-align:center;background:var(--brown-light);padding:2px 6px;border-radius:4px; }
        .item-est  { font-size:13px;font-weight:700;color:var(--text);min-width:90px;text-align:right; }
        .item-del  { background:none;border:none;color:#d6d3d1;cursor:pointer;font-size:20px;line-height:1;padding:2px 4px;transition:color 0.12s; }
        .item-del:hover { color:#ef4444; }

        /* ── Total & Submit ──────────────────── */
        .total-row {
            display:flex;justify-content:space-between;align-items:center;
            padding:14px 18px;border-top:1px solid var(--border);background:var(--cream);
        }
        .total-label { font-size:11px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em; }
        .total-value { font-size:20px;font-weight:800;color:var(--text);letter-spacing:-0.02em; }

        .btn-submit {
            width:100%;padding:12px;background:var(--brown);color:#fff;border:none;
            border-radius:8px;font-size:13.5px;font-weight:700;cursor:pointer;
            transition:all 0.15s ease-in-out;font-family:inherit;
            box-shadow:0 2px 4px rgba(42,23,14,0.1);
        }
        .btn-submit:hover { background:var(--brown-hover);box-shadow:0 4px 12px rgba(42,23,14,0.15); }

        .badge-count {
            background:var(--brown);color:#fff;
            padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;margin-left:6px;
        }

        /* ── Responsive ──────────────────────── */
        @media (max-width:767px) { 
            .layout { grid-template-columns:1fr; } 
        }
    </style>

    <a href="{{ route('sales.orders.index') }}" class="back-link">← Kembali</a>

    <h1 class="page-title">Buat Pengajuan Barang</h1>
    <p class="page-desc">Pilih produk dan jumlah yang ingin diambil dari gudang. Pengajuan akan direview oleh admin.</p>

    <form action="{{ route('sales.orders.store') }}" method="POST" id="orderForm">
        @csrf
        <div class="layout">

            {{-- ── Kiri ─────────────────────── --}}
            <div>
                <div class="card">
                    <div class="card-header">
                        <h3>Informasi Pengajuan</h3>
                        <p>Isi tujuan dan catatan jika diperlukan</p>
                    </div>
                    <div class="card-body">
                        <div class="field">
                            <label>Tujuan Toko <span style="color:#a8a29e;font-weight:400;">(opsional)</span></label>
                            <select name="customer_id">
                                <option value="">— Stok Keliling —</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Catatan <span style="color:#a8a29e;font-weight:400;">(opsional)</span></label>
                            <textarea name="catatan" rows="2" placeholder="Contoh: Untuk stok minggu ini">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Tambah Produk</h3>
                        <p>Pilih produk dan tentukan jumlahnya</p>
                    </div>
                    <div class="card-body">
                        <div class="field">
                            <label>Produk</label>
                            <select id="product_selector">
                                <option value="">— Cari produk —</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}"
                                            data-name="{{ $p->name }} — {{ $p->weight }} Gram"
                                            data-price="{{ $p->price }}"
                                            data-stock="{{ $p->current_stock }}"
                                            data-unit="{{ $p->unit->code ?? '' }}">
                                        {{ $p->name }} — {{ $p->weight }} Gram
                                        @if($p->current_stock > 0)
                                            (Stok: {{ number_format($p->current_stock, 0, ',', '.') }})
                                        @else
                                            (Stok Habis)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <span id="stock_info" style="font-size:11.5px;color:#78716c;display:none;margin-top:4px;font-weight:500;"></span>
                        </div>
                        <div class="field">
                            <label>Jumlah yang Diminta</label>
                            <input type="number" id="qty_selector" value="1" min="1" placeholder="0">
                        </div>
                        <div id="stock_error_msg" style="display:none;margin: 0 0 12px 0;padding:10px 14px;background:#fef2f2;border:1px solid #fee2e2;border-radius:8px;font-size:12.5px;color:#991b1b;line-height:1.4;font-weight:500;"></div>
                        <button type="button" id="add_item" class="btn-add">+ Tambah ke Daftar</button>
                    </div>
                </div>
            </div>

            {{-- ── Kanan: Daftar Item ──────── --}}
            <div class="card" style="display:flex;flex-direction:column;">
                <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <h3 style="display:inline;">Daftar Barang</h3>
                        <span class="badge-count" id="item_count_badge">0</span>
                    </div>
                </div>

                <div id="items_area" style="flex:1;min-height:180px;">
                    <div class="item-empty" id="empty_state">
                        <div class="item-empty-emoji">📋</div>
                        <p>Belum ada produk dipilih.<br>Pilih produk di sebelah kiri<br>lalu klik Tambah.</p>
                    </div>
                    <div id="items_container"></div>
                </div>

                <div class="total-row">
                    <span class="total-label">Total Estimasi</span>
                    <span class="total-value" id="grand_total">Rp 0</span>
                </div>
                <div style="padding:0 18px 18px;">
                    <button type="submit" class="btn-submit">Kirim Pengajuan ke Gudang</button>
                </div>
            </div>

        </div>
    </form>

    <script>
        const productSelector = document.getElementById('product_selector');
        const qtySelector     = document.getElementById('qty_selector');
        const addItemBtn      = document.getElementById('add_item');
        const itemsContainer  = document.getElementById('items_container');
        const emptyState      = document.getElementById('empty_state');
        const grandTotalEl    = document.getElementById('grand_total');
        const itemCountBadge  = document.getElementById('item_count_badge');
        const form            = document.getElementById('orderForm');
        const stockInfoEl     = document.getElementById('stock_info');
        const stockErrorMsgEl = document.getElementById('stock_error_msg');

        let items = [];
        let errorTimeout = null;

        function rp(n) { return 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

        function showStockError(message) {
            stockErrorMsgEl.textContent = message;
            stockErrorMsgEl.style.display = 'block';

            if (errorTimeout) {
                clearTimeout(errorTimeout);
            }

            // Hilang otomatis setelah 6 detik
            errorTimeout = setTimeout(function () {
                hideStockError();
            }, 6000);
        }

        function hideStockError() {
            stockErrorMsgEl.style.display = 'none';
            stockErrorMsgEl.textContent = '';
            if (errorTimeout) {
                clearTimeout(errorTimeout);
                errorTimeout = null;
            }
        }

        productSelector.addEventListener('change', function () {
            hideStockError();
            const opt = this.options[this.selectedIndex];
            if (!this.value) {
                stockInfoEl.style.display = 'none';
                qtySelector.removeAttribute('max');
                return;
            }
            const stock = parseFloat(opt.dataset.stock) || 0;
            const unit = opt.dataset.unit || 'pcs';
            
            stockInfoEl.textContent = `Stok tersedia di gudang: ${Math.round(stock)} ${unit}`;
            stockInfoEl.style.display = 'block';
            qtySelector.setAttribute('max', Math.round(stock));
        });

        qtySelector.addEventListener('input', function () {
            hideStockError();
        });

        qtySelector.addEventListener('change', function () {
            hideStockError();
        });

        addItemBtn.addEventListener('click', function () {
            const id  = productSelector.value;
            const qty = parseInt(qtySelector.value) || 0;
            if (!id)    { showStockError('Pilih produk terlebih dahulu.'); return; }
            if (qty < 1){ showStockError('Jumlah minimal 1.'); return; }

            const opt   = productSelector.options[productSelector.selectedIndex];
            const name  = opt.dataset.name;
            const price = parseFloat(opt.dataset.price);
            const stock = parseInt(opt.dataset.stock) || 0;

            // Hitung akumulasi qty produk yang sama yang sudah ada di daftar
            let currentQtyInList = 0;
            const idx = items.findIndex(i => i.id === id);
            if (idx > -1) {
                currentQtyInList = items[idx].qty;
            }

            const totalRequestedQty = currentQtyInList + qty;

            if (totalRequestedQty > stock) {
                showStockError(`Qty melebihi stok gudang. Stok tersedia: ${stock} pcs. (Di daftar: ${currentQtyInList} pcs, diajukan tambahan: ${qty} pcs)`);
                return;
            }

            if (idx > -1) {
                items[idx].qty += qty;
                items[idx].sub  = items[idx].qty * price;
            } else {
                items.push({ id, name, price, qty, sub: qty * price });
            }
            render();
            productSelector.value = '';
            qtySelector.value = 1;
            stockInfoEl.style.display = 'none';
            qtySelector.removeAttribute('max');
            hideStockError();
        });

        function render() {
            itemCountBadge.textContent = items.length;
            if (items.length === 0) {
                emptyState.style.display = '';
                itemsContainer.innerHTML = '';
                grandTotalEl.textContent = 'Rp 0';
                return;
            }
            emptyState.style.display = 'none';
            let total = 0, html = '';
            items.forEach((item, i) => {
                total += item.sub;
                html += `<div class="item-row">
                    <input type="hidden" name="items[${i}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${i}][qty]"        value="${item.qty}">
                    <span class="item-name">${item.name}</span>
                    <span class="item-qty">${item.qty}×</span>
                    <span class="item-est">${rp(item.sub)}</span>
                    <button type="button" class="item-del" onclick="removeItem(${i})">×</button>
                </div>`;
            });
            itemsContainer.innerHTML = html;
            grandTotalEl.textContent = rp(total);
        }

        window.removeItem = i => { items.splice(i, 1); render(); };

        form.addEventListener('submit', e => {
            if (items.length === 0) {
                e.preventDefault();
                showStockError('Tambahkan minimal 1 produk.');
            }
        });
    </script>

</x-layouts.user>

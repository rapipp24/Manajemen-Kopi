<x-layouts.user>
    <x-slot name="title">Buat Pengajuan Barang</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:#78716c;text-decoration:none;margin-bottom:20px; }
        .back-link:hover { color:#1c1917; }

        .page-title { font-size:20px;font-weight:700;color:#1c1917;letter-spacing:-0.02em;margin-bottom:4px; }
        .page-desc  { font-size:13px;color:#78716c;margin-bottom:24px; }

        .layout { display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start; }

        .card { background:#fff;border:1px solid #e7e5e4;border-radius:12px;overflow:hidden; }
        .card-header { padding:14px 18px;border-bottom:1px solid #f5f5f4;background:#fafaf9; }
        .card-header h3 { font-size:13.5px;font-weight:700;color:#1c1917;margin:0; }
        .card-body { padding:18px; }

        label { display:block;font-size:12px;font-weight:600;color:#44403c;margin-bottom:5px; }
        select, textarea, input[type="number"] {
            width:100%;padding:9px 11px;border:1px solid #d6d3d1;border-radius:8px;
            font-size:13.5px;font-family:inherit;background:#fff;transition:border-color 0.15s;
        }
        select:focus, textarea:focus, input:focus { outline:none;border-color:#92400e;box-shadow:0 0 0 3px rgba(146,64,14,0.08); }
        .field { margin-bottom:14px; }
        .field:last-child { margin-bottom:0; }

        .btn-add { width:100%;padding:10px;background:#1c1917;color:white;border:none;border-radius:8px;font-size:13.5px;font-weight:700;cursor:pointer;margin-top:4px;transition:background 0.15s;font-family:inherit; }
        .btn-add:hover { background:#292524; }

        /* Item list */
        .item-empty { padding:48px 20px;text-align:center;color:#a8a29e; }
        .item-empty p { font-size:13px;margin-top:8px; }

        .item-row { display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid #f5f5f4; }
        .item-row:last-child { border-bottom:none; }
        .item-name { flex:1;font-size:13.5px;font-weight:600;color:#1c1917; }
        .item-qty  { font-size:13px;font-weight:700;color:#44403c;min-width:32px;text-align:center; }
        .item-est  { font-size:12px;color:#78716c;min-width:80px;text-align:right; }
        .item-del  { background:none;border:none;color:#d6d3d1;cursor:pointer;font-size:18px;line-height:1;padding:2px 4px;transition:color 0.15s; }
        .item-del:hover { color:#ef4444; }

        .total-row { display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-top:1px solid #e7e5e4;background:#fafaf9; }
        .total-label { font-size:12px;font-weight:700;color:#a8a29e;text-transform:uppercase; }
        .total-value { font-size:20px;font-weight:800;color:#92400e; }

        .btn-submit { width:100%;padding:12px;background:#92400e;color:white;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;transition:background 0.15s;font-family:inherit;margin-top:0; }
        .btn-submit:hover { background:#78350f; }

        .badge-count { background:#92400e;color:white;padding:2px 7px;border-radius:10px;font-size:10px;font-weight:700;margin-left:4px; }
    </style>

    <a href="{{ route('sales.orders.index') }}" class="back-link">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Kembali
    </a>

    <h1 class="page-title">Buat Pengajuan Barang</h1>
    <p class="page-desc">Pilih produk dan jumlah yang ingin diambil dari gudang. Pengajuan akan direview oleh admin.</p>

    <form action="{{ route('sales.orders.store') }}" method="POST" id="orderForm">
        @csrf
        <div class="layout">
            {{-- Kiri: Form --}}
            <div>
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header"><h3>Informasi Pengajuan</h3></div>
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
                    <div class="card-header"><h3>Tambah Produk</h3></div>
                    <div class="card-body">
                        <div class="field">
                            <label>Pilih Produk</label>
                            <select id="product_selector">
                                <option value="">— Cari Produk —</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}"
                                            data-name="{{ $p->name }} — {{ $p->weight }}gr"
                                            data-price="{{ $p->price }}"
                                            data-stock="{{ $p->current_stock }}"
                                            data-unit="{{ $p->unit->code ?? '' }}">
                                        {{ $p->name }} — {{ $p->weight }}gr
                                        @if($p->current_stock > 0)
                                            (Gudang: {{ number_format($p->current_stock, 0, ',', '.') }} {{ $p->unit->code ?? '' }})
                                        @else
                                            (Stok Habis)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Jumlah (Qty)</label>
                            <input type="number" id="qty_selector" value="1" min="1" placeholder="0">
                        </div>
                        <button type="button" id="add_item" class="btn-add">Tambah ke Daftar</button>
                    </div>
                </div>
            </div>

            {{-- Kanan: Daftar Item --}}
            <div class="card" style="display:flex;flex-direction:column;">
                <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                    <h3>Daftar Barang <span class="badge-count" id="item_count_badge">0</span></h3>
                    <span id="item_count_text" style="font-size:12px;color:#78716c;"></span>
                </div>

                <div id="items_area" style="flex:1;min-height:200px;">
                    <div class="item-empty" id="empty_state">
                        <i data-lucide="package" style="width:32px;height:32px;margin:0 auto;display:block;opacity:0.2;"></i>
                        <p>Belum ada produk dipilih.<br>Pilih produk di sebelah kiri.</p>
                    </div>
                    <div id="items_container"></div>
                </div>

                <div class="total-row">
                    <span class="total-label">Total Estimasi</span>
                    <span class="total-value" id="grand_total">Rp 0</span>
                </div>
                <div style="padding:0 18px 18px;">
                    <button type="submit" class="btn-submit">Kirim Pengajuan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        const productSelector   = document.getElementById('product_selector');
        const qtySelector       = document.getElementById('qty_selector');
        const addItemBtn        = document.getElementById('add_item');
        const itemsContainer    = document.getElementById('items_container');
        const emptyState        = document.getElementById('empty_state');
        const grandTotalEl      = document.getElementById('grand_total');
        const itemCountBadge    = document.getElementById('item_count_badge');
        const form              = document.getElementById('orderForm');

        let items = [];

        function rp(n) { return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

        addItemBtn.addEventListener('click', function () {
            const id = productSelector.value;
            const qty = parseInt(qtySelector.value) || 0;
            if (!id) { alert('Pilih produk terlebih dahulu.'); return; }
            if (qty < 1) { alert('Jumlah minimal 1.'); return; }

            const opt   = productSelector.options[productSelector.selectedIndex];
            const name  = opt.dataset.name;
            const price = parseFloat(opt.dataset.price);

            const idx = items.findIndex(i => i.id === id);
            if (idx > -1) {
                items[idx].qty += qty;
                items[idx].sub  = items[idx].qty * price;
            } else {
                items.push({ id, name, price, qty, sub: qty * price });
            }
            render();
            productSelector.value = '';
            qtySelector.value = 1;
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
                    <span class="item-qty">${item.qty}x</span>
                    <span class="item-est">${rp(item.sub)}</span>
                    <button type="button" class="item-del" onclick="removeItem(${i})">×</button>
                </div>`;
            });
            itemsContainer.innerHTML = html;
            grandTotalEl.textContent = rp(total);
        }

        window.removeItem = i => { items.splice(i, 1); render(); };

        form.addEventListener('submit', e => {
            if (items.length === 0) { e.preventDefault(); alert('Tambahkan minimal 1 produk.'); }
        });

        lucide.createIcons();
    </script>
</x-layouts.user>

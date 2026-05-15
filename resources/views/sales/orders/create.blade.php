<x-layouts.user>
    <x-slot name="title">Buat Pengajuan Barang</x-slot>

    <div style="max-width: 1100px; margin: 0 auto;">
        <div style="margin-bottom: 24px;">
            <a href="{{ route('sales.orders.index') }}" style="color: #92400e; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Daftar Pengajuan
            </a>
            <h1 style="font-size: 24px; font-weight: 700; color: #1c1917; margin-top: 12px;">Buat Pengajuan Barang</h1>
            <p style="color: #78716c; font-size: 14px;">Ajukan pengambilan beberapa stok produk sekaligus dari gudang utama.</p>
        </div>

        <form action="{{ route('sales.orders.store') }}" method="POST" id="orderForm">
            @csrf
            <div style="display: grid; grid-template-columns: 350px 1fr; gap: 24px; align-items: start;">
                
                <!-- Kolom Kiri: Form Input -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    
                    <!-- 1. Info Pengajuan -->
                    <div style="background: white; padding: 20px; border-radius: 16px; border: 1px solid #e7e5e4; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                        <h3 style="font-size: 15px; font-weight: 700; color: #1c1917; margin-bottom: 16px;">1. Informasi Utama</h3>
                        
                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #44403c; margin-bottom: 6px;">Tujuan Member (Opsional)</label>
                            <select name="customer_id" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
                                <option value="">-- Stok Pribadi / Keliling --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #44403c; margin-bottom: 6px;">Catatan</label>
                            <textarea name="catatan" rows="2" placeholder="Contoh: Untuk stok minggu ini" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; font-family: inherit;">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <!-- 2. Pilih Barang -->
                    <div style="background: #fafaf9; padding: 20px; border-radius: 16px; border: 2px dashed #e7e5e4;">
                        <h3 style="font-size: 15px; font-weight: 700; color: #1c1917; margin-bottom: 16px;">2. Tambah Barang</h3>
                        
                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #44403c; margin-bottom: 6px;">Pilih Produk</label>
                            <select id="product_selector" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; background: white;">
                                <option value="">-- Cari Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                        {{ $product->name }} (Sisa: {{ number_format($product->current_stock, 0, ',', '.') }} {{ $product->unit->name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #44403c; margin-bottom: 6px;">Jumlah (Qty)</label>
                            <input type="number" id="qty_selector" value="1" min="1" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
                        </div>

                        <button type="button" id="add_item" style="width: 100%; padding: 12px; background: #1c1917; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
                            Tambah ke Daftar ↓
                        </button>
                    </div>
                </div>

                <!-- Kolom Kanan: Daftar Barang (Multiple Items) -->
                <div style="background: white; border-radius: 16px; border: 1px solid #e7e5e4; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); align-self: stretch; display: flex; flex-direction: column;">
                    <div style="padding: 16px 24px; border-bottom: 1px solid #f5f5f4; background: #fafaf9; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-size: 15px; font-weight: 700; color: #1c1917; margin: 0;">3. Daftar Barang Pengajuan</h3>
                        <span id="item_count_badge" style="background: #92400e; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 700;">0 Item</span>
                    </div>
                    
                    <div style="flex-grow: 1; overflow-y: auto; min-height: 300px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid #f5f5f4;">
                                    <th style="padding: 12px 24px; text-align: left; font-size: 11px; color: #78716c; text-transform: uppercase;">Produk</th>
                                    <th style="padding: 12px 24px; text-align: right; font-size: 11px; color: #78716c; text-transform: uppercase;">Estimasi</th>
                                    <th style="padding: 12px 24px; text-align: center; font-size: 11px; color: #78716c; text-transform: uppercase;">Qty</th>
                                    <th style="padding: 12px 24px; text-align: center; font-size: 11px; color: #78716c; text-transform: uppercase;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="items_container">
                                <tr class="empty_row">
                                    <td colspan="4" style="padding: 60px 24px; text-align: center; color: #a8a29e; font-size: 14px;">
                                        <svg style="width: 48px; height: 48px; margin-bottom: 12px; color: #e7e5e4;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        <br>Belum ada produk dipilih.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div style="padding: 20px 24px; background: #fafaf9; border-top: 1px solid #f5f5f4;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <span style="font-weight: 600; color: #78716c;">TOTAL ESTIMASI NILAI</span>
                            <span id="grand_total" style="font-size: 20px; font-weight: 800; color: #92400e;">Rp 0</span>
                        </div>
                        <button type="submit" style="width: 100%; padding: 14px; background: #92400e; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(146, 64, 14, 0.3);">
                            Kirim Pengajuan Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelector = document.getElementById('product_selector');
            const qtySelector = document.getElementById('qty_selector');
            const addItemBtn = document.getElementById('add_item');
            const itemsContainer = document.getElementById('items_container');
            const grandTotalEl = document.getElementById('grand_total');
            const itemCountBadge = document.getElementById('item_count_badge');
            const orderForm = document.getElementById('orderForm');

            let items = [];

            addItemBtn.addEventListener('click', function() {
                const productId = productSelector.value;
                const qty = parseInt(qtySelector.value);
                
                if (!productId) {
                    alert('Silakan pilih produk terlebih dahulu.');
                    return;
                }

                if (qty <= 0) {
                    alert('Jumlah minimal pengambilan adalah 1.');
                    return;
                }

                const selectedOption = productSelector.options[productSelector.selectedIndex];
                const name = selectedOption.getAttribute('data-name');
                const price = parseFloat(selectedOption.getAttribute('data-price'));

                const existingIndex = items.findIndex(i => i.product_id === productId);
                if (existingIndex > -1) {
                    items[existingIndex].qty += qty;
                    items[existingIndex].subtotal = items[existingIndex].qty * items[existingIndex].price;
                } else {
                    items.push({
                        product_id: productId,
                        name: name,
                        price: price,
                        qty: qty,
                        subtotal: qty * price
                    });
                }

                renderItems();
                productSelector.value = '';
                qtySelector.value = 1;
            });

            function renderItems() {
                if (items.length === 0) {
                    itemsContainer.innerHTML = '<tr class="empty_row"><td colspan="4" style="padding: 60px 24px; text-align: center; color: #a8a29e; font-size: 14px;"><svg style="width: 48px; height: 48px; margin-bottom: 12px; color: #e7e5e4;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg><br>Belum ada produk dipilih.</td></tr>';
                    grandTotalEl.textContent = 'Rp 0';
                    itemCountBadge.textContent = '0 Item';
                    return;
                }

                let html = '';
                let total = 0;

                items.forEach((item, index) => {
                    total += item.subtotal;
                    html += `
                        <tr style="border-bottom: 1px solid #f5f5f4;">
                            <td style="padding: 12px 24px;">
                                <div style="font-weight: 600; color: #1c1917; font-size: 13px;">${item.name}</div>
                                <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                                <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                            </td>
                            <td style="padding: 12px 24px; text-align: right; font-size: 13px; color: #78716c;">Rp ${formatMataUang(item.subtotal)}</td>
                            <td style="padding: 12px 24px; text-align: center; font-weight: 700;">${item.qty}</td>
                            <td style="padding: 12px 24px; text-align: center;">
                                <button type="button" onclick="removeItem(${index})" style="background: #fee2e2; border: none; color: #ef4444; cursor: pointer; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700;">Hapus</button>
                            </td>
                        </tr>
                    `;
                });

                itemsContainer.innerHTML = html;
                grandTotalEl.textContent = 'Rp ' + formatMataUang(total);
                itemCountBadge.textContent = items.length + ' Item';
            }

            window.removeItem = function(index) {
                items.splice(index, 1);
                renderItems();
            };

            function formatMataUang(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            }

            orderForm.addEventListener('submit', function(e) {
                if (items.length === 0) {
                    e.preventDefault();
                    alert('Silakan tambahkan minimal 1 barang ke dalam daftar sebelum mengirim.');
                }
            });
        });
    </script>
</x-layouts.user>

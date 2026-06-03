<x-layouts.admin>
    <x-slot name="title">Tambah Penjualan</x-slot>

    <div style="max-width: 900px; margin-bottom: 50px;">
        <form action="{{ route('admin.sales.store') }}" method="POST" id="form-sale">
            @csrf

            {{-- HEADER PENJUALAN --}}
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #0f172a; border-radius: 2px;"></span>
                    Informasi Penjualan
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Tanggal Transaksi *</label>
                        <input type="date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required
                               style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px;">
                        @error('sale_date') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Member (Opsional)</label>
                            <select name="customer_id" id="select-customer" onchange="handleCustomerChange(this)"
                                    style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: white;">
                                <option value="">-- Member Umum (Guest) --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Nama Pelanggan (Opsional)</label>
                            <input type="text" name="customer_name" id="input-customer-name" value="{{ old('customer_name') }}" 
                                   placeholder="Ketik nama jika Guest..."
                                   style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: white;">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Status Pembayaran *</label>
                        <select name="payment_status" id="select-payment-status" required onchange="togglePaymentMethod()"
                                style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: white;">
                            <option value="lunas" {{ old('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas (Dibayar Penuh)</option>
                            <option value="sebagian" {{ old('payment_status') == 'sebagian' ? 'selected' : '' }}>Sebagian / DP</option>
                            <option value="belum_bayar" {{ old('payment_status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar (Hutang)</option>
                        </select>
                        @error('payment_status') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                    <div id="col-payment-method">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Metode Pembayaran *</label>
                        <select name="payment_method" required
                                style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: white;">
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai / Cash</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                        </select>
                        @error('payment_method') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                    <div id="col-paid-amount">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Jumlah Bayar / DP *</label>
                        <input type="number" name="paid_amount" id="input-paid-amount" value="{{ old('paid_amount', 0) }}" min="0" step="1"
                               oninput="updatePaidPreview(this)"
                               style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: white;">
                        <div id="paid-amount-preview" style="font-size: 12px; color: #16a34a; font-weight: 600; margin-top: 4px;">
                            Konfirmasi: Rp 0
                        </div>
                        @error('paid_amount') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Catatan (Opsional)</label>
                    <textarea name="note" rows="2" placeholder="Catatan transaksi..."
                              style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; resize: none;">{{ old('note') }}</textarea>
                </div>
            </div>

            {{-- DAFTAR ITEM PENJUALAN --}}
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 8px; height: 18px; background: #0f172a; border-radius: 2px;"></span>
                        Produk yang Dijual
                    </h3>
                    <button type="button" onclick="tambahBaris()"
                            style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 7px 14px; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer;">
                        + Tambah Produk
                    </button>
                </div>
                
                @if(session('error'))
                <div style="background:#fff1f2;border:1px solid #fecaca;color:#be123c;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;font-weight:600;">
                    {{ session('error') }}
                </div>
                @endif
                @error('items') <div style="color:#ef4444;font-size:13px;margin-bottom:10px;">{{ $message }}</div> @enderror

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left;">Produk</th>
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right; width: 150px;">Harga Satuan</th>
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left; width: 120px;">Qty (Pcs)</th>
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right; width: 150px;">Subtotal</th>
                            <th style="padding: 10px 12px; width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-items">
                        <tr class="baris-item">
                            <td style="padding: 8px 6px;">
                                <select name="items[0][product_id]" required class="select-produk" onchange="pilihProduk(this)"
                                        style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;background:white;">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        @php
                                            $stock = (float)$product->current_stock;
                                            $unit = strtolower($product->unit->name ?? 'pcs');
                                            $label = number_format($stock, 0, ',', '.') . ' ' . $unit;
                                            
                                            if (($unit === 'kg' || $unit === 'kilogram') && $stock >= 1000) {
                                                $tonVal = $stock / 1000;
                                                $tonFmt = floor($tonVal) == $tonVal 
                                                    ? number_format($tonVal, 0, ',', '.') 
                                                    : rtrim(rtrim(number_format($tonVal, 2, ',', '.'), '0'), ',');
                                                $label = $tonFmt . ' Ton / ' . number_format($stock, 0, ',', '.') . ' kg';
                                            }
                                        @endphp
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->price }}" 
                                                data-stok="{{ $product->current_stock }}">
                                            {{ $product->name }} {{ $product->variant ? '('.$product->variant.')' : '' }} (Stok: {{ $label }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="stok-error" style="color:#dc2626; font-size:11px; margin-top:4px; display:none;">Stok tidak cukup!</div>
                            </td>
                            <td style="padding: 8px 6px; text-align: right;">
                                <span class="display-harga" style="font-size:13px;color:#475569;">Rp 0</span>
                                <input type="hidden" class="input-harga" value="0">
                            </td>
                            <td style="padding: 8px 6px;">
                                <input type="number" name="items[0][qty]" required min="1" step="1" placeholder="0"
                                       class="input-qty"
                                       style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;"
                                       oninput="hitungBaris(this)">
                            </td>
                            <td style="padding: 8px 6px; text-align: right;">
                                <span class="display-subtotal" style="font-size:14px;font-weight:600;color:#1e293b;">Rp 0</span>
                                <input type="hidden" class="input-subtotal" value="0">
                            </td>
                            <td style="padding: 8px 6px; text-align: center;">
                                <button type="button" onclick="hapusBaris(this)"
                                        style="background:#fff1f2;border:1px solid #ffe4e6;color:#be123c;padding:7px 10px;border-radius:6px;font-size:12px;cursor:pointer;">✕</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 2px solid #e2e8f0; background: #f8fafc;">
                            <td colspan="3" style="padding: 14px 12px; text-align: right; font-size: 14px; font-weight: 700; color: #475569;">
                                Total Transaksi:
                            </td>
                            <td style="padding: 14px 12px; text-align: right;">
                                <span id="display-total" style="font-size: 18px; font-weight: 800; color: #16a34a;">Rp 0</span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- TOMBOL --}}
            <div style="display: flex; gap: 12px;">
                <button type="submit" id="btn-submit" style="flex:2;padding:14px;background:#0f172a;color:white;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;">
                    Simpan Transaksi
                </button>
                <a href="{{ route('admin.sales.index') }}"
                   style="flex:1;padding:14px;background:white;color:#64748b;border:1px solid #e2e8f0;border-radius:10px;font-size:15px;font-weight:600;text-decoration:none;text-align:center;display:flex;align-items:center;justify-content:center;">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const dataProducts = @json($products->keyBy('id'));
        let barisIndex = 1;

        // Helper untuk format angka Indonesia
        function formatIndo(num, decimals = 0) {
            return new Intl.NumberFormat('id-ID', { 
                minimumFractionDigits: 0, 
                maximumFractionDigits: decimals 
            }).format(num);
        }

        // Helper untuk label stok yang cerdas
        function getStockLabel(stock, unitName) {
            stock = parseFloat(stock);
            let unit = (unitName || 'pcs').toLowerCase();
            
            if ((unit === 'kg' || unit === 'kilogram') && stock >= 1000) {
                return `${formatIndo(stock/1000, 2)} Ton / ${formatIndo(stock)} kg`;
            }
            return `${formatIndo(stock)} ${unit}`;
        }

        function formatMataUang(angka) {
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(angka);
        }

        function tambahBaris() {
            const tbody = document.getElementById('tbody-items');
            const idx   = barisIndex++;
            const opts  = Object.values(dataProducts).map(p => {
                const unitName = p.unit ? p.unit.name : 'pcs';
                const label = getStockLabel(p.current_stock, unitName);
                return `<option value="${p.id}" data-price="${p.price}" data-stok="${p.current_stock}">
                    ${p.name} ${p.variant ? '('+p.variant+')' : ''} (Stok: ${label})
                </option>`;
            }).join('');

            const tr = document.createElement('tr');
            tr.className = 'baris-item';
            tr.innerHTML = `
                <td style="padding:8px 6px;">
                    <select name="items[${idx}][product_id]" required class="select-produk" onchange="pilihProduk(this)"
                            style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;background:white;">
                        <option value="">-- Pilih Produk --</option>${opts}
                    </select>
                    <div class="stok-error" style="color:#dc2626; font-size:11px; margin-top:4px; display:none;">Stok tidak cukup!</div>
                </td>
                <td style="padding:8px 6px;text-align:right;">
                    <span class="display-harga" style="font-size:13px;color:#475569;">Rp 0</span>
                    <input type="hidden" class="input-harga" value="0">
                </td>
                <td style="padding:8px 6px;">
                    <input type="number" name="items[${idx}][qty]" required min="1" step="1" placeholder="0"
                           class="input-qty"
                           style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;"
                           oninput="hitungBaris(this)">
                </td>
                <td style="padding:8px 6px;text-align:right;">
                    <span class="display-subtotal" style="font-size:14px;font-weight:600;color:#1e293b;">Rp 0</span>
                    <input type="hidden" class="input-subtotal" value="0">
                </td>
                <td style="padding:8px 6px;text-align:center;">
                    <button type="button" onclick="hapusBaris(this)"
                            style="background:#fff1f2;border:1px solid #ffe4e6;color:#be123c;padding:7px 10px;border-radius:6px;font-size:12px;cursor:pointer;">✕</button>
                </td>`;
            tbody.appendChild(tr);
        }

        function hapusBaris(btn) {
            if (document.querySelectorAll('.baris-item').length <= 1) {
                alert('Minimal 1 produk harus diisi.');
                return;
            }
            btn.closest('tr').remove();
            hitungTotal();
        }

        function pilihProduk(select) {
            const tr = select.closest('tr');
            const option = select.options[select.selectedIndex];
            
            if (option && option.value) {
                const price = parseFloat(option.getAttribute('data-price')) || 0;
                tr.querySelector('.input-harga').value = price;
                tr.querySelector('.display-harga').textContent = formatMataUang(price);
            } else {
                tr.querySelector('.input-harga').value = 0;
                tr.querySelector('.display-harga').textContent = 'Rp 0';
            }
            
            hitungBaris(select);
        }

        function hitungBaris(el) {
            const tr    = el.closest('tr');
            const qtyInput = tr.querySelector('.input-qty');
            const qty   = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(tr.querySelector('.input-harga').value) || 0;
            const subtotal = qty * price;
            
            tr.querySelector('.input-subtotal').value = subtotal;
            tr.querySelector('.display-subtotal').textContent = formatMataUang(subtotal);
            
            // Validasi stok
            const select = tr.querySelector('.select-produk');
            const option = select.options[select.selectedIndex];
            const errorLabel = tr.querySelector('.stok-error');
            
            if (option && option.value) {
                const stokTersedia = parseFloat(option.getAttribute('data-stok')) || 0;
                if (qty > stokTersedia) {
                    errorLabel.style.display = 'block';
                    qtyInput.style.borderColor = '#dc2626';
                    qtyInput.style.backgroundColor = '#fef2f2';
                } else {
                    errorLabel.style.display = 'none';
                    qtyInput.style.borderColor = '#cbd5e1';
                    qtyInput.style.backgroundColor = 'white';
                }
            }
            
            hitungTotal();
        }

        function handleCustomerChange(select) {
            const opt = select.options[select.selectedIndex];
            const nameInput = document.getElementById('input-customer-name');
            
            if (opt.value) {
                nameInput.value = opt.dataset.name;
                nameInput.readOnly = true;
                nameInput.style.background = '#f1f5f9';
            } else {
                nameInput.value = '';
                nameInput.readOnly = false;
                nameInput.style.background = 'white';
            }
        }

        function updatePaidPreview(input) {
            const preview = document.getElementById('paid-amount-preview');
            const val = parseFloat(input.value) || 0;
            preview.textContent = 'Konfirmasi: ' + formatMataUang(val);
        }

        function togglePaymentMethod() {
            const status = document.getElementById('select-payment-status').value;
            const colMethod = document.getElementById('col-payment-method');
            const colPaid = document.getElementById('col-paid-amount');
            const inputPaid = document.getElementById('input-paid-amount');
            
            if (status === 'belum_bayar') {
                colMethod.style.display = 'none';
                colPaid.style.display = 'none';
                inputPaid.value = 0;
            } else {
                colMethod.style.display = 'block';
                colPaid.style.display = 'block';
                
                if (status === 'lunas') {
                    const totalRaw = document.getElementById('display-total').textContent;
                    const totalNum = totalRaw.replace(/[^0-9]/g, '');
                    inputPaid.value = totalNum;
                    inputPaid.readOnly = true;
                    inputPaid.style.background = '#f1f5f9';
                } else {
                    inputPaid.readOnly = false;
                    inputPaid.style.background = 'white';
                }
            }
            updatePaidPreview(inputPaid);
        }

        function hitungTotal() {
            let total = 0;
            let adaErrorStok = false;
            
            document.querySelectorAll('.baris-item').forEach(tr => {
                total += parseFloat(tr.querySelector('.input-subtotal').value) || 0;
                if (tr.querySelector('.stok-error').style.display === 'block') {
                    adaErrorStok = true;
                }
            });
            
            document.getElementById('display-total').textContent = formatMataUang(total);
            
            const status = document.getElementById('select-payment-status').value;
            const inputPaid = document.getElementById('input-paid-amount');
            if (status === 'lunas') {
                inputPaid.value = total;
            }
            updatePaidPreview(inputPaid);
            
            const btnSubmit = document.getElementById('btn-submit');
            btnSubmit.disabled = adaErrorStok;
            btnSubmit.style.opacity = adaErrorStok ? '0.5' : '1';
            btnSubmit.style.cursor = adaErrorStok ? 'not-allowed' : 'pointer';
        }

        // Jalankan saat halaman dimuat untuk memastikan baris pertama sinkron
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.select-produk').forEach(select => {
                if (select.value) pilihProduk(select);
            });

            // Sinkronkan nama pelanggan jika sudah ada customer_id
            const selectCustomer = document.getElementById('select-customer');
            if (selectCustomer.value) handleCustomerChange(selectCustomer);

            // Sinkronkan visibilitas metode pembayaran
            togglePaymentMethod();
        });
    </script>
</x-layouts.admin>

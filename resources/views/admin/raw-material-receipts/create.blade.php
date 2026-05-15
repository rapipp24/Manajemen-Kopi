<x-layouts.admin>
    <x-slot name="title">Tambah Penerimaan</x-slot>

    <style>
        .card { background: white; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 24px; }
        .card-body { padding: 24px; }
        .form-label { display: block; font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 12px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; color: var(--text-main); transition: all 0.2s; }
        .form-control:focus { outline: none; border-color: var(--brown-400); box-shadow: 0 0 0 3px rgba(146, 64, 14, 0.1); }
        
        /* Remove number spin buttons */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        
        /* Input Group for Currency */
        .input-group { display: flex; align-items: stretch; }
        .input-group-text { 
            background: #f8fafc; 
            border: 1px solid var(--border); 
            padding: 10px 12px; 
            font-size: 13px; 
            font-weight: 700; 
            color: var(--text-muted);
            display: flex;
            align-items: center;
        }
        .input-group .form-control:first-child { border-top-right-radius: 0; border-bottom-right-radius: 0; }
        .input-group .form-control:last-child { border-top-left-radius: 0; border-bottom-left-radius: 0; }
        .input-group .input-group-text:first-child { border-top-right-radius: 0; border-bottom-right-radius: 0; }
        .input-group .input-group-text:last-child { border-top-left-radius: 0; border-bottom-left-radius: 0; }

        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .item-table th { text-align: left; padding: 12px; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        .item-table td { padding: 12px; border-bottom: 1px solid #f8fafc; }
        
        .btn { padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; border: none; transition: all 0.2s; }
        .btn-add { background: #f0fdf4; color: #166534; border: 1px dashed #bbf7d0; width: 100%; justify-content: center; margin-top: 10px; }
        .btn-add:hover { background: #dcfce7; }
        .btn-remove { color: #dc2626; padding: 5px; background: none; }
        .btn-remove:hover { color: #991b1b; }
        .btn-save { background: var(--brown-400); color: white; }
        .btn-save:hover { background: var(--brown-500); transform: translateY(-1px); }
        
        .total-display { font-size: 18px; font-weight: 800; color: var(--text-main); }
        
        /* Table specific adjustments to prevent clipping */
        .item-table td .form-control { padding-left: 8px; padding-right: 8px; font-size: 13px; }
        .item-table td .input-group-text { padding: 8px; font-size: 11px; }
    </style>


    @if(session('error'))
        <div style="background: #fef2f2; color: #991b1b; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid #fee2e2;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.raw-material-receipts.store') }}" method="POST" id="receiptForm">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            
            <!-- Left Side: Items -->
            <div>
                <div class="card">
                    <div class="card-body">
                        <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 20px; color: var(--brown-400);">Detail Item Bahan</h3>
                        
                        <table class="item-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Bahan Baku</th>
                                    <th style="width: 20%;">Jumlah (Qty)</th>
                                    <th style="width: 25%;">Harga / Satuan</th>
                                    <th style="width: 25%;">Subtotal</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Initial Row -->
                                <tr class="item-row">
                                    <td style="vertical-align: top;">
                                        <select name="items[0][raw_material_id]" class="form-control material-select" required onchange="updateUnit(this)">
                                            <option value="">Pilih Bahan</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->id }}" data-unit="{{ $material->unit->code }}">{{ $material->name }} ({{ $material->unit->code }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <div class="input-group">
                                            <input type="text" name="items[0][qty]" class="form-control qty-input" value="0" required 
                                                   style="border-right: none; text-align: right;" oninput="formatQtyInput(this); calculateRow(this)">
                                            <span class="input-group-text unit-label" style="background: white; color: #94a3b8; border-left: 1px solid #e2e8f0; min-width: 45px; justify-content: center;">-</span>
                                        </div>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <div class="input-group">
                                            <span class="input-group-text" style="background: #f8fafc; border-right: none;">Rp</span>
                                            <input type="text" name="items[0][unit_price]" class="form-control price-input" value="0" required 
                                                   style="border-left: 1px solid #e2e8f0;" oninput="formatPriceInput(this); calculateRow(this)">
                                        </div>
                                        <div class="price-unit-label" style="font-size: 10px; color: #94a3b8; margin-top: 4px; font-weight: 600; text-align: right;">/ -</div>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <input type="text" class="form-control subtotal-input" value="Rp 0" readonly style="background: #f8fafc; font-weight: 700;">
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-add" onclick="addRow()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Tambah Baris Bahan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Side: Header Info -->
            <div>
                <div class="card">
                    <div class="card-body">
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <label class="form-label" style="margin-bottom: 0;">Supplier</label>
                                <a href="{{ route('admin.suppliers.create') }}" target="_blank" style="font-size: 11px; color: var(--brown-400); font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 12px; height: 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    Supplier Baru
                                </a>
                            </div>
                            <select name="supplier_id" class="form-control" required>
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label class="form-label">Tanggal Terima</label>
                            <input type="date" name="receipt_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label class="form-label">No. Nota / Surat Jalan</label>
                            <input type="text" name="reference_number" class="form-control" 
                                   maxlength="50" 
                                   pattern="^[a-zA-Z0-9\-\/\s]+$"
                                   title="Hanya boleh Huruf, Angka, Spasi, Strip (-), atau Garis Miring (/)"
                                   placeholder="Contoh: INV-123/2024">
                        </div>

                        <div style="margin-bottom: 24px;">
                            <label class="form-label">Catatan</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Opsional..."></textarea>
                        </div>

                        <div style="padding: 20px; background: #fcfaf8; border-radius: 10px; border: 1px solid #f1e6d8; margin-bottom: 24px;">
                            <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase;">Total Transaksi</div>
                            <div class="total-display" id="grandTotal">Rp 0</div>
                        </div>

                        <button type="submit" class="btn btn-save" style="width: 100%; justify-content: center;">
                            Simpan Penerimaan
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        let rowCount = 1;

        function addRow() {
            const tbody = document.getElementById('itemsBody');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.innerHTML = `
                <td style="vertical-align: top;">
                    <select name="items[${rowCount}][raw_material_id]" class="form-control material-select" required onchange="updateUnit(this)">
                        <option value="">Pilih Bahan</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" data-unit="{{ $material->unit->code }}">{{ $material->name }} ({{ $material->unit->code }})</option>
                        @endforeach
                    </select>
                </td>
                <td style="vertical-align: top;">
                    <div class="input-group">
                        <input type="text" name="items[${rowCount}][qty]" class="form-control qty-input" value="0" required 
                               style="border-right: none; text-align: right;" oninput="formatQtyInput(this); calculateRow(this)">
                        <span class="input-group-text unit-label" style="background: white; color: #94a3b8; border-left: 1px solid #e2e8f0; min-width: 45px; justify-content: center;">-</span>
                    </div>
                </td>
                <td style="vertical-align: top;">
                    <div class="input-group">
                        <span class="input-group-text" style="background: #f8fafc; border-right: none;">Rp</span>
                        <input type="text" name="items[${rowCount}][unit_price]" class="form-control price-input" value="0" required 
                               style="border-left: 1px solid #e2e8f0;" oninput="formatPriceInput(this); calculateRow(this)">
                    </div>
                    <div class="price-unit-label" style="font-size: 10px; color: #94a3b8; margin-top: 4px; font-weight: 600; text-align: right;">/ -</div>
                </td>
                <td style="vertical-align: top;">
                    <input type="text" class="form-control subtotal-input" value="Rp 0" readonly style="background: #f8fafc; font-weight: 700;">
                </td>
                <td style="text-align: center;">
                    <button type="button" class="btn-remove" onclick="removeRow(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);
            rowCount++;
        }

        function removeRow(btn) {
            btn.closest('tr').remove();
            calculateGrandTotal();
        }

        // --- Helper Formatting ---
        function formatQtyInput(el) {
            let val = el.value.replace(/[^0-9,]/g, ""); // Allow only numbers and comma
            let parts = val.split(",");
            
            // Format integer part with dots
            parts[0] = parts[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            
            if (parts.length > 2) {
                el.value = parts[0] + "," + parts[1]; // Limit to 1 comma
            } else {
                el.value = parts.join(",");
            }
        }

        function formatPriceInput(el) {
            let val = el.value.replace(/\D/g, "");
            el.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function parseQty(str) {
            return parseFloat(str.replace(/\./g, "").replace(/,/g, ".")) || 0;
        }

        function parsePrice(str) {
            return parseFloat(str.replace(/\./g, "")) || 0;
        }

        function formatIDR(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // --- Logic ---
        function updateUnit(select) {
            const row = select.closest('tr');
            const opt = select.options[select.selectedIndex];
            const unit = opt.dataset.unit || '-';
            row.querySelector('.unit-label').innerText = unit;
            row.querySelector('.price-unit-label').innerText = '/ ' + unit;
        }

        function calculateRow(el) {
            const row = el.closest('tr');
            const qty = parseQty(row.querySelector('.qty-input').value);
            const price = parsePrice(row.querySelector('.price-input').value);
            const subtotal = qty * price;
            row.querySelector('.subtotal-input').value = formatIDR(subtotal);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseQty(row.querySelector('.qty-input').value);
                const price = parsePrice(row.querySelector('.price-input').value);
                grandTotal += qty * price;
            });
            document.getElementById('grandTotal').innerText = formatIDR(grandTotal);
        }

        // Clean values before submit
        document.getElementById('receiptForm').addEventListener('submit', function(e) {
            document.querySelectorAll('.qty-input').forEach(input => {
                // Convert back to standard number for backend
                let cleanVal = input.value.replace(/\./g, "").replace(/,/g, ".");
                input.value = cleanVal;
            });
            document.querySelectorAll('.price-input').forEach(input => {
                input.value = input.value.replace(/\./g, "");
            });
        });
    </script>
</x-layouts.admin>

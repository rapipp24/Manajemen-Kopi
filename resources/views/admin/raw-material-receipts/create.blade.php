<x-layouts.admin>
    <x-slot name="title">Tambah Penerimaan</x-slot>

    <style>
        .card { background: white; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 24px; }
        .card-body { padding: 24px; }
        .form-label { display: block; font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; color: var(--text-main); transition: all 0.2s; }
        .form-control:focus { outline: none; border-color: var(--brown-400); box-shadow: 0 0 0 3px rgba(146, 64, 14, 0.1); }
        
        /* Input Group for Currency */
        .input-group { display: flex; align-items: center; }
        .input-group-text { 
            background: #f8fafc; 
            border: 1px solid var(--border); 
            border-right: none; 
            padding: 10px 12px; 
            border-radius: 8px 0 0 8px; 
            font-size: 13px; 
            font-weight: 700; 
            color: var(--text-muted);
        }
        .input-group .form-control { border-radius: 0 8px 8px 0; }

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
    </style>

    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-main);">Catat Penerimaan Baru</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Masukkan detail bahan baku yang masuk ke gudang.</p>
    </div>

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
                                    <th style="width: 35%;">Bahan Baku</th>
                                    <th style="width: 15%;">Qty</th>
                                    <th style="width: 25%;">Harga Beli</th>
                                    <th style="width: 20%;">Subtotal</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Initial Row -->
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][raw_material_id]" class="form-control material-select" required>
                                            <option value="">Pilih Bahan</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->id }}" data-unit="{{ $material->unit->code }}">{{ $material->name }} ({{ $material->unit->code }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][qty]" class="form-control qty-input" step="0.01" min="0.01" value="0" required>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="items[0][unit_price]" class="form-control price-input" value="0" required>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal-input" value="0" readonly style="background: #f8fafc; font-weight: 700;">
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
                            <label class="form-label">Supplier</label>
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
                <td>
                    <select name="items[${rowCount}][raw_material_id]" class="form-control material-select" required>
                        <option value="">Pilih Bahan</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" data-unit="{{ $material->unit->code }}">{{ $material->name }} ({{ $material->unit->code }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][qty]" class="form-control qty-input" step="0.01" min="0.01" value="0" required>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="items[${rowCount}][unit_price]" class="form-control price-input" value="0" required>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control subtotal-input" value="0" readonly style="background: #f8fafc; font-weight: 700;">
                </td>
                <td>
                    <button type="button" class="btn-remove" onclick="removeRow(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);
            rowCount++;
            initCalculators();
        }

        function removeRow(btn) {
            btn.closest('tr').remove();
            calculateGrandTotal();
        }

        function formatNumber(number) {
            return number.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function parseNumber(string) {
            return parseFloat(string.replace(/\./g, "")) || 0;
        }

        function initCalculators() {
            document.querySelectorAll('.item-row').forEach(row => {
                const qtyInput = row.querySelector('.qty-input');
                const priceInput = row.querySelector('.price-input');
                const subtotalInput = row.querySelector('.subtotal-input');

                // Price formatting
                priceInput.addEventListener('input', function(e) {
                    this.value = formatNumber(this.value);
                    const qty = parseFloat(qtyInput.value) || 0;
                    const price = parseNumber(this.value);
                    const subtotal = qty * price;
                    subtotalInput.value = formatIDR(subtotal);
                    calculateGrandTotal();
                });

                qtyInput.addEventListener('input', function() {
                    const qty = parseFloat(this.value) || 0;
                    const price = parseNumber(priceInput.value);
                    const subtotal = qty * price;
                    subtotalInput.value = formatIDR(subtotal);
                    calculateGrandTotal();
                });
            });
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseNumber(row.querySelector('.price-input').value);
                grandTotal += qty * price;
            });
            document.getElementById('grandTotal').innerText = formatIDR(grandTotal);
        }

        function formatIDR(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Clean currency before submit
        document.getElementById('receiptForm').addEventListener('submit', function(e) {
            document.querySelectorAll('.price-input').forEach(input => {
                input.value = input.value.replace(/\./g, "");
            });
        });

        document.addEventListener('DOMContentLoaded', initCalculators);
    </script>
</x-layouts.admin>

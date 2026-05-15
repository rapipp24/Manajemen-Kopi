<x-layouts.admin>
    <x-slot name="title">Tambah Produksi</x-slot>

    <div style="max-width: 860px; margin-bottom: 50px;">
        <form action="{{ route('admin.productions.store') }}" method="POST" id="form-produksi">
            @csrf

            {{-- HEADER PRODUKSI --}}
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 15px; font-weight: 700; color: #92400e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                    Informasi Produksi
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Tanggal Produksi *</label>
                        <input type="date" name="production_date" value="{{ old('production_date', date('Y-m-d')) }}" required
                               style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px;">
                        @error('production_date') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Jenis Produksi *</label>
                        <select name="product_type" required
                                style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: white;">
                            <option value="">-- Pilih Jenis Produk --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ old('product_type') == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_type') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div style="margin-top: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Catatan (Opsional)</label>
                    <textarea name="note" rows="2" placeholder="Catatan tambahan..."
                              style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; resize: none;">{{ old('note') }}</textarea>
                </div>
            </div>

            {{-- DAFTAR BAHAN BAKU --}}
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 15px; font-weight: 700; color: #92400e; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                        Bahan Baku Digunakan
                    </h3>
                    <button type="button" onclick="tambahBaris()"
                            style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 7px 14px; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer;">
                        + Tambah Bahan
                    </button>
                </div>
                @error('items') <div style="color:#ef4444;font-size:13px;margin-bottom:10px;">{{ $message }}</div> @enderror

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left;">Bahan Baku</th>
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left; width: 220px;">Stok Tersedia</th>
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left; width: 180px;">Qty Digunakan</th>
                            <th style="padding: 10px 12px; width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-bahan">
                        <tr class="baris-bahan">
                            <td style="padding: 8px 6px;">
                                <select name="items[0][raw_material_id]" required class="select-bahan"
                                        style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;background:white;"
                                        onchange="updateInfoStok(this)">
                                    <option value="">-- Pilih Bahan --</option>
                                    @foreach($materials as $m)
                                        <option value="{{ $m->id }}" data-stock="{{ $m->current_stock }}" data-unit="{{ $m->unit->name ?? '' }}">
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding: 8px 6px;">
                                <span class="info-stok" style="font-size:13px;color:#64748b;display:block;padding:9px 10px;">-</span>
                            </td>
                            <td style="padding: 8px 6px;">
                                <div style="position: relative;">
                                    <input type="text" name="items[0][qty_used]" required placeholder="0"
                                           class="input-qty"
                                           style="width:100%;padding:9px 35px 9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:14px;text-align:right;font-weight:700;"
                                           oninput="formatQtyInput(this); hitungTotal()">
                                    <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #94a3b8; pointer-events: none; font-weight: 700;">kg</span>
                                </div>
                            </td>
                            <td style="padding: 8px 6px; text-align: center;">
                                <button type="button" onclick="hapusBaris(this)"
                                        style="background:#fff1f2;border:1px solid #ffe4e6;color:#be123c;padding:7px 10px;border-radius:6px;font-size:12px;cursor:pointer;">✕</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top:14px;padding:12px 16px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;display:flex;justify-content:flex-end;align-items:center;gap:16px;">
                    <span style="font-size:13px;font-weight:600;color:#475569;">Total Bahan Digunakan:</span>
                    <span id="total-bahan" style="font-size:16px;font-weight:700;color:#92400e;">0 kg</span>
                </div>
            </div>

            {{-- HASIL PRODUKSI --}}
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 15px; font-weight: 700; color: #92400e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                    Hasil Produksi
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#475569;margin-bottom:8px;">Total Bahan Digunakan</label>
                        <div id="display-total-bahan" style="padding:10px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;color:#64748b;font-weight:700;">0 kg</div>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#475569;margin-bottom:8px;">Total Hasil Produksi (kg) *</label>
                        <div style="position: relative;">
                            <input type="text" name="total_output" id="input-total-output" required placeholder="0"
                                   value="{{ old('total_output') }}"
                                   style="width:100%;padding:10px 35px 10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;font-weight:700;text-align:right;"
                                   oninput="formatQtyInput(this); hitungSusut()">
                            <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #94a3b8; pointer-events: none; font-weight: 700;">kg</span>
                        </div>
                        @error('total_output') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#475569;margin-bottom:8px;">Susut (Otomatis)</label>
                        <div id="display-susut" style="padding:10px 12px;background:#fff5f5;border:1px solid #fecaca;border-radius:8px;font-size:14px;font-weight:700;color:#dc2626;">0 kg</div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL --}}
            <div style="display: flex; gap: 12px;">
                <button type="submit" style="flex:2;padding:14px;background:#92400e;color:white;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;">
                    Simpan Produksi
                </button>
                <a href="{{ route('admin.productions.index') }}"
                   style="flex:1;padding:14px;background:white;color:#64748b;border:1px solid #e2e8f0;border-radius:10px;font-size:15px;font-weight:600;text-decoration:none;text-align:center;display:flex;align-items:center;justify-content:center;">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const dataBahan = @json($materials->keyBy('id'));
        let barisIndex = 1;

        // --- Formatting Helpers ---
        function formatQtyInput(el) {
            let val = el.value.replace(/[^0-9,]/g, "");
            let parts = val.split(",");
            parts[0] = parts[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            if (parts.length > 2) el.value = parts[0] + "," + parts[1];
            else el.value = parts.join(",");
        }

        function parseQty(str) {
            if (!str) return 0;
            return parseFloat(str.toString().replace(/\./g, "").replace(/,/g, ".")) || 0;
        }

        function formatIDRNumber(num) {
            return num.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
        }

        function formatVisualStock(stock, unit) {
            stock = parseFloat(stock);
            if (!unit) return stock;

            const unitLower = unit.toLowerCase();
            if (unitLower === 'kilogram' || unitLower === 'kg') {
                if (stock >= 1000) {
                    let tonVal = stock / 1000;
                    return `<strong>${formatIDRNumber(tonVal)}</strong> <span style="font-size:11px;color:#92400e;font-weight:700;">Ton</span> / <strong>${formatIDRNumber(stock)}</strong> <span style="font-size:11px;color:#64748b;">kg</span>`;
                }
                return `<strong>${formatIDRNumber(stock)}</strong> <span style="font-size:11px;color:#64748b;">kg</span>`;
            }
            return `<strong>${formatIDRNumber(stock)}</strong> <span style="font-size:11px;color:#64748b;">${unit}</span>`;
        }

        // --- Core Logic ---
        function tambahBaris() {
            const tbody = document.getElementById('tbody-bahan');
            const idx = barisIndex++;
            const options = Object.values(dataBahan).map(m =>
                `<option value="${m.id}" data-stock="${m.current_stock}" data-unit="${m.unit ? m.unit.name : ''}">${m.name}</option>`
            ).join('');

            const tr = document.createElement('tr');
            tr.className = 'baris-bahan';
            tr.innerHTML = `
                <td style="padding:8px 6px;">
                    <select name="items[${idx}][raw_material_id]" required class="select-bahan"
                            style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;background:white;"
                            onchange="updateInfoStok(this)">
                        <option value="">-- Pilih Bahan --</option>
                        ${options}
                    </select>
                </td>
                <td style="padding:8px 6px;">
                    <span class="info-stok" style="font-size:13px;color:#64748b;display:block;padding:9px 10px;">-</span>
                </td>
                <td style="padding: 8px 6px;">
                    <div style="position: relative;">
                        <input type="text" name="items[${idx}][qty_used]" required placeholder="0"
                               class="input-qty"
                               style="width:100%;padding:9px 35px 9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:14px;text-align:right;font-weight:700;"
                               oninput="formatQtyInput(this); hitungTotal()">
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #94a3b8; pointer-events: none; font-weight: 700;">kg</span>
                    </div>
                </td>
                <td style="padding:8px 6px;text-align:center;">
                    <button type="button" onclick="hapusBaris(this)"
                            style="background:#fff1f2;border:1px solid #ffe4e6;color:#be123c;padding:7px 10px;border-radius:6px;font-size:12px;cursor:pointer;">✕</button>
                </td>`;
            tbody.appendChild(tr);
        }

        function hapusBaris(btn) {
            if (document.querySelectorAll('.baris-bahan').length <= 1) {
                alert('Minimal 1 bahan harus diisi.');
                return;
            }
            btn.closest('tr').remove();
            hitungTotal();
        }

        function updateInfoStok(select) {
            const opt = select.options[select.selectedIndex];
            const span = select.closest('tr').querySelector('.info-stok');
            if (opt.value) {
                span.innerHTML = formatVisualStock(opt.dataset.stock, opt.dataset.unit);
            } else {
                span.textContent = '-';
            }
            hitungTotal();
        }

        function hitungTotal() {
            let total = 0;
            document.querySelectorAll('.input-qty').forEach(i => { total += parseQty(i.value); });
            
            let totalDisplay = formatIDRNumber(total) + ' kg';
            if (total >= 1000) {
                totalDisplay = formatIDRNumber(total/1000) + ' Ton / ' + formatIDRNumber(total) + ' kg';
            }

            document.getElementById('total-bahan').innerHTML = totalDisplay;
            document.getElementById('display-total-bahan').innerHTML = totalDisplay;
            hitungSusut();
        }

        function hitungSusut() {
            let totalBahan = 0;
            document.querySelectorAll('.input-qty').forEach(i => { totalBahan += parseQty(i.value); });
            
            const totalHasil = parseQty(document.getElementById('input-total-output').value);
            const susut = totalBahan - totalHasil;
            const el = document.getElementById('display-susut');
            
            el.textContent = formatIDRNumber(susut) + ' kg';
            el.style.color = susut > 0 ? '#dc2626' : '#166534';
        }

        // --- Prevent accidental changes ---
        document.getElementById('form-produksi').addEventListener('submit', function(e) {
            // Clean dots before submit for backend
            document.querySelectorAll('.input-qty').forEach(input => {
                input.value = parseQty(input.value);
            });
            const output = document.getElementById('input-total-output');
            output.value = parseQty(output.value);
        });

        // Disable scroll on number inputs globally (if any left)
        window.addEventListener('wheel', function(e) {
            if (document.activeElement.type === 'number') {
                document.activeElement.blur();
            }
        });
    </script>
</x-layouts.admin>

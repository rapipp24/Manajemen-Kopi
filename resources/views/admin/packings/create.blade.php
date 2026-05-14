<x-layouts.admin>
    <x-slot name="title">Tambah Packing</x-slot>

    <div style="max-width: 860px; margin-bottom: 50px;">
        <form action="{{ route('admin.packings.store') }}" method="POST" id="form-packing">
            @csrf

            {{-- HEADER PACKING --}}
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 15px; font-weight: 700; color: #92400e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                    Informasi Packing
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Tanggal Packing *</label>
                        <input type="date" name="packing_date" value="{{ old('packing_date', date('Y-m-d')) }}" required
                               style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px;">
                        @error('packing_date') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Jenis Produksi (Sumber Curah) *</label>
                        <select name="curah_type" id="curah_type" required onchange="updateCurahStock()"
                                style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: white;">
                            <option value="">-- Pilih Jenis Produksi --</option>
                            @foreach($curahStocks as $type => $stock)
                                <option value="{{ $type }}" {{ old('curah_type') == $type ? 'selected' : '' }}>
                                    {{ $type }} (Tersedia: {{ number_format($stock, 2) }} kg)
                                </option>
                            @endforeach
                        </select>
                        @error('curah_type') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Catatan (Opsional)</label>
                    <input type="text" name="note" value="{{ old('note') }}" placeholder="Catatan tambahan..."
                           style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px;">
                </div>
            </div>

            {{-- INFO STOK CURAH TERPILIH --}}
            <div id="info-stok-curah" style="display: none; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; align-items: center; gap: 12px;">
                <svg style="width: 20px; height: 20px; color: #16a34a; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <span style="font-size: 13px; font-weight: 600; color: #166534;">Stok Curah <span id="label-curah-type"></span> Tersedia:</span>
                    <span id="label-curah-stock" style="font-size: 16px; font-weight: 800; color: #15803d; margin-left: 8px;">0.00 kg</span>
                </div>
            </div>

            {{-- DAFTAR PRODUK --}}
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 15px; font-weight: 700; color: #92400e; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                        Produk yang Dipacking
                    </h3>
                    <button type="button" onclick="tambahBaris()"
                            style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 7px 14px; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer;">
                        + Tambah Produk
                    </button>
                </div>
                @error('items') <div style="color:#ef4444;font-size:13px;margin-bottom:10px;">{{ $message }}</div> @enderror

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left;">Produk</th>
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left; width: 130px;">Jumlah Kemasan</th>
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left; width: 150px;">Berat/Kemasan (gr)</th>
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left; width: 120px;">Total Berat</th>
                            <th style="padding: 10px 12px; width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-items">
                        <tr class="baris-item">
                            <td style="padding: 8px 6px;">
                                <select name="items[0][product_id]" required class="select-produk"
                                        style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;background:white;">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                                data-weight="{{ $product->weight }}"
                                                data-stok="{{ $product->current_stock }}">
                                            {{ $product->name }}
                                            @if($product->variant) ({{ $product->variant }}) @endif
                                            — {{ $product->weight }}gr
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding: 8px 6px;">
                                <input type="number" name="items[0][qty_pack]" required min="1" step="1" placeholder="0"
                                       class="input-qty"
                                       style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;"
                                       oninput="hitungBaris(this); hitungTotalCurah()">
                            </td>
                            <td style="padding: 8px 6px;">
                                <input type="number" name="items[0][weight_per_pack]" required min="0.001" step="0.001" placeholder="0"
                                       class="input-berat"
                                       style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;"
                                       oninput="hitungBaris(this); hitungTotalCurah()">
                            </td>
                            <td style="padding: 8px 6px;">
                                <span class="display-total-berat" style="font-size:13px;color:#64748b;display:block;padding:9px 10px;background:#f8fafc;border-radius:7px;border:1px solid #e2e8f0;">-</span>
                            </td>
                            <td style="padding: 8px 6px; text-align: center;">
                                <button type="button" onclick="hapusBaris(this)"
                                        style="background:#fff1f2;border:1px solid #ffe4e6;color:#be123c;padding:7px 10px;border-radius:6px;font-size:12px;cursor:pointer;">✕</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Ringkasan penggunaan curah --}}
                <div style="margin-top:14px;padding:14px 16px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:13px;font-weight:600;color:#475569;">Total Curah yang Dipakai:</span>
                        <span id="total-curah-pakai" style="font-size:16px;font-weight:700;color:#92400e;">0.000 kg</span>
                    </div>
                    <div id="warning-curah" style="display:none;margin-top:8px;color:#dc2626;font-size:12px;font-weight:600;">
                        ⚠ Melebihi stok curah tersedia!
                    </div>
                </div>
            </div>

            {{-- TOMBOL --}}
            <div style="display: flex; gap: 12px;">
                <button type="submit" id="btn-submit" style="flex:2;padding:14px;background:#92400e;color:white;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;">
                    Simpan Packing
                </button>
                <a href="{{ route('admin.packings.index') }}"
                   style="flex:1;padding:14px;background:white;color:#64748b;border:1px solid #e2e8f0;border-radius:10px;font-size:15px;font-weight:600;text-decoration:none;text-align:center;display:flex;align-items:center;justify-content:center;">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const curahStocks = @json($curahStocks);
        const dataProducts = @json($products->keyBy('id'));
        let barisIndex = 1;
        let currentCurahStock = 0;

        function updateCurahStock() {
            const type = document.getElementById('curah_type').value;
            const infoBox = document.getElementById('info-stok-curah');
            const labelType = document.getElementById('label-curah-type');
            const labelStock = document.getElementById('label-curah-stock');

            if (type && curahStocks[type] !== undefined) {
                currentCurahStock = parseFloat(curahStocks[type]);
                labelType.textContent = `"${type}"`;
                labelStock.textContent = currentCurahStock.toFixed(2) + ' kg';
                infoBox.style.display = 'flex';
            } else {
                currentCurahStock = 0;
                infoBox.style.display = 'none';
            }
            hitungTotalCurah();
        }

        function tambahBaris() {
            const tbody = document.getElementById('tbody-items');
            const idx   = barisIndex++;
            const opts  = Object.values(dataProducts).map(p =>
                `<option value="${p.id}" data-weight="${p.weight}" data-stok="${p.current_stock}">
                    ${p.name}${p.variant ? ' (' + p.variant + ')' : ''} — ${p.weight}gr
                </option>`
            ).join('');

            const tr = document.createElement('tr');
            tr.className = 'baris-item';
            tr.innerHTML = `
                <td style="padding:8px 6px;">
                    <select name="items[${idx}][product_id]" required class="select-produk"
                            style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;background:white;">
                        <option value="">-- Pilih Produk --</option>${opts}
                    </select>
                </td>
                <td style="padding:8px 6px;">
                    <input type="number" name="items[${idx}][qty_pack]" required min="1" step="1" placeholder="0"
                           class="input-qty"
                           style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;"
                           oninput="hitungBaris(this); hitungTotalCurah()">
                </td>
                <td style="padding:8px 6px;">
                    <input type="number" name="items[${idx}][weight_per_pack]" required min="0.001" step="0.001" placeholder="0"
                           class="input-berat"
                           style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;"
                           oninput="hitungBaris(this); hitungTotalCurah()">
                </td>
                <td style="padding:8px 6px;">
                    <span class="display-total-berat" style="font-size:13px;color:#64748b;display:block;padding:9px 10px;background:#f8fafc;border-radius:7px;border:1px solid #e2e8f0;">-</span>
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
            hitungTotalCurah();
        }

        function hitungBaris(el) {
            const tr    = el.closest('tr');
            const qty   = parseFloat(tr.querySelector('.input-qty').value) || 0;
            const berat = parseFloat(tr.querySelector('.input-berat').value) || 0;
            const total = qty * berat; // gram
            const span  = tr.querySelector('.display-total-berat');
            span.textContent = total > 0 ? total.toFixed(0) + ' gr (' + (total / 1000).toFixed(3) + ' kg)' : '-';
        }

        function hitungTotalCurah() {
            let totalKg = 0;
            document.querySelectorAll('.baris-item').forEach(tr => {
                const qty   = parseFloat(tr.querySelector('.input-qty').value) || 0;
                const berat = parseFloat(tr.querySelector('.input-berat').value) || 0;
                totalKg += (qty * berat) / 1000;
            });
            document.getElementById('total-curah-pakai').textContent = totalKg.toFixed(3) + ' kg';
            
            const warning = document.getElementById('warning-curah');
            const btnSubmit = document.getElementById('btn-submit');
            
            if (totalKg > currentCurahStock) {
                warning.style.display = 'block';
                document.getElementById('total-curah-pakai').style.color = '#dc2626';
                if (!document.getElementById('curah_type').value) {
                     warning.textContent = '⚠ Pilih jenis produksi terlebih dahulu!';
                } else {
                     warning.textContent = `⚠ Melebihi stok curah tersedia (${currentCurahStock.toFixed(2)} kg)!`;
                }
            } else {
                warning.style.display = 'none';
                document.getElementById('total-curah-pakai').style.color = '#92400e';
            }
        }
        
        // Initialize on load if old value exists
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('curah_type').value) {
                updateCurahStock();
            }
        });
    </script>
</x-layouts.admin>

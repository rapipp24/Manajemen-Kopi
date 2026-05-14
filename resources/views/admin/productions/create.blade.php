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
                        <input type="text" name="product_type" value="{{ old('product_type') }}" required
                               placeholder="Contoh: Kopi Bubuk Robusta"
                               style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px;">
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
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left; width: 150px;">Stok Tersedia</th>
                            <th style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left; width: 150px;">Qty Digunakan</th>
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
                                <input type="number" name="items[0][qty_used]" required min="0.01" step="0.01" placeholder="0"
                                       class="input-qty"
                                       style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;"
                                       oninput="hitungTotal()">
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
                    <span id="total-bahan" style="font-size:16px;font-weight:700;color:#92400e;">0.00 kg</span>
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
                        <div id="display-total-bahan" style="padding:10px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;color:#64748b;">0.00 kg</div>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#475569;margin-bottom:8px;">Total Hasil Produksi (kg) *</label>
                        <input type="number" name="total_output" id="input-total-output" required min="0.01" step="0.01" placeholder="0"
                               value="{{ old('total_output') }}"
                               style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;"
                               oninput="hitungSusut()">
                        @error('total_output') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#475569;margin-bottom:8px;">Susut (Otomatis)</label>
                        <div id="display-susut" style="padding:10px 12px;background:#fff5f5;border:1px solid #fecaca;border-radius:8px;font-size:14px;font-weight:700;color:#dc2626;">0.00 kg</div>
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
                <td style="padding:8px 6px;">
                    <input type="number" name="items[${idx}][qty_used]" required min="0.01" step="0.01" placeholder="0"
                           class="input-qty"
                           style="width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:7px;font-size:13px;"
                           oninput="hitungTotal()">
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
            span.textContent = opt.value
                ? `${parseFloat(opt.dataset.stock).toFixed(2)} ${opt.dataset.unit}`
                : '-';
            hitungTotal();
        }

        function hitungTotal() {
            let total = 0;
            document.querySelectorAll('.input-qty').forEach(i => { total += parseFloat(i.value) || 0; });
            document.getElementById('total-bahan').textContent = total.toFixed(2) + ' kg';
            document.getElementById('display-total-bahan').textContent = total.toFixed(2) + ' kg';
            hitungSusut();
        }

        function hitungSusut() {
            const totalBahan = parseFloat(document.getElementById('total-bahan').textContent) || 0;
            const totalHasil = parseFloat(document.getElementById('input-total-output').value) || 0;
            const susut = totalBahan - totalHasil;
            const el = document.getElementById('display-susut');
            el.textContent = susut.toFixed(2) + ' kg';
            el.style.color = susut > 0 ? '#dc2626' : '#166534';
        }
    </script>
</x-layouts.admin>

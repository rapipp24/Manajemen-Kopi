<x-layouts.admin>
    <x-slot name="title">Tambah Paket Baru</x-slot>

    <div style="max-width: 700px; margin-bottom: 50px;">
        <form action="{{ route('admin.packages.store') }}" method="POST">
            @csrf
            
            <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 24px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                    Informasi Paket
                </h3>

                @if($errors->has('error'))
                    <div style="background: #fff5f5; border: 1px solid #fecaca; color: #be123c; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
                        {{ $errors->first('error') }}
                    </div>
                @endif

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Kode Paket <span style="font-weight: 400; color: var(--text-muted);">(Kosongkan untuk otomatis)</span></label>
                    <input type="text" name="code" value="{{ old('code', $nextCode) }}" 
                           placeholder="Contoh: {{ $nextCode }}"
                           style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('code') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    @error('code') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Nama Paket</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           placeholder="Contoh: Pak 1kg Isi 4, Pak Campur 250gr + 500gr"
                           style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    @error('name') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Harga Jual Paket (Rp)</label>
                    <input type="text" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" required
                           placeholder="95.000"
                           style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('selling_price') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    @error('selling_price') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Deskripsi / Catatan <span style="font-weight: 400; color: var(--text-muted);">(Opsional)</span></label>
                    <textarea name="description" rows="3"
                               placeholder="Keterangan paket..."
                               style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('description') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; resize: vertical; outline: none; transition: border-color 0.15s;"
                               onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">{{ old('description') }}</textarea>
                    @error('description') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                           style="width: 16px; height: 16px; accent-color: var(--brown-500); cursor: pointer;">
                    <label for="is_active" style="font-size: 13.5px; font-weight: 600; color: var(--text-main); cursor: pointer; user-select: none;">Aktifkan Paket Langsung</label>
                </div>

                <!-- Komponen Produk -->
                <div style="border-top: 1px dashed var(--border); padding-top: 20px; margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h4 style="font-size: 14px; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 6px;">
                            <span style="width: 3px; height: 12px; background: var(--brown-400); border-radius: 1.5px;"></span>
                            Komponen Produk
                        </h4>
                        <button type="button" id="btn-add-item" 
                                style="background: var(--brown-500); color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                                onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
                            + Tambah Produk
                        </button>
                    </div>

                    <div id="components-container" style="display: flex; flex-direction: column; gap: 12px;">
                        <!-- JS Appended rows will appear here -->
                    </div>

                    @error('items')
                        <span style="color: #ef4444; font-size: 12.5px; font-weight: 600; margin-top: 8px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" 
                        style="flex: 2; padding: 12px; background: var(--brown-500); color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
                        onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
                    Simpan Paket
                </button>
                <a href="{{ route('admin.packages.index') }}" 
                   style="flex: 1; padding: 12px; background: var(--cream-100); color: var(--text-mid); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; text-align: center; transition: all 0.2s;"
                   onmouseover="this.style.background='var(--cream-200)'" onmouseout="this.style.background='var(--cream-100)'">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Scripting for Dynamic Components -->
    <script>
        const products = @json($products);
        let rowIndex = 0;

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
        }

        function addRow(productId = '', qty = '') {
            const container = document.getElementById('components-container');
            
            const div = document.createElement('div');
            div.className = 'component-row';
            div.style.display = 'flex';
            div.style.gap = '12px';
            div.style.alignItems = 'center';
            div.style.background = '#faf8f6';
            div.style.padding = '10px 14px';
            div.style.borderRadius = '10px';
            div.style.border = '1px solid var(--border)';

            let optionsHtml = '<option value="">-- Pilih Produk --</option>';
            products.forEach(p => {
                const selected = p.id == productId ? 'selected' : '';
                optionsHtml += `<option value="${p.id}" ${selected}>${p.name} (${p.variant || '-'}) - Rp ${formatRupiah(p.price)}</option>`;
            });

            div.innerHTML = `
                <div style="flex: 2;">
                    <select name="items[${rowIndex}][product_id]" required 
                            style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 13.5px; outline: none; background: white; border-color: var(--border);">
                        ${optionsHtml}
                    </select>
                </div>
                <div style="flex: 1; display: flex; align-items: center; gap: 8px;">
                    <input type="number" name="items[${rowIndex}][qty]" value="${qty}" required min="0.01" step="any" placeholder="Qty" 
                           style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 13.5px; outline: none; border-color: var(--border);">
                </div>
                <div>
                    <button type="button" class="btn-remove-row" 
                            style="background: none; border: none; color: #ef4444; font-size: 12.5px; font-weight: 700; cursor: pointer; padding: 4px 8px;">
                        Hapus
                    </button>
                </div>
            `;

            container.appendChild(div);

            // Bind remove event
            div.querySelector('.btn-remove-row').addEventListener('click', function() {
                div.remove();
            });

            rowIndex++;
        }

        document.getElementById('btn-add-item').addEventListener('click', () => addRow());

        // Reload old input rows if validation fails
        @if(old('items'))
            @foreach(old('items') as $item)
                addRow("{{ $item['product_id'] ?? '' }}", "{{ $item['qty'] ?? '' }}");
            @endforeach
        @else
            // Add default first row
            addRow('', '');
        @endif

        // Live Rupiah Formatter
        function formatRupiahInput(value) {
            let number_string = value.replace(/[^,\d]/g, '').toString();
            let split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        const priceInput = document.getElementById('selling_price');
        if (priceInput) {
            priceInput.addEventListener('input', function(e) {
                e.target.value = formatRupiahInput(e.target.value);
            });
            // Format on load
            if (priceInput.value) {
                priceInput.value = formatRupiahInput(priceInput.value);
            }
        }
    </script>
</x-layouts.admin>

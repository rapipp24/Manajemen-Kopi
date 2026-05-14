<x-layouts.admin>
    <x-slot name="title">Edit Produk</x-slot>

    <div style="max-width: 800px; margin-bottom: 50px;">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- SECTION 1: INFORMASI DASAR -->
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 16px; font-weight: 700; color: #92400e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                    Informasi Dasar Produk
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 2.5fr; gap: 20px; margin-bottom: 0;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Kode Produk</label>
                        <input type="text" name="code" value="{{ $product->code }}" readonly 
                               style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; background-color: #f8fafc; color: #64748b; cursor: not-allowed;">
                        <small style="color: #94a3b8; font-size: 11px; margin-top: 6px; display: block;">Otomatis dari sistem.</small>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Nama Produk / Menu</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required minlength="3" maxlength="50" 
                               style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px; outline: none;">
                        @error('name') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 2: SPESIFIKASI & UKURAN -->
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 16px; font-weight: 700; color: #92400e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                    Spesifikasi & Ukuran
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Jenis Produk</label>
                        <select name="product_category_id" required 
                                style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('product_category_id') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px; background-color: white;">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('product_category_id') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Berat Bersih</label>
                        <div style="position: relative;">
                            <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" required
                                   style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('weight') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                            <span style="position: absolute; right: 12px; top: 12px; color: #94a3b8; font-size: 13px;">gram</span>
                        </div>
                        @error('weight') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Satuan Jual</label>
                        <select name="unit_id" required 
                                style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('unit_id') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px; background-color: white;">
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @error('unit_id') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 3: HARGA & STATUS -->
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 16px; font-weight: 700; color: #92400e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                    Harga & Status Operasional
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Harga Modal (HPP)</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 12px; color: #94a3b8; font-size: 14px;">Rp</span>
                            <input type="text" name="cost_price" value="{{ old('cost_price', $product->cost_price) ? number_format(old('cost_price', $product->cost_price), 0, ',', '.') : '' }}" 
                                   class="currency-input"
                                   style="width: 100%; padding: 12px 12px 12px 35px; border: 1px solid {{ $errors->has('cost_price') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                        </div>
                        @error('cost_price') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Harga Jual</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 12px; color: #94a3b8; font-size: 14px;">Rp</span>
                            <input type="text" name="price" value="{{ old('price', $product->price) ? number_format(old('price', $product->price), 0, ',', '.') : '' }}" 
                                   required class="currency-input"
                                   style="width: 100%; padding: 12px 12px 12px 35px; border: 1px solid {{ $errors->has('price') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px; font-weight: 700; color: #92400e;">
                        </div>
                        @error('price') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="padding-top: 15px; border-top: 1px solid #f1f5f9;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               style="width: 20px; height: 20px; accent-color: #92400e; cursor: pointer;">
                        <div>
                            <span style="font-size: 14px; font-weight: 600; color: #1e293b; display: block;">Produk Aktif</span>
                            <span style="font-size: 12px; color: #64748b;">Hapus centang jika produk sudah tidak dijual lagi.</span>
                        </div>
                    </label>
                    @error('is_active') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" 
                        style="flex: 2; padding: 14px; background: #92400e; color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s;">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.products.index') }}" 
                   style="flex: 1; padding: 14px; background: white; color: #64748b; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; font-weight: 600; text-decoration: none; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>

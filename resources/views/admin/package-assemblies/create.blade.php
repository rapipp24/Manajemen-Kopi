<x-layouts.admin>
    <x-slot name="title">Buat Stok Paket Baru</x-slot>

    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.package-assemblies.index') }}" style="color: var(--brown-500); text-decoration: none; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            &larr; Kembali ke Riwayat Buat Stok Paket
        </a>
    </div>

    @if(session('error'))
        <div style="background: #fff5f5; border: 1px solid #fecaca; color: #be123c; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">
        <!-- Form Kiri -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 24px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                Form Buat Stok Paket
            </h3>

            <form action="{{ route('admin.package-assemblies.store') }}" method="POST" id="assemblyForm">
                @csrf
                
                <!-- Pilih Paket -->
                <div style="margin-bottom: 20px;">
                    <label for="package_id" style="display: block; font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Pilih Master Paket <span style="color: #ef4444;">*</span></label>
                    <select name="package_id" id="package_id" required
                            style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13.5px; color: var(--text-main); outline: none; background: white;"
                            onchange="updateRecipe()">
                        <option value="">-- Pilih Paket --</option>
                        @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}" {{ old('package_id') == $pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }} ({{ $pkg->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px; font-weight: 600;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jumlah Pembuatan Paket -->
                <div style="margin-bottom: 20px;">
                    <label for="qty" style="display: block; font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Jumlah Paket yang Dibuat <span style="color: #ef4444;">*</span></label>
                    <input type="number" name="qty" id="qty" min="0.01" step="any" value="{{ old('qty', 1) }}" required
                           style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13.5px; color: var(--text-main); outline: none;"
                           oninput="updateRecipe()">
                    @error('qty')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px; font-weight: 600;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Catatan -->
                <div style="margin-bottom: 24px;">
                    <label for="note" style="display: block; font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Catatan (Opsional)</label>
                    <textarea name="note" id="note" rows="3" placeholder="Tambahkan catatan pembuatan stok paket..."
                              style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13.5px; color: var(--text-main); outline: none; resize: vertical;">{{ old('note') }}</textarea>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" id="submitBtn"
                        style="width: 100%; background: var(--brown-500); color: white; border: none; padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.15s; display: block; text-align: center; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
                        onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
                    Simpan & Buat Stok Paket
                </button>
            </form>
        </div>

        <!-- Detail Resep / Komponen Kanan -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 24px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02); min-height: 300px;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: #ea580c; border-radius: 2px;"></span>
                Isi Paket & Kecukupan Stok
            </h3>

            <div id="noRecipePlaceholder" style="color: var(--text-muted); font-size: 13.5px; text-align: center; padding: 48px 24px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 40px; height: 40px; margin: 0 auto 12px; opacity: 0.35;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.3 8.35h.007v.008H9.75v-.008z" />
                </svg>
                Pilih paket di sebelah kiri untuk melihat isi paket dan kecukupan stok di gudang.
            </div>

            <div id="recipeSection" style="display: none;">
                <div style="background: #fbfbfa; border: 1px solid var(--border); border-radius: 12px; padding: 14px; margin-bottom: 20px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <tr>
                            <td style="color: var(--text-muted); padding: 4px 0;">Nama Paket:</td>
                            <td id="infoPkgName" style="font-weight: 700; color: var(--text-main); text-align: right;">-</td>
                        </tr>
                        <tr>
                            <td style="color: var(--text-muted); padding: 4px 0;">Harga Jual:</td>
                            <td id="infoPkgPrice" style="font-weight: 700; color: var(--brown-500); text-align: right;">-</td>
                        </tr>
                        <tr>
                            <td style="color: var(--text-muted); padding: 4px 0;">HPP Saat Dibuat:</td>
                            <td id="infoPkgHpp" style="font-weight: 700; color: var(--text-main); text-align: right;">-</td>
                        </tr>
                    </table>
                </div>

                <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">Isi Paket</div>
                
                <div id="componentsContainer" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                    <!-- Rincian komponen diisi via JS -->
                </div>

                <!-- Alert kecukupan stok -->
                <div id="stockAlert" style="padding: 12px; border-radius: 10px; font-size: 13px; font-weight: 600; text-align: center;">
                    <!-- Status stok -->
                </div>
            </div>
        </div>
    </div>

    <!-- Data Master Paket ter-serialize untuk Javascript -->
    <script>
        const packages = @json($packages);

        function formatRupiahLocal(amount) {
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        }

        function updateRecipe() {
            const packageId = document.getElementById('package_id').value;
            const qtyInput = document.getElementById('qty').value;
            const assemblyQty = parseFloat(qtyInput) || 0;

            const noRecipePlaceholder = document.getElementById('noRecipePlaceholder');
            const recipeSection = document.getElementById('recipeSection');
            const container = document.getElementById('componentsContainer');
            const alertBox = document.getElementById('stockAlert');
            const submitBtn = document.getElementById('submitBtn');

            if (!packageId || assemblyQty <= 0) {
                noRecipePlaceholder.style.display = 'block';
                recipeSection.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
                return;
            }

            // Cari data paket
            const selectedPackage = packages.find(pkg => pkg.id == packageId);
            if (!selectedPackage) return;

            noRecipePlaceholder.style.display = 'none';
            recipeSection.style.display = 'block';

            document.getElementById('infoPkgName').textContent = selectedPackage.name;
            document.getElementById('infoPkgPrice').textContent = formatRupiahLocal(selectedPackage.selling_price);

            // Hitung estimasi HPP per pack & render komponen
            let estimatedHppPerPack = 0;
            let allStocksSufficient = true;
            let componentsHtml = '';

            selectedPackage.items.forEach(item => {
                const product = item.product;
                const costPrice = parseFloat(product.cost_price) || 0;
                estimatedHppPerPack += item.qty * costPrice;

                const qtyPerPack = parseFloat(item.qty);
                const totalNeeded = qtyPerPack * assemblyQty;
                const availableStock = parseFloat(product.current_stock) || 0;
                const unit = (product.unit && product.unit.name) ? product.unit.name : 'pcs';

                const isSufficient = availableStock >= totalNeeded;
                if (!isSufficient) {
                    allStocksSufficient = false;
                }

                componentsHtml += `
                    <div style="border: 1px solid var(--border); border-radius: 10px; padding: 12px; background: ${isSufficient ? '#ffffff' : '#fff5f5'}; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 700; font-size: 13.5px; color: var(--text-main);">${product.name}</div>
                            <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">
                                Resep: ${qtyPerPack} ${unit}/pack &bull; Butuh: <strong>${totalNeeded.toLocaleString('id-ID')} ${unit}</strong>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 12px; color: var(--text-muted);">Stok Gudang:</div>
                            <div style="font-weight: 700; font-size: 13.5px; color: ${isSufficient ? '#166534' : '#be123c'};">
                                ${availableStock.toLocaleString('id-ID')} ${unit}
                                ${isSufficient ? ' &nbsp;✔️' : ' &nbsp;❌'}
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = componentsHtml;
            document.getElementById('infoPkgHpp').textContent = formatRupiahLocal(estimatedHppPerPack);

            // Tampilkan alert status stok
            if (allStocksSufficient) {
                alertBox.style.background = '#f0fdf4';
                alertBox.style.border = '1px solid #bbf7d0';
                alertBox.style.color = '#166534';
                alertBox.innerHTML = '✔️ Semua stok isi paket mencukupi untuk pembuatan.';
                
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            } else {
                alertBox.style.background = '#fff5f5';
                alertBox.style.border = '1px solid #fecaca';
                alertBox.style.color = '#be123c';
                alertBox.innerHTML = '⚠️ Stok beberapa isi paket tidak mencukupi!';

                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
            }
        }

        // Jalankan kalkulasi resep saat load halaman (untuk menangani auto-fill form Laravel)
        window.addEventListener('DOMContentLoaded', () => {
            updateRecipe();
        });
    </script>
</x-layouts.admin>

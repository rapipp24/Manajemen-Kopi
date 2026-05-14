<x-layouts.admin>
    <x-slot name="title">Manajemen Jenis Produk</x-slot>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 24px; align-items: start;">
        <!-- Daftar Kategori -->
        <div style="background: white; border-radius: 16px; border: 1px solid #e7e5e4; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div style="padding: 16px 24px; border-bottom: 1px solid #f5f5f4; background: #fafaf9;">
                <h3 style="font-size: 15px; font-weight: 700; color: #1c1917; margin: 0;">Daftar Jenis Produk</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #fafaf9; border-bottom: 1px solid #f5f5f4;">
                        <th style="padding: 12px 24px; text-align: left; font-size: 11px; color: #78716c; text-transform: uppercase;">Nama Jenis</th>
                        <th style="padding: 12px 24px; text-align: center; font-size: 11px; color: #78716c; text-transform: uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr style="border-bottom: 1px solid #f5f5f4;">
                        <td style="padding: 14px 24px;">
                            <div style="font-weight: 600; color: #1c1917;">{{ $category->name }}</div>
                        </td>
                        <td style="padding: 14px 24px; text-align: center; display: flex; justify-content: center; gap: 8px;">
                            <form action="{{ route('admin.product-categories.destroy', $category) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="confirm-action" 
                                        data-confirm-title="Hapus Kategori?" 
                                        data-confirm-text="Pastikan kategori ini tidak lagi digunakan oleh produk apa pun."
                                        style="background: #fee2e2; border: none; color: #ef4444; cursor: pointer; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700;">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Form Tambah -->
        <div style="background: white; border-radius: 16px; border: 1px solid #e7e5e4; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <h3 style="font-size: 15px; font-weight: 700; color: #1c1917; margin-bottom: 20px;">Tambah Jenis Baru</h3>
            <form action="{{ route('admin.product-categories.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #44403c; margin-bottom: 8px;">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: Kopi Bubuk" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px;">
                </div>
                <button type="submit" style="width: 100%; padding: 12px; background: #92400e; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>
</x-layouts.admin>

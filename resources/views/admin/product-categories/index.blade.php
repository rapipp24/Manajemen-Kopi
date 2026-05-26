<x-layouts.admin>
    <x-slot name="title">Manajemen Jenis Produk</x-slot>

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start;">
        <!-- Daftar Kategori -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
            <div style="padding: 20px 24px; border-bottom: 1.5px solid var(--border); background: #fffdfb;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                    <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                    Daftar Jenis Produk
                </h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                        <th style="padding: 14px 24px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nama Jenis</th>
                        <th style="padding: 14px 24px; text-align: center; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fffdfb'" onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 14px 24px;">
                            <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $category->name }}</div>
                        </td>
                        <td style="padding: 14px 24px; text-align: center; display: flex; justify-content: center; align-items: center; gap: 8px;">
                            <form action="{{ route('admin.product-categories.destroy', $category) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="confirm-action" 
                                        data-confirm-title="Hapus Kategori?" 
                                        data-confirm-text="Pastikan kategori ini tidak lagi digunakan oleh produk apa pun."
                                        style="background: #fff5f5; border: 1px solid #ffe4e6; color: #be123c; cursor: pointer; padding: 6px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 700; transition: all 0.15s;"
                                        onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff5f5'">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding: 48px; text-align: center; color: var(--text-muted); font-size: 14px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.35; color: var(--text-muted);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <div>Belum ada data jenis produk.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Form Tambah -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 24px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                Tambah Jenis Baru
            </h3>
            <form action="{{ route('admin.product-categories.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 8px;">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: Kopi Bubuk" 
                           style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <button type="submit" 
                        style="width: 100%; padding: 12px; background: var(--brown-500); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
                        onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>
</x-layouts.admin>

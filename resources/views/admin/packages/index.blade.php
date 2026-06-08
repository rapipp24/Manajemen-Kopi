<x-layouts.admin>
    <x-slot name="title">Master Paket / Bundel Produk</x-slot>

    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <p style="color: var(--text-muted); font-size: 14px;">Kelola paket/bundel produk dengan harga khusus untuk sales lapangan dan kasir admin.</p>
        <a href="{{ route('admin.packages.create') }}" 
           style="background: var(--brown-500); color: white; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
           onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
            + Tambah Paket
        </a>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
        <div style="padding: 20px 24px; border-bottom: 1.5px solid var(--border); background: #fffdfb; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                Daftar Paket
            </h3>
            <form action="{{ route('admin.packages.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari paket..." 
                       style="padding: 8px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                <button type="submit" style="background: var(--brown-500); color: white; border: none; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                        onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">Cari</button>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Kode Paket</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nama Paket</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Harga Jual Paket</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Jumlah Item</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Status</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $package)
                <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fffdfb'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-mid); font-weight: 700;">
                        <code>{{ $package->code }}</code>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $package->name }}</div>
                        @if($package->description)
                            <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">{{ Str::limit($package->description, 50) }}</div>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-main); font-weight: 700; text-align: right;">
                        Rp {{ number_format($package->selling_price, 0, ',', '.') }}
                    </td>
                    <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-mid); text-align: center; font-weight: 600;">
                        {{ $package->items_count }} produk
                    </td>
                    <td style="padding: 16px 20px; font-size: 14px; text-align: center;">
                        @if($package->is_active)
                            <span style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Aktif</span>
                        @else
                            <span style="background: #fff5f5; color: #be123c; border: 1px solid #fecaca; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Non-Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('admin.packages.show', $package->id) }}" style="color: var(--brown-500); text-decoration: none; font-size: 13.5px; font-weight: 600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Detail</a>
                            <a href="{{ route('admin.packages.edit', $package->id) }}" style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Edit</a>
                            <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Hapus paket ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13.5px; font-weight: 600; padding: 0;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 48px; text-align: center; color: var(--text-muted); font-size: 14px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.35; color: var(--text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        <div>Belum ada data paket yang terdaftar.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($packages->hasPages())
    <div style="margin-top: 20px;">
        {{ $packages->links() }}
    </div>
    @endif
</x-layouts.admin>

<x-layouts.admin>
    <x-slot name="title">Daftar Member</x-slot>

    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <p style="color: var(--text-muted); font-size: 14px;">Kelola data member untuk program loyalitas dan riwayat penjualan.</p>
        <a href="{{ route('admin.customers.create') }}" 
           style="background: var(--brown-500); color: white; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
           onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
            + Tambah Member
        </a>
    </div>

    <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
        <div style="padding: 20px 24px; border-bottom: 1.5px solid var(--border); background: #fffdfb; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                Daftar Member
            </h3>
            <form action="{{ route('admin.customers.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari member..." 
                       style="padding: 8px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                <button type="submit" style="background: var(--brown-500); color: white; border: none; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                        onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">Cari</button>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nama Member</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Telepon</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fffdfb'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 16px 20px;">
                        <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $customer->name }}</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $customer->address ?? 'Alamat belum diisi' }}
                        </div>
                    </td>
                    <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-mid); font-weight: 600;">{{ $customer->phone ?? '-' }}</td>
                    <td style="padding: 16px 20px; font-size: 14px;">
                        @if($customer->is_active)
                            <span style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Aktif</span>
                        @else
                            <span style="background: #fff5f5; color: #be123c; border: 1px solid #fecaca; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Non-Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Edit</a>
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Hapus member ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13.5px; font-weight: 600; padding: 0;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 48px; text-align: center; color: var(--text-muted); font-size: 14px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.35; color: var(--text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <div>Belum ada data member yang terdaftar.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
    <div style="margin-top: 20px;">
        {{ $customers->links() }}
    </div>
    @endif
</x-layouts.admin>

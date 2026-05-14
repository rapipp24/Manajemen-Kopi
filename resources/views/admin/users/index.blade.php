<x-layouts.admin>
    <x-slot name="title">Manajemen Pelanggan</x-slot>

    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="padding: 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">Daftar Pengguna Sistem</h3>
                <p style="font-size: 13px; color: #64748b; margin: 4px 0 0 0;">Kelola akun Admin dan Sales di sini.</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 8px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                           style="padding: 8px 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; width: 250px;">
                    <button type="submit" style="background: white; border: 1px solid #cbd5e1; padding: 8px 15px; border-radius: 8px; font-size: 13px; cursor: pointer; font-weight: 600;">Cari</button>
                </form>
                <a href="{{ route('admin.users.create') }}" 
                   style="background: #92400e; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah User
                </a>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 15px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">User</th>
                    <th style="padding: 15px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Role</th>
                    <th style="padding: 15px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Kontak</th>
                    <th style="padding: 15px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Status</th>
                    <th style="padding: 15px 20px; text-align: right; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                    <td style="padding: 15px 20px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; background: #fef3c7; color: #92400e; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size: 14px; font-weight: 600; color: #0f172a;">{{ $user->name }}</div>
                                <div style="font-size: 12px; color: #64748b;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 15px 20px;">
                        @if($user->isAdmin())
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; border: 1px solid #fde68a;">Admin</span>
                        @else
                            <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; border: 1px solid #bbf7d0;">Sales</span>
                        @endif
                    </td>
                    <td style="padding: 15px 20px; font-size: 13px; color: #475569;">
                        {{ $user->phone ?? '-' }}
                    </td>
                    <td style="padding: 15px 20px;">
                        @if($user->is_active)
                            <span style="display: flex; align-items: center; gap: 6px; color: #166534; font-size: 13px; font-weight: 500;">
                                <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></span> Aktif
                            </span>
                        @else
                            <span style="display: flex; align-items: center; gap: 6px; color: #991b1b; font-size: 13px; font-weight: 500;">
                                <span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span> Non-Aktif
                            </span>
                        @endif
                    </td>
                    <td style="padding: 15px 20px; text-align: right;">
                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               style="color: #0284c7; text-decoration: none; font-size: 13px; font-weight: 600; padding: 6px 12px; background: #f0f9ff; border-radius: 6px; border: 1px solid #e0f2fe;">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: #fff1f2; border: 1px solid #ffe4e6; color: #be123c; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 50px; text-align: center; color: #94a3b8; font-size: 14px;">Belum ada user terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div style="padding: 15px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>

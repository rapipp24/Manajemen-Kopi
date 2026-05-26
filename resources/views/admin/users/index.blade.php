<x-layouts.admin>
    <x-slot name="title">Manajemen Pelanggan</x-slot>

    <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
        <div style="padding: 20px 24px; border-bottom: 1.5px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #fffdfb;">
            <div>
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                    Daftar Pengguna Sistem
                </h3>
                <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Kelola akun Admin dan Sales di sini.</p>
            </div>
            <div style="display: flex; gap: 12px; align-items: center;">
                <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 8px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                           style="padding: 8px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; width: 220px; outline: none; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    <button type="submit" style="background: var(--cream-100); border: 1px solid var(--border); color: var(--text-mid); padding: 8px 16px; border-radius: 10px; font-size: 13px; cursor: pointer; font-weight: 700; transition: all 0.2s;"
                            onmouseover="this.style.background='var(--cream-200)'" onmouseout="this.style.background='var(--cream-100)'">Cari</button>
                </form>
                <a href="{{ route('admin.users.create') }}" 
                   style="background: var(--brown-500); color: white; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-size: 13.5px; font-weight: 700; display: flex; align-items: center; gap: 8px; transition: background 0.15s;"
                   onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah User
                </a>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">User</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Role</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Kontak</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fffdfb'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 16px 20px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; background: #fffbeb; color: var(--brown-500); border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; box-shadow: 0 2px 4px rgba(120, 53, 15, 0.05);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $user->name }}</div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px 20px;">
                        @if($user->isAdmin())
                            <span style="background: #fffbeb; color: var(--brown-500); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #fde68a;">Admin</span>
                        @else
                            <span style="background: #f0fdf4; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #bbf7d0;">Sales</span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-mid); font-weight: 600;">
                        {{ $user->phone ?? '-' }}
                    </td>
                    <td style="padding: 16px 20px;">
                        @if($user->is_active)
                            <span style="display: inline-flex; align-items: center; gap: 5px; color: #166534; font-size: 13px; font-weight: 700;">
                                <span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%;"></span> Aktif
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 5px; color: #be123c; font-size: 13px; font-weight: 700;">
                                <span style="width: 6px; height: 6px; background: #ef4444; border-radius: 50%;"></span> Non-Aktif
                            </span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 600;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13.5px; font-weight: 600; padding: 0;"
                                        onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 48px; text-align: center; color: var(--text-muted); font-size: 14px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.35; color: var(--text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A12.018 12.018 0 0112 21c-1.08 0-2.117-.143-3.109-.413V20.47c0-1.18-.327-2.279-.894-3.218m0 0a4.125 4.125 0 00-7.534 2.492 9.335 9.335 0 004.12.952c.905 0 1.78-.128 2.625-.372m0-3.376a9.044 9.044 0 011.503-4.372m1.503 4.372a9.043 9.043 0 001.503-4.372m-1.503 4.372a11.962 11.962 0 00-3.324-4.372m3.324 4.372a11.963 11.963 0 013.324-4.372m-3.324-1.129a3 3 0 11-6 0 3 3 0 016 0zm9 1.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>Belum ada data user terdaftar.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: #fffdfb;">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>

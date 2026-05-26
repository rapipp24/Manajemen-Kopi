<x-layouts.admin>
    <x-slot name="title">Karyawan Gudang</x-slot>

    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:12px; margin-bottom:20px; font-size:13.5px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; color: var(--text-main); margin: 0;">Karyawan Gudang</h1>
            <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Master data karyawan gudang untuk pencatatan absensi.</p>
        </div>
        <a href="{{ route('admin.warehouse-employees.create') }}" 
           style="background: var(--brown-500); color: white; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-size: 13.5px; font-weight: 700; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
           onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'"
           id="btn-tambah-karyawan">
            + Tambah Karyawan
        </a>
    </div>

    {{-- Filter --}}
    <form action="{{ route('admin.warehouse-employees.index') }}" method="GET" 
          style="background: white; border: 1px solid var(--border); border-radius: 16px; padding: 20px; margin-bottom: 24px; display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Cari Nama</label>
            <input type="text" name="search" placeholder="Cari nama..." value="{{ request('search') }}" 
                   style="padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13.5px; color: var(--text-main); outline: none; min-width: 220px; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
        </div>
        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Status</label>
            <select name="status" 
                    style="padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13.5px; color: var(--text-main); background: #fff; outline: none; min-width: 160px; transition: border-color 0.2s;"
                    onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div style="display:flex; gap:8px; align-items:flex-end;">
            <button type="submit" 
                    style="background: var(--brown-500); color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13.5px; font-weight: 700; cursor: pointer; transition: background 0.15s;"
                    onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">Cari</button>
            <a href="{{ route('admin.warehouse-employees.index') }}" 
               style="background: var(--cream-100); color: var(--text-mid); border: 1px solid var(--border); padding: 10px 20px; border-radius: 10px; font-size: 13.5px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; transition: all 0.2s;"
               onmouseover="this.style.background='var(--cream-200)'" onmouseout="this.style.background='var(--cream-100)'">Reset</a>
        </div>
    </form>

    {{-- Tabel --}}
    <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nama Karyawan</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">No HP</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Catatan</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fffdfb'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 14px 20px; font-weight: 700; color: var(--text-main);">{{ $emp->name }}</td>
                    <td style="padding: 14px 20px; color: var(--text-mid); font-weight: 600;">{{ $emp->phone ?? '-' }}</td>
                    <td style="padding: 14px 20px;">
                        @if($emp->is_active)
                            <span style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Aktif</span>
                        @else
                            <span style="background: #fff5f5; color: #be123c; border: 1px solid #fecaca; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding: 14px 20px; color: var(--text-muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $emp->note ? \Str::limit($emp->note, 60) : '-' }}
                    </td>
                    <td style="padding: 14px 20px; text-align: right; white-space: nowrap;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('admin.warehouse-employees.edit', $emp->id) }}" 
                               style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 600;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Edit
                            </a>
                            <form action="{{ route('admin.warehouse-employees.destroy', $emp->id) }}" method="POST"
                                  onsubmit="return confirm('{{ $emp->attendances()->exists() ? 'Karyawan ini memiliki riwayat absensi. Karyawan akan dinonaktifkan, bukan dihapus permanen. Lanjutkan?' : 'Hapus karyawan ini?' }}')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13.5px; font-weight: 600; padding: 0;"
                                        onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                    {{ $emp->attendances()->exists() ? 'Nonaktifkan' : 'Hapus' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 48px; text-align: center; color: var(--text-muted); font-size: 14px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.35; color: var(--text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        <div>Belum ada data karyawan gudang.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($employees->hasPages())
        <div style="padding: 16px 24px; border-top: 1.5px solid var(--border); background: #fffdfb;">
            {{ $employees->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>

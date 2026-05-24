<x-layouts.admin>
    <x-slot name="title">Karyawan Gudang</x-slot>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; gap: 16px; flex-wrap: wrap; }
        .page-title-block h1 { font-size: 20px; font-weight: 700; color: #1c1917; margin: 0; }
        .page-title-block p  { font-size: 13px; color: #78716c; margin: 4px 0 0 0; }

        .btn-primary {
            background: #92400e; color: #fff; text-decoration: none;
            padding: 10px 18px; border-radius: 9px; font-size: 13px; font-weight: 700;
            display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer;
            transition: background 0.15s;
        }
        .btn-primary:hover { background: #78350f; }

        .filter-card {
            background: #fff; border: 1px solid #e7e5e4; border-radius: 12px;
            padding: 16px 20px; margin-bottom: 20px;
            display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;
        }
        .filter-group { display: flex; flex-direction: column; gap: 4px; }
        .filter-group label { font-size: 11px; font-weight: 700; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; }
        .filter-control {
            padding: 8px 12px; border: 1px solid #d6d3d1; border-radius: 8px;
            font-size: 13px; color: #1c1917; background: #fff;
        }
        .filter-control:focus { border-color: #92400e; outline: none; }
        .btn-filter {
            background: #92400e; color: #fff; border: none; padding: 9px 16px;
            border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
        }
        .btn-filter-reset {
            background: #fff; color: #78716c; border: 1px solid #d6d3d1;
            padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; text-decoration: none; display: inline-block;
        }

        .card { background: #fff; border: 1px solid #e7e5e4; border-radius: 12px; overflow: hidden; }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead tr { background: #fafaf8; border-bottom: 1px solid #e7e5e4; }
        th { padding: 13px 18px; font-size: 11px; font-weight: 700; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px; }
        tbody tr { border-bottom: 1px solid #f5f5f4; }
        tbody tr:last-child { border-bottom: none; }
        td { padding: 13px 18px; font-size: 13.5px; color: #1c1917; }

        .badge {
            display: inline-block; padding: 3px 10px; border-radius: 20px;
            font-size: 11.5px; font-weight: 700;
        }
        .badge-aktif     { background: #dcfce7; color: #166534; }
        .badge-nonaktif  { background: #f3f4f6; color: #6b7280; }

        .action-btn {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 5px 12px; border-radius: 7px; font-size: 12.5px; font-weight: 600;
            text-decoration: none; transition: background 0.15s; border: 1px solid;
        }
        .action-edit  { background: #eff6ff; color: #1d4ed8; border-color: #dbeafe; }
        .action-edit:hover  { background: #dbeafe; }
        .action-delete { background: #fff1f2; color: #be123c; border-color: #ffe4e6; cursor: pointer; }
        .action-delete:hover { background: #ffe4e6; }

        .empty-state { padding: 60px; text-align: center; color: #a8a29e; }
        .empty-state p { font-size: 14px; margin: 0; }

        .pagination-wrap { padding: 14px 18px; border-top: 1px solid #e7e5e4; background: #fafaf8; }
    </style>

    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div class="page-title-block">
            <h1>Karyawan Gudang</h1>
            <p>Master data karyawan gudang untuk pencatatan absensi.</p>
        </div>
        <a href="{{ route('admin.warehouse-employees.create') }}" class="btn-primary" id="btn-tambah-karyawan">
            + Tambah Karyawan
        </a>
    </div>

    {{-- Filter --}}
    <form action="{{ route('admin.warehouse-employees.index') }}" method="GET" class="filter-card">
        <div class="filter-group">
            <label>Cari Nama</label>
            <input type="text" name="search" class="filter-control" placeholder="Cari nama..." value="{{ request('search') }}" style="min-width: 200px;">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select name="status" class="filter-control">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div style="display:flex; gap:8px; align-items:flex-end;">
            <button type="submit" class="btn-filter">Cari</button>
            <a href="{{ route('admin.warehouse-employees.index') }}" class="btn-filter-reset">Reset</a>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Nama Karyawan</th>
                    <th>No HP</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    <td style="font-weight: 600;">{{ $emp->name }}</td>
                    <td style="color: #78716c;">{{ $emp->phone ?? '-' }}</td>
                    <td>
                        @if($emp->is_active)
                            <span class="badge badge-aktif">Aktif</span>
                        @else
                            <span class="badge badge-nonaktif">Nonaktif</span>
                        @endif
                    </td>
                    <td style="color: #78716c; max-width: 200px;">
                        {{ $emp->note ? \Str::limit($emp->note, 60) : '-' }}
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <a href="{{ route('admin.warehouse-employees.edit', $emp->id) }}" class="action-btn action-edit">
                                Edit
                            </a>
                            <form action="{{ route('admin.warehouse-employees.destroy', $emp->id) }}" method="POST"
                                  onsubmit="return confirm('{{ $emp->attendances()->exists() ? 'Karyawan ini memiliki riwayat absensi. Karyawan akan dinonaktifkan, bukan dihapus permanen. Lanjutkan?' : 'Hapus karyawan ini?' }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-delete">
                                    {{ $emp->attendances()->exists() ? 'Nonaktifkan' : 'Hapus' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        <p>Belum ada data karyawan gudang.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($employees->hasPages())
        <div class="pagination-wrap">
            {{ $employees->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>

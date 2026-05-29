<x-layouts.admin>
    <x-slot name="title">Manajemen User</x-slot>

    {{-- ─── Tab Filter & Header ─────────────────────────────────────────────── --}}
    <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">

        {{-- Header --}}
        <div style="padding: 20px 24px; border-bottom: 1.5px solid var(--border); background: #fffdfb;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 8px;">
                        <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                        Daftar Pengguna Sistem
                    </h3>
                    <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Kelola akun Admin dan Sales. Setujui atau tolak pendaftaran Sales baru.</p>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    {{-- Search --}}
                    <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 8px;">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                               style="padding: 8px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; width: 200px; outline: none;"
                               onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                        <button type="submit" style="background: var(--cream-100); border: 1px solid var(--border); color: var(--text-mid); padding: 8px 14px; border-radius: 10px; font-size: 13px; cursor: pointer; font-weight: 600;">Cari</button>
                    </form>
                    {{-- Tambah User --}}
                    <a href="{{ route('admin.users.create') }}"
                       style="background: var(--brown-500); color: white; text-decoration: none; padding: 9px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                        <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah User
                    </a>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <div style="display: flex; gap: 4px; margin-top: 16px; border-bottom: 1.5px solid var(--border); padding-bottom: 0;">
                @php
                    $tabs = [
                        'semua'    => 'Semua',
                        'pending'  => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ];
                @endphp
                @foreach($tabs as $tabKey => $tabLabel)
                    <a href="{{ route('admin.users.index', array_merge(request()->query(), ['tab' => $tabKey])) }}"
                       style="padding: 8px 16px; font-size: 13px; font-weight: 600; text-decoration: none; border-radius: 8px 8px 0 0; border: 1px solid transparent; margin-bottom: -1.5px;
                              {{ $tab === $tabKey
                                ? 'background: white; border-color: var(--border); border-bottom-color: white; color: var(--brown-500);'
                                : 'color: var(--text-muted);' }}
                              display: inline-flex; align-items: center; gap: 6px;">
                        {{ $tabLabel }}
                        @if($tabKey === 'pending' && $pendingApprovalCount > 0)
                            <span style="background: #ef4444; color: white; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 10px;">
                                {{ $pendingApprovalCount > 99 ? '99+' : $pendingApprovalCount }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ─── Tabel User ───────────────────────────────────────────────────── --}}
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 640px;">
                <thead>
                    <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                        <th style="padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">User</th>
                        <th style="padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Role</th>
                        <th style="padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th style="padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Email</th>
                        <th style="padding: 12px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.15s;"
                        onmouseover="this.style.backgroundColor='#fffdfb'"
                        onmouseout="this.style.backgroundColor='transparent'">

                        {{-- Kolom: User Info --}}
                        <td style="padding: 14px 20px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 34px; height: 34px; background: #fffbeb; color: var(--brown-500); border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; flex-shrink: 0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $user->name }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted);">{{ $user->phone ?? '—' }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom: Role --}}
                        <td style="padding: 14px 20px;">
                            @if($user->isAdmin())
                                <span style="background: #fffbeb; color: var(--brown-500); padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #fde68a;">Admin</span>
                            @else
                                <span style="background: #f0fdf4; color: #166534; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #bbf7d0;">Sales</span>
                            @endif
                        </td>

                        {{-- Kolom: Status --}}
                        <td style="padding: 14px 20px;">
                            @if($user->isApproved() && $user->is_active)
                                <span style="display:inline-flex;align-items:center;gap:5px;color:#166534;font-size:12px;font-weight:700;">
                                    <span style="width:6px;height:6px;background:#22c55e;border-radius:50%;flex-shrink:0;"></span> Aktif
                                </span>
                            @elseif($user->isPending())
                                <span style="display:inline-flex;align-items:center;gap:5px;color:#92400e;font-size:12px;font-weight:700;">
                                    <span style="width:6px;height:6px;background:#f59e0b;border-radius:50%;flex-shrink:0;"></span> Menunggu Approval
                                </span>
                                @if($user->email_verified_at)
                                    <div style="font-size:11px;color:#22c55e;margin-top:2px;">✓ Email terverifikasi</div>
                                @else
                                    <div style="font-size:11px;color:#9ca3af;margin-top:2px;">Belum verifikasi email</div>
                                @endif
                            @elseif($user->isRejected())
                                <span style="display:inline-flex;align-items:center;gap:5px;color:#be123c;font-size:12px;font-weight:700;">
                                    <span style="width:6px;height:6px;background:#ef4444;border-radius:50%;flex-shrink:0;"></span> Ditolak
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:5px;color:#6b7280;font-size:12px;font-weight:700;">
                                    <span style="width:6px;height:6px;background:#9ca3af;border-radius:50%;flex-shrink:0;"></span> Non-Aktif
                                </span>
                            @endif
                        </td>

                        {{-- Kolom: Email --}}
                        <td style="padding: 14px 20px; font-size: 13px; color: var(--text-mid);">
                            {{ $user->email }}
                            @if($user->email_verified_at)
                                <div style="font-size:11px;color:#22c55e;">✓ Terverifikasi</div>
                            @else
                                <div style="font-size:11px;color:#9ca3af;">Belum diverifikasi</div>
                            @endif
                        </td>

                        {{-- Kolom: Aksi --}}
                        <td style="padding: 14px 20px; text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">

                                {{-- Tombol Approve: hanya untuk sales pending yang sudah verified email --}}
                                @if($user->isSales() && $user->isPending() && $user->hasVerifiedEmail())
                                    <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit"
                                                class="confirm-action"
                                                data-confirm-title="Setujui Pendaftaran?"
                                                data-confirm-text="Akun {{ $user->name }} akan diaktifkan dan Sales ini bisa login ke portal."
                                                data-confirm-icon="question"
                                                style="background: #166534; color: white; border: none; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">
                                            ✓ Setujui
                                        </button>
                                    </form>

                                    {{-- Tombol Reject --}}
                                    <button type="button"
                                            onclick="openRejectModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">
                                        ✕ Tolak
                                    </button>
                                @endif

                                {{-- Edit --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   style="color: #0284c7; text-decoration: none; font-size: 13px; font-weight: 600; padding: 6px 10px;">
                                    Edit
                                </a>

                                {{-- Hapus (kecuali diri sendiri dan ID 1) --}}
                                @if($user->id !== auth()->id() && $user->id !== 1)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="confirm-action"
                                                data-confirm-title="Hapus User?"
                                                data-confirm-text="User {{ $user->name }} akan dihapus permanen. Tindakan ini tidak bisa dibatalkan."
                                                data-confirm-icon="warning"
                                                style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px; font-weight: 600; padding: 6px 4px;">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 48px; text-align: center; color: var(--text-muted);">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.3;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A12.018 12.018 0 0112 21c-1.08 0-2.117-.143-3.109-.413V20.47c0-1.18-.327-2.279-.894-3.218m0 0a4.125 4.125 0 00-7.534 2.492" />
                            </svg>
                            <div style="font-size: 14px;">
                                @if($tab === 'pending')
                                    Tidak ada Sales yang menunggu persetujuan. 🎉
                                @elseif($tab === 'rejected')
                                    Tidak ada pendaftaran yang ditolak.
                                @elseif($tab === 'approved')
                                    Belum ada user yang disetujui.
                                @else
                                    Belum ada data user terdaftar.
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: #fffdfb;">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- ─── Modal Reject ─────────────────────────────────────────────────────── --}}
    <div id="reject-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:1000; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:16px; padding:28px; max-width:420px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <h3 style="font-size:16px; font-weight:800; color:#1c1917; margin:0 0 6px;">Tolak Pendaftaran</h3>
            <p id="reject-modal-desc" style="font-size:13.5px; color:#57534e; margin:0 0 20px;"></p>
            <form id="reject-form" method="POST">
                @csrf
                <div style="margin-bottom:16px;">
                    <label style="font-size:11px; font-weight:700; color:#57534e; text-transform:uppercase; letter-spacing:0.8px; display:block; margin-bottom:6px;">
                        Alasan Penolakan <span style="font-weight:400; text-transform:none;">(opsional)</span>
                    </label>
                    <textarea name="rejection_reason" rows="3"
                              placeholder="Contoh: Email tidak valid, Bukan karyawan terdaftar, dll."
                              style="width:100%; border:1px solid #e7e5e4; border-radius:10px; padding:10px 14px; font-size:13.5px; font-family:inherit; resize:vertical; outline:none;"
                              onfocus="this.style.borderColor='#92400e'" onblur="this.style.borderColor='#e7e5e4'"></textarea>
                </div>
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" onclick="closeRejectModal()"
                            style="background:#f5f5f4; color:#57534e; border:1px solid #e7e5e4; padding:9px 20px; border-radius:10px; font-size:13.5px; font-weight:600; cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                            style="background:#dc2626; color:white; border:none; padding:9px 20px; border-radius:10px; font-size:13.5px; font-weight:700; cursor:pointer;">
                        Tolak Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(userId, userName) {
            const modal = document.getElementById('reject-modal');
            const form  = document.getElementById('reject-form');
            const desc  = document.getElementById('reject-modal-desc');

            form.action = `/admin/users/${userId}/reject`;
            desc.textContent = `Anda akan menolak pendaftaran "${userName}". Sales ini tidak akan bisa login ke portal.`;

            // Reset textarea
            form.querySelector('textarea').value = '';

            modal.style.display = 'flex';
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').style.display = 'none';
        }

        // Tutup modal saat klik di luar
        document.getElementById('reject-modal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
</x-layouts.admin>

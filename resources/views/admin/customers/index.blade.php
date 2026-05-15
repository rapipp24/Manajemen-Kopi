<x-layouts.admin>
    <x-slot name="title">Daftar Member</x-slot>

    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <p style="color: #64748b; font-size: 14px;">Kelola data member untuk program loyalitas dan riwayat penjualan.</p>
        <a href="{{ route('admin.customers.create') }}" 
           style="background: #92400e; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;">
            + Tambah Member
        </a>
    </div>

    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 600; color: #1e293b;">Daftar Member</h3>
            <form action="{{ route('admin.customers.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." 
                       style="padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                <button type="submit" style="background: #f1f5f9; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px; font-size: 13px; cursor: pointer;">Cari</button>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Nama Member</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Telepon</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Status</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px 20px;">
                        <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $customer->name }}</div>
                        <div style="font-size: 12px; color: #64748b; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $customer->address ?? 'Alamat belum diisi' }}
                        </div>
                    </td>
                    <td style="padding: 16px 20px; font-size: 14px; color: #475569;">{{ $customer->phone ?? '-' }}</td>
                    <td style="padding: 16px 20px; font-size: 14px;">
                        @if($customer->is_active)
                            <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Aktif</span>
                        @else
                            <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Non-Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end;">
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" style="color: #0284c7; text-decoration: none; font-size: 13px; font-weight: 500;">Edit</a>
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Hapus member ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px; font-weight: 500;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 40px; text-align: center; color: #94a3b8; font-size: 14px;">Belum ada data member.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $customers->links() }}
    </div>
</x-layouts.admin>

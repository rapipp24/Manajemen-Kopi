<x-layouts.admin>
    <x-slot name="title">Data Supplier</x-slot>

    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <p style="color: #64748b; font-size: 14px;">Kelola daftar pemasok bahan baku kopi Anda.</p>
        <a href="{{ route('admin.suppliers.create') }}" 
           style="background: #92400e; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;">
            + Tambah Supplier
        </a>
    </div>

    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Nama Supplier</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">CP / Kontak</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Telepon</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Alamat</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px 20px; font-size: 14px; font-weight: 600; color: #1e293b;">{{ $supplier->name }}</td>
                    <td style="padding: 16px 20px; font-size: 14px; color: #475569;">{{ $supplier->contact_person ?? '-' }}</td>
                    <td style="padding: 16px 20px; font-size: 14px; color: #475569;">{{ $supplier->phone ?? '-' }}</td>
                    <td style="padding: 16px 20px; font-size: 14px; color: #64748b; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $supplier->address ?? '-' }}
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end;">
                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" style="color: #0284c7; text-decoration: none; font-size: 13px; font-weight: 500;">Edit</a>
                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Hapus supplier ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px; font-weight: 500;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8; font-size: 14px;">Belum ada data supplier.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $suppliers->links() }}
    </div>
</x-layouts.admin>

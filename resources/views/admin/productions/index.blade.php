<x-layouts.admin>
    <x-slot name="title">Daftar Produksi</x-slot>

    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        {{-- Header Card --}}
        <div style="padding: 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">Daftar Produksi</h3>
                <p style="font-size: 13px; color: #64748b; margin: 4px 0 0 0;">Riwayat seluruh batch produksi kopi.</p>
            </div>
            <a href="{{ route('admin.productions.create') }}"
               style="background: #92400e; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Produksi
            </a>
        </div>

        {{-- Table --}}
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">No. Batch</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Tanggal</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Jenis Produksi</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Bahan Digunakan</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Hasil</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Susut</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Dibuat Oleh</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productions as $production)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 14px 20px;">
                        <span style="font-family: monospace; font-size: 13px; font-weight: 700; color: #92400e; background: #fef3c7; padding: 3px 8px; border-radius: 5px;">
                            {{ $production->batch_number }}
                        </span>
                    </td>
                    <td style="padding: 14px 20px; font-size: 13px; color: #475569;">
                        {{ \Carbon\Carbon::parse($production->production_date)->format('d M Y') }}
                    </td>
                    <td style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #1e293b;">
                        {{ $production->product_type }}
                    </td>
                    <td style="padding: 14px 20px; font-size: 13px; color: #475569;">
                        {{ floor((float)$production->total_material_used) == (float)$production->total_material_used ? number_format($production->total_material_used, 0, ',', '.') : rtrim(rtrim(number_format($production->total_material_used, 2, ',', '.'), '0'), ',') }} kg
                    </td>
                    <td style="padding: 14px 20px; font-size: 13px; color: #166534; font-weight: 600;">
                        {{ floor((float)$production->total_output) == (float)$production->total_output ? number_format($production->total_output, 0, ',', '.') : rtrim(rtrim(number_format($production->total_output, 2, ',', '.'), '0'), ',') }} kg
                    </td>
                    <td style="padding: 14px 20px; font-size: 13px; color: #dc2626;">
                        {{ floor((float)$production->shrinkage) == (float)$production->shrinkage ? number_format($production->shrinkage, 0, ',', '.') : rtrim(rtrim(number_format($production->shrinkage, 2, ',', '.'), '0'), ',') }} kg
                    </td>
                    <td style="padding: 14px 20px; font-size: 13px; color: #475569;">
                        {{ $production->creator->name ?? '-' }}
                    </td>
                    <td style="padding: 14px 20px; text-align: right;">
                        <a href="{{ route('admin.productions.show', $production) }}"
                           style="color: #0284c7; text-decoration: none; font-size: 13px; font-weight: 600; padding: 6px 12px; background: #f0f9ff; border-radius: 6px; border: 1px solid #e0f2fe;">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 50px; text-align: center; color: #94a3b8; font-size: 14px;">
                        Belum ada data produksi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($productions->hasPages())
        <div style="padding: 15px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
            {{ $productions->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>

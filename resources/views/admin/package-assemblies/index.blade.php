<x-layouts.admin>
    <x-slot name="title">Riwayat Buat Stok Paket</x-slot>

    @php
        $showHppColumns = $assemblies->contains(function ($assembly) {
            return $assembly->hpp_per_package_snapshot > 0;
        });
        $totalColumns = $showHppColumns ? 8 : 6;
    @endphp

    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
        <p style="color: var(--text-muted); font-size: 13.5px; line-height: 1.5; margin: 0; max-width: 70%;">
            Catatan pembuatan stok paket fisik di gudang utama.<br>
            <span style="font-size: 12px; color: var(--brown-500); display: block; margin-top: 4px;">
                <strong>Note:</strong> <em>Jumlah Dibuat</em> menunjukkan total pack yang dibuat pada transaksi tersebut. <em>Stok Gudang Saat Ini</em> menunjukkan sisa stok paket yang masih tersedia di gudang sekarang.
            </span>
        </p>
        <a href="{{ route('admin.package-assemblies.create') }}" 
           style="background: var(--brown-500); color: white; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15); display: inline-block; white-space: nowrap;"
           onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
            + Buat Stok Paket Baru
        </a>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
        <div style="padding: 20px 24px; border-bottom: 1.5px solid var(--border); background: #fffdfb;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                Daftar Pembuatan Stok Paket
            </h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: {{ $showHppColumns ? '800px' : '100%' }};">
                <thead>
                    <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                        <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Waktu</th>
                        <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">No. Pembuatan</th>
                        <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Paket</th>
                        <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Jumlah Dibuat</th>
                        @if($showHppColumns)
                        <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">HPP Saat Dibuat</th>
                        <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Total HPP</th>
                        @endif
                        <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Operator</th>
                        <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assemblies as $assembly)
                    <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fffdfb'" onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-muted);">
                            {{ $assembly->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-mid); font-weight: 700;">
                            <code>{{ $assembly->assembly_number }}</code>
                        </td>
                        <td style="padding: 16px 20px;">
                            <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">
                                {{ $assembly->package->name ?? 'Paket Terhapus' }}
                            </div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <code>{{ $assembly->package->code ?? '-' }}</code>
                                @if(isset($assembly->package))
                                <span style="font-size: 11px; color: #166534; font-weight: 700; background: #f0fdf4; padding: 2px 8px; border-radius: 6px; border: 1px solid #bbf7d0; display: inline-block; white-space: nowrap;">
                                    Stok Gudang Saat Ini: {{ number_format($assembly->package->stock->qty ?? 0, 0, ',', '.') }} pack
                                </span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-mid); text-align: center; font-weight: 700;">
                            {{ number_format($assembly->qty, 0, ',', '.') }} pack
                        </td>
                        @if($showHppColumns)
                        <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-main); font-weight: 600; text-align: right;">
                            @if($assembly->hpp_per_package_snapshot > 0)
                                Rp {{ number_format($assembly->hpp_per_package_snapshot, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-main); font-weight: 700; text-align: right;">
                            @if($assembly->hpp_per_package_snapshot > 0)
                                Rp {{ number_format($assembly->qty * $assembly->hpp_per_package_snapshot, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        @endif
                        <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-mid);">
                            {{ $assembly->creator->name ?? 'Sistem' }}
                        </td>
                        <td style="padding: 16px 20px; text-align: right;">
                            <a href="{{ route('admin.package-assemblies.show', $assembly->id) }}" 
                               style="color: var(--brown-500); text-decoration: none; font-size: 13.5px; font-weight: 600;" 
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Detail Rincian
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $totalColumns }}" style="padding: 48px; text-align: center; color: var(--text-muted); font-size: 14px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.35; color: var(--text-muted);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            <div>Belum ada riwayat pembuatan stok paket.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($assemblies->hasPages())
    <div style="margin-top: 20px;">
        {{ $assemblies->links() }}
    </div>
    @endif
</x-layouts.admin>

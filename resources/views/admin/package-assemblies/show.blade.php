<x-layouts.admin>
    <x-slot name="title">Rincian Pembuatan Stok Paket</x-slot>

    @php
        $showHpp = $packageAssembly->hpp_per_package_snapshot > 0;
        
        if (!function_exists('formatQty')) {
            function formatQty($qty) {
                if ($qty == (int)$qty) {
                    return number_format($qty, 0, ',', '.');
                }
                $formatted = number_format($qty, 2, ',', '.');
                return rtrim(rtrim($formatted, '0'), ',');
            }
        }
    @endphp

    <style>
        .show-grid {
            display: grid;
            grid-template-columns: 1.2fr 2fr;
            gap: 24px;
            align-items: start;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }
        .info-table td {
            padding: 10px 0;
            vertical-align: top;
        }
        .info-table td.label {
            color: var(--text-muted);
            width: 40%;
        }
        .info-table td.value {
            font-weight: 700;
            color: var(--text-main);
            text-align: right;
            width: 60%;
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        @media (max-width: 991px) {
            .show-grid {
                grid-template-columns: 1fr;
            }
            .info-table td {
                display: block;
                width: 100% !important;
                padding: 4px 0;
                text-align: left !important;
            }
            .info-table td.value {
                margin-bottom: 8px;
                font-weight: 700;
            }
        }
    </style>

    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.package-assemblies.index') }}" style="color: var(--brown-500); text-decoration: none; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            &larr; Kembali ke Riwayat Buat Stok Paket
        </a>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="show-grid">
        <!-- Header Info Kiri -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 24px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                Informasi Umum
            </h3>

            <table class="info-table">
                <tr style="border-bottom: 1px solid #fcf6ee;">
                    <td class="label">No. Pembuatan:</td>
                    <td class="value">
                        <code>{{ $packageAssembly->assembly_number }}</code>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #fcf6ee;">
                    <td class="label">Nama Paket:</td>
                    <td class="value">
                        {{ $packageAssembly->package->name ?? 'Terhapus' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #fcf6ee;">
                    <td class="label">Kode Paket:</td>
                    <td class="value">
                        <code>{{ $packageAssembly->package->code ?? '-' }}</code>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #fcf6ee;">
                    <td class="label">Jumlah Pembuatan:</td>
                    <td class="value">
                        {{ formatQty($packageAssembly->qty) }} pack
                    </td>
                </tr>
                @if($showHpp)
                <tr style="border-bottom: 1px solid #fcf6ee;">
                    <td class="label">HPP Saat Dibuat:</td>
                    <td class="value" style="color: var(--brown-500);">
                        Rp {{ number_format($packageAssembly->hpp_per_package_snapshot, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #fcf6ee;">
                    <td class="label">Total HPP Transaksi:</td>
                    <td class="value" style="font-weight: 800;">
                        Rp {{ number_format($packageAssembly->qty * $packageAssembly->hpp_per_package_snapshot, 0, ',', '.') }}
                    </td>
                </tr>
                @endif
                <tr style="border-bottom: 1px solid #fcf6ee;">
                    <td class="label">Waktu Pembuatan:</td>
                    <td class="value" style="font-weight: normal;">
                        {{ $packageAssembly->created_at->format('d/m/Y H:i:s') }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #fcf6ee;">
                    <td class="label">Dibuat Oleh:</td>
                    <td class="value" style="font-weight: normal;">
                        {{ $packageAssembly->creator->name ?? 'Sistem' }}
                    </td>
                </tr>
            </table>

            @if($packageAssembly->note)
                <div style="margin-top: 20px; background: #fffdfb; border: 1px solid var(--border); border-radius: 10px; padding: 12px;">
                    <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Catatan:</div>
                    <div style="font-size: 13px; color: var(--text-mid); line-height: 1.4;">{{ $packageAssembly->note }}</div>
                </div>
            @endif
        </div>

        <!-- Detail Komponen Kanan -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
            <div style="padding: 20px 24px; border-bottom: 1.5px solid var(--border); background: #fffdfb;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                    <span style="width: 4px; height: 16px; background: #ea580c; border-radius: 2px;"></span>
                    Rincian Isi Paket
                </h3>
            </div>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: {{ $showHpp ? '600px' : '100%' }};">
                    <thead>
                        <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                            <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nama Produk</th>
                            <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Qty per Paket</th>
                            <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Total Digunakan</th>
                            @if($showHpp)
                            <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">HPP Satuan</th>
                            <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Subtotal HPP</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packageAssembly->items as $item)
                            @php
                                $unit = $item->product->unit->name ?? 'pcs';
                                $subtotalHpp = $item->total_qty_used * $item->cost_price_snapshot;
                            @endphp
                            <tr style="border-bottom: 1px solid #fcf6ee;">
                                <td style="padding: 16px 20px;">
                                    <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">
                                        {{ $item->product->name ?? 'Produk Terhapus' }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                                        <code>{{ $item->product->code ?? '-' }}</code>
                                    </div>
                                </td>
                                <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-mid); text-align: center;">
                                    {{ formatQty($item->qty_per_package) }} {{ $unit }}
                                </td>
                                <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-main); font-weight: 700; text-align: center;">
                                    {{ formatQty($item->total_qty_used) }} {{ $unit }}
                                </td>
                                @if($showHpp)
                                <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-mid); text-align: right;">
                                    Rp {{ number_format($item->cost_price_snapshot, 0, ',', '.') }}
                                </td>
                                <td style="padding: 16px 20px; font-size: 13.5px; color: var(--text-main); font-weight: 700; text-align: right;">
                                    Rp {{ number_format($subtotalHpp, 0, ',', '.') }}
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>

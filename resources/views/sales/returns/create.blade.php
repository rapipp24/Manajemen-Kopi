<x-layouts.user>
    <x-slot name="title">Ajukan Return Barang</x-slot>

    <a href="{{ route('sales.returns.index') }}"
       style="display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:500;color:#78716c;text-decoration:none;margin-bottom:18px;">
        ← Kembali ke Daftar Return
    </a>

    <h1 style="font-size:18px;font-weight:800;color:#1c1917;margin-bottom:12px;">Ajukan Return Barang</h1>

    {{-- Helper: Penjelasan scope return versi ini --}}
    <div style="background:#f0f9ff;border:1px solid #bae6fd;border-left:3px solid #38bdf8;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:12.5px;color:#0369a1;line-height:1.6;">
        <strong>Catatan:</strong> Return yang diterima admin akan masuk kembali ke stok produk yang sama.
        Jika barang perlu proses ulang atau packing ulang, tuliskan keterangannya di kolom <strong>Catatan Tambahan</strong> di bawah —
        proses fisiknya ditangani manual oleh gudang. Stok khusus barang return/proses ulang belum tersedia di versi ini.
    </div>

    @if(session('error'))
        <div style="background:#fff5f5;border:1px solid #fca5a5;border-left:3px solid #ef4444;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background:#fff5f5;border:1px solid #fca5a5;border-left:3px solid #ef4444;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
            <ul style="margin:0;padding-left:16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STEP 1: Pilih Laporan Pengiriman --}}
    <div style="background:#fff;border:1px solid #ece8e3;border-radius:12px;padding:20px;margin-bottom:20px;">
        <h3 style="font-size:13px;font-weight:700;color:#1c1917;margin:0 0 14px;text-transform:uppercase;letter-spacing:0.05em;">
            1. Pilih Laporan Pengiriman
        </h3>
        <form method="GET" action="{{ route('sales.returns.create') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <label style="font-size:11.5px;font-weight:700;color:#78716c;display:block;margin-bottom:5px;">Laporan Pengiriman</label>
                <select name="delivery_report_id"
                        style="width:100%;padding:9px 12px;border:1px solid #d6d3d1;border-radius:8px;font-size:13px;background:#fff;color:#1c1917;">
                    <option value="">-- Pilih Laporan --</option>
                    @foreach($reports as $report)
                        <option value="{{ $report->id }}" {{ (isset($selectedReport) && $selectedReport->id == $report->id) ? 'selected' : '' }}>
                            {{ $report->report_number }} — {{ $report->toko_name }} ({{ $report->delivery_date->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    style="background:#92400e;color:#fff;border:none;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;">
                Pilih
            </button>
        </form>
    </div>

    {{-- STEP 2: Form Item Return (hanya muncul setelah laporan dipilih) --}}
    @if(isset($selectedReport) && $itemsWithMaxReturn->isNotEmpty())
    <form method="POST" action="{{ route('sales.returns.store') }}" id="returnForm">
        @csrf

        <input type="hidden" name="delivery_report_id" value="{{ $selectedReport->id }}">

        <div style="background:#fff;border:1px solid #ece8e3;border-radius:12px;padding:20px;margin-bottom:20px;">
            <h3 style="font-size:13px;font-weight:700;color:#1c1917;margin:0 0 14px;text-transform:uppercase;letter-spacing:0.05em;">
                2. Informasi Return
            </h3>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label style="font-size:11.5px;font-weight:700;color:#78716c;display:block;margin-bottom:5px;">Laporan Pengiriman</label>
                    <div style="padding:9px 12px;border:1px solid #e7e5e4;border-radius:8px;background:#fafaf8;font-size:13px;color:#57534e;">
                        {{ $selectedReport->report_number }} — {{ $selectedReport->toko_name }}
                    </div>
                </div>
                <div>
                    <label style="font-size:11.5px;font-weight:700;color:#78716c;display:block;margin-bottom:5px;">Tanggal Return <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" required
                           style="width:100%;padding:9px 12px;border:1px solid #d6d3d1;border-radius:8px;font-size:13px;box-sizing:border-box;">
                </div>
            </div>

            <div>
                <label style="font-size:11.5px;font-weight:700;color:#78716c;display:block;margin-bottom:5px;">Catatan Tambahan</label>
                <textarea name="note" rows="2" placeholder="Contoh: Barang dikembalikan karena kadaluarsa. Jika perlu proses ulang/packing ulang, tulis di sini."
                          style="width:100%;padding:9px 12px;border:1px solid #d6d3d1;border-radius:8px;font-size:13px;box-sizing:border-box;resize:vertical;">{{ old('note') }}</textarea>
                <p style="font-size:11px;color:#a8a29e;margin:4px 0 0;">Jika barang perlu proses ulang atau packing ulang, catat di sini. Proses fisiknya ditangani manual oleh gudang.</p>
            </div>
        </div>

        {{-- Tabel Item Return --}}
        <div style="background:#fff;border:1px solid #ece8e3;border-radius:12px;overflow:hidden;margin-bottom:20px;">
            <div style="padding:14px 18px;border-bottom:1px solid #ece8e3;background:#fafaf8;">
                <h3 style="font-size:13px;font-weight:700;color:#1c1917;margin:0;">3. Produk yang Dikembalikan</h3>
                <p style="font-size:12px;color:#a8a29e;margin:4px 0 0;">Kosongkan qty (0) untuk produk yang tidak direturn. Qty tidak boleh melebihi kolom "Maks Return".</p>
            </div>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#fafaf8;border-bottom:1px solid #ece8e3;">
                        <th style="padding:10px 18px;text-align:left;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Produk</th>
                        <th style="padding:10px 18px;text-align:center;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Qty Kirim</th>
                        <th style="padding:10px 18px;text-align:center;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Maks Return</th>
                        <th style="padding:10px 18px;text-align:right;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Harga/pcs</th>
                        <th style="padding:10px 18px;text-align:center;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Qty Return</th>
                        <th style="padding:10px 18px;text-align:left;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Alasan (Opsional)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itemsWithMaxReturn as $i => $item)
                    <tr style="border-bottom:1px solid #f5f0eb;">
                        <td style="padding:13px 18px;">
                            <input type="hidden" name="items[{{ $i }}][delivery_report_item_id]" value="{{ $item->id }}">
                            <div style="font-weight:600;color:#1c1917;font-size:13px;">{{ $item->product->name }}</div>
                            <div style="font-size:11px;color:#a8a29e;">{{ $item->product->weight }} Gram</div>
                        </td>
                        <td style="padding:13px 18px;text-align:center;font-size:13px;color:#57534e;">{{ number_format($item->qty, 0, ',', '.') }}</td>
                        <td style="padding:13px 18px;text-align:center;">
                            <span style="background:#fef3c7;color:#92400e;font-weight:700;font-size:12px;padding:2px 8px;border-radius:6px;">
                                Maks {{ number_format($item->max_return, 0, ',', '.') }} pcs
                            </span>
                        </td>
                        <td style="padding:13px 18px;text-align:right;font-size:13px;color:#57534e;">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td style="padding:13px 18px;text-align:center;">
                            <input type="number" name="items[{{ $i }}][qty_return]"
                                   value="{{ old('items.' . $i . '.qty_return', 0) }}"
                                   min="0" max="{{ $item->max_return }}"
                                   style="width:70px;padding:6px 8px;border:1px solid #d6d3d1;border-radius:6px;font-size:13px;text-align:center;">
                        </td>
                        <td style="padding:13px 18px;">
                            <input type="text" name="items[{{ $i }}][reason]"
                                   value="{{ old('items.' . $i . '.reason') }}"
                                   placeholder="Misal: tidak laku"
                                   style="width:100%;padding:6px 10px;border:1px solid #d6d3d1;border-radius:6px;font-size:12px;">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ route('sales.returns.index') }}"
               style="background:#fff;border:1px solid #d6d3d1;color:#57534e;text-decoration:none;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;">
                Batal
            </a>
            <button type="submit" id="submitBtn"
                    style="background:#92400e;color:#fff;border:none;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;">
                Ajukan Return
            </button>
        </div>
    </form>

    @elseif(isset($selectedReport) && $itemsWithMaxReturn->isEmpty())
        <div style="background:#fefce8;border:1px solid #fde047;border-radius:12px;padding:20px;text-align:center;">
            <div style="font-size:14px;font-weight:700;color:#854d0e;">Tidak ada item yang bisa direturn dari laporan ini.</div>
            <div style="font-size:12px;color:#a16207;margin-top:4px;">Semua item sudah direturn maksimal, atau belum ada item di laporan ini.</div>
        </div>
    @endif

    <script>
        // Cegah submit jika semua qty_return = 0
        document.getElementById('returnForm')?.addEventListener('submit', function(e) {
            const qtys = document.querySelectorAll('input[name*="[qty_return]"]');
            const hasQty = Array.from(qtys).some(input => parseInt(input.value) > 0);
            if (!hasQty) {
                e.preventDefault();
                alert('Minimal satu produk harus memiliki qty return lebih dari 0.');
            }
        });
    </script>
</x-layouts.user>

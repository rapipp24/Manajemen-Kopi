<x-layouts.user>
    <x-slot name="title">Kirim Setoran</x-slot>

    <style>
        .page-header { margin-bottom:24px; }
        .page-title  { font-size:20px;font-weight:700;color:#1c1917;letter-spacing:-0.03em; }
        .page-desc   { font-size:13px;color:#78716c;margin-top:3px; }

        .btn-secondary {
            background:#fff;border:1px solid #ece8e3;color:#44403c;padding:9px 16px;border-radius:9px;
            text-decoration:none;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;
            transition:background 0.15s;
        }
        .btn-secondary:hover { background:#fafaf8; }

        .form-card { background:#fff;border:1px solid #ece8e3;border-radius:12px;padding:24px;max-width:600px; }

        .form-group { margin-bottom:18px; }
        .form-label { display:block;font-size:12.5px;font-weight:700;color:#44403c;margin-bottom:6px; }
        .form-control {
            width:100%;padding:10px 14px;border:1px solid #d6d3d1;border-radius:8px;
            font-size:13.5px;color:#1c1917;background:#fff;transition:border-color 0.15s,box-shadow 0.15s;
        }
        .form-control:focus { border-color:#92400e;outline:none;box-shadow:0 0 0 3px rgba(146,64,14,0.15); }

        .alert-error {
            background:#fef2f2;border:1px solid #fecaca;color:#991b1b;
            padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:20px;
        }

        .summary-box {
            background:#fdf9f5;border:1px solid #f5ebe0;border-radius:10px;
            padding:16px;margin-top:14px;display:none;
        }
        .summary-row { display:flex;justify-content:space-between;margin-bottom:6px;font-size:13px; }
        .summary-row:last-child { margin-bottom:0;padding-top:6px;border-top:1px dashed #e7dcd0;font-weight:700; }

        .btn-submit {
            background:#92400e;color:#fff;border:none;padding:11px 24px;border-radius:9px;
            font-size:13.5px;font-weight:700;cursor:pointer;transition:background 0.15s;
            display:inline-flex;align-items:center;justify-content:center;width:100%;margin-top:8px;
        }
        .btn-submit:hover { background:#78350f; }
    </style>

    <div style="margin-bottom:16px;">
        <a href="{{ route('sales.deposits.index') }}" class="btn-secondary">
            ← Kembali ke Daftar
        </a>
    </div>

    <div class="page-header">
        <h1 class="page-title">Kirim Setoran Baru</h1>
        <p class="page-desc">Laporkan uang yang telah Anda kumpulkan dari toko.</p>
    </div>

    @if($errors->any() || session('error'))
        <div class="alert-error">
            @if(session('error'))
                {{ session('error') }}
            @else
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('sales.deposits.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="delivery_report_id">Pilih Laporan Pengiriman</label>
                <select name="delivery_report_id" id="delivery_report_id" class="form-control" required>
                    <option value="">-- Pilih Laporan --</option>
                    @foreach($reports as $r)
                        <option value="{{ $r->id }}" 
                                data-toko="{{ $r->toko_name }}"
                                data-total="{{ $r->total_amount }}"
                                data-paid="{{ $r->down_payment_amount }}"
                                data-remaining="{{ $r->remaining_amount }}"
                                {{ old('delivery_report_id', $selectedReportId) == $r->id ? 'selected' : '' }}>
                            {{ $r->report_number }} ({{ $r->toko_name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Detail Laporan Preview Box -->
            <div id="report_summary" class="summary-box">
                <div class="summary-row">
                    <span style="color:#78716c;">Toko Tujuan</span>
                    <span id="sum_toko" style="font-weight:600;color:#1c1917;">-</span>
                </div>
                <div class="summary-row">
                    <span style="color:#78716c;">Total Tagihan</span>
                    <span id="sum_total" style="color:#1c1917;">Rp 0</span>
                </div>
                <div class="summary-row">
                    <span style="color:#78716c;">Total Dibayar (Lunas/DP Terverifikasi)</span>
                    <span id="sum_paid" style="color:#166534;">Rp 0</span>
                </div>
                <div class="summary-row">
                    <span style="color:#92400e;">Sisa Tagihan Toko</span>
                    <span id="sum_remaining" style="color:#92400e;">Rp 0</span>
                </div>
            </div>

            <div class="form-group" style="margin-top:18px;">
                <label class="form-label" for="amount_display">Jumlah Setoran Uang (Rupiah)</label>
                <input type="text" id="amount_display" class="form-control" 
                       placeholder="Contoh: 50.000" required>
                <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}">
                <span style="font-size:11px;color:#78716c;margin-top:4px;display:block;">
                    *Nominal tidak boleh melebihi sisa tagihan toko.
                </span>
            </div>

            <div class="form-group">
                <label class="form-label" for="payment_date">Tanggal Penagihan / Penerimaan Uang</label>
                <input type="date" name="payment_date" id="payment_date" class="form-control" 
                       value="{{ old('payment_date', date('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="payment_method">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="Tunai" {{ old('payment_method') === 'Tunai' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="Transfer" {{ old('payment_method') === 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="note">Catatan Tambahan (Opsional)</label>
                <textarea name="note" id="note" class="form-control" rows="3" placeholder="Contoh: Pembayaran cicilan kedua dari toko.">{{ old('note') }}</textarea>
            </div>

            <button type="submit" class="btn-submit">
                Ajukan Setoran Sekarang →
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectEl = document.getElementById('delivery_report_id');
            const summaryBox = document.getElementById('report_summary');
            const sumToko = document.getElementById('sum_toko');
            const sumTotal = document.getElementById('sum_total');
            const sumPaid = document.getElementById('sum_paid');
            const sumRemaining = document.getElementById('sum_remaining');
            const amountInput = document.getElementById('amount');
            const amountDisplayInput = document.getElementById('amount_display');

            function formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
            }

            function updatePreview() {
                const selectedOpt = selectEl.options[selectEl.selectedIndex];
                if (!selectedOpt || selectedOpt.value === '') {
                    summaryBox.style.display = 'none';
                    amountInput.max = "";
                    return;
                }

                const toko = selectedOpt.getAttribute('data-toko');
                const total = parseFloat(selectedOpt.getAttribute('data-total')) || 0;
                const paid = parseFloat(selectedOpt.getAttribute('data-paid')) || 0;
                const remaining = parseFloat(selectedOpt.getAttribute('data-remaining')) || 0;

                sumToko.textContent = toko;
                sumTotal.textContent = formatRupiah(total);
                sumPaid.textContent = formatRupiah(paid);
                sumRemaining.textContent = formatRupiah(remaining);

                // Hapus validasi HTML max, kita handle di backend atau JS opsional
                // amountInput.max = remaining;

                summaryBox.style.display = 'block';
            }

            // Inisialisasi display value jika ada old() value
            if (amountInput.value) {
                amountDisplayInput.value = formatRupiah(amountInput.value).replace('Rp ', '');
            }

            amountDisplayInput.addEventListener('input', function(e) {
                let cleaned = this.value.replace(/\D/g, '');
                if (cleaned) {
                    let numericValue = parseInt(cleaned, 10);
                    this.value = formatRupiah(numericValue).replace('Rp ', '');
                    amountInput.value = numericValue;
                } else {
                    this.value = '';
                    amountInput.value = '';
                }
            });

            selectEl.addEventListener('change', updatePreview);
            // Run on load in case of validation back with input
            updatePreview();
        });
    </script>
</x-layouts.user>

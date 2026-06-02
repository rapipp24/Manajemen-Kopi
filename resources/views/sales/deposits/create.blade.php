<x-layouts.user>
    <x-slot name="title">Kirim Setoran</x-slot>

    <style>
        .page-header { margin-bottom:24px; }
        .page-title  { font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.02em; }
        .page-desc   { font-size:13.5px;color:var(--muted);margin-top:4px; }

        .form-card { background:#fff;border:1px solid var(--border);border-radius:12px;padding:24px;max-width:600px;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01); }

        .form-group { margin-bottom:18px; }
        .form-label { display:block;font-size:12.5px;font-weight:700;color:var(--text);margin-bottom:6px; }
        .form-control {
            width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;
            font-size:13.5px;color:var(--text);background:#fff;transition:border-color 0.15s,box-shadow 0.15s;
        }
        .form-control:focus { border-color:var(--accent);outline:none;box-shadow:0 0 0 3px rgba(197, 160, 89, 0.15); }

        .summary-box {
            background:var(--cream);border:1px solid var(--border);border-radius:10px;
            padding:16px;margin-top:14px;display:none;
        }
        .summary-row { display:flex;justify-content:space-between;margin-bottom:6px;font-size:13px; }
        .summary-row:last-child { margin-bottom:0;padding-top:8px;border-top:1px dashed var(--border);font-weight:700; }

        /* Custom File Upload Styling (Camera PWA Friendly) */
        .custom-file-upload {
            position: relative;
            border: 2px dashed var(--border);
            border-radius: 8px;
            background: var(--cream);
            padding: 20px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        .custom-file-upload:hover {
            border-color: var(--accent);
        }
        .custom-file-upload input[type="file"] {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .upload-icon {
            width: 28px; height: 28px; color: var(--accent); margin-bottom: 6px;
        }
        .upload-text {
            display: block; font-size: 13px; font-weight: 700; color: var(--text);
        }
        .upload-info {
            display: block; font-size: 11px; color: var(--muted); margin-top: 2px;
        }

        .btn-submit {
            background:var(--brown);color:#fff;border:none;padding:12px 24px;border-radius:8px;
            font-size:14px;font-weight:700;cursor:pointer;transition:background 0.15s;
            display:inline-flex;align-items:center;justify-content:center;width:100%;margin-top:8px;
            box-shadow: 0 2px 4px rgba(42, 23, 14, 0.1);
        }
        .btn-submit:hover { background:var(--brown-hover); }
    </style>

    <a href="{{ route('sales.deposits.index') }}" class="sales-back-link">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali ke Daftar
    </a>

    <div class="page-header">
        <h1 class="page-title">Kirim Setoran Baru</h1>
        <p class="page-desc">Laporkan uang yang telah Anda kumpulkan dari toko.</p>
    </div>

    @if($errors->any() || session('error'))
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:20px;">
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
        <form action="{{ route('sales.deposits.store') }}" method="POST" enctype="multipart/form-data">
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
                    <span style="color:var(--muted);">Toko Tujuan</span>
                    <span id="sum_toko" style="font-weight:600;color:var(--text);">-</span>
                </div>
                <div class="summary-row">
                    <span style="color:var(--muted);">Total Tagihan</span>
                    <span id="sum_total" style="color:var(--text);">Rp 0</span>
                </div>
                <div class="summary-row">
                    <span style="color:var(--muted);">Total Dibayar (DP/Setoran Lunas)</span>
                    <span id="sum_paid" style="color:#166534; font-weight:600;">Rp 0</span>
                </div>
                <div class="summary-row">
                    <span style="color:var(--text);">Sisa Tagihan Toko</span>
                    <span id="sum_remaining" style="color:var(--brown); font-weight:700;">Rp 0</span>
                </div>
            </div>

            <div class="form-group" style="margin-top:18px;">
                <label class="form-label" for="amount_display">Jumlah Setoran Uang (Rupiah)</label>
                <input type="text" id="amount_display" class="form-control" 
                       placeholder="Contoh: 50.000" required>
                <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}">
                <span style="font-size:11px;color:var(--muted);margin-top:4px;display:block;">
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

            <div class="form-group" id="proof_upload_group" style="display:none;">
                <label class="form-label" for="payment_proof">Upload / Foto Bukti Pembayaran <span style="color:#dc2626;">*</span></label>
                
                <div class="custom-file-upload">
                    <input type="file" name="payment_proof" id="payment_proof" accept="image/*,.pdf" capture="environment">
                    <div id="upload_area">
                        <i data-lucide="camera" class="upload-icon"></i>
                        <span class="upload-text">Ambil Foto Bukti atau Pilih File</span>
                        <span class="upload-info">JPG, PNG, WEBP, atau PDF (Maks. 2MB)</span>
                    </div>
                </div>

                <span style="font-size:11.5px;color:var(--muted);margin-top:6px;display:block;">
                    Untuk transfer bank, foto bukti pembayaran atau upload file bukti transfer.
                </span>
            </div>

            <div class="form-group">
                <label class="form-label" for="note">Catatan Tambahan (Opsional)</label>
                <textarea name="note" id="note" class="form-control" rows="3" placeholder="Contoh: Pembayaran cicilan kedua dari toko.">{{ old('note') }}</textarea>
            </div>

            <button type="submit" class="btn-submit">
                Ajukan Setoran Sekarang <i data-lucide="chevron-right" style="width:16px;height:16px;margin-left:4px;"></i>
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

            const methodSelect = document.getElementById('payment_method');
            const proofGroup = document.getElementById('proof_upload_group');
            const proofInput = document.getElementById('payment_proof');
            const uploadArea = document.getElementById('upload_area');

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

                summaryBox.style.display = 'block';
            }

            function toggleProofField() {
                if (methodSelect.value === 'Transfer') {
                    proofGroup.style.display = 'block';
                    proofInput.setAttribute('required', 'required');
                } else {
                    proofGroup.style.display = 'none';
                    proofInput.removeAttribute('required');
                    proofInput.value = ''; // Reset value
                    uploadArea.innerHTML = `
                        <i data-lucide="camera" class="upload-icon"></i>
                        <span class="upload-text">Ambil Foto Bukti atau Pilih File</span>
                        <span class="upload-info">JPG, PNG, WEBP, atau PDF (Maks. 2MB)</span>
                    `;
                    lucide.createIcons();
                }
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

            proofInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    uploadArea.innerHTML = `
                        <i data-lucide="check-circle" class="upload-icon" style="color:#166534;"></i>
                        <span class="upload-text" style="color:#166534;">File Terpilih</span>
                        <span class="upload-info" style="font-weight:600;color:var(--text);">${this.files[0].name}</span>
                    `;
                    lucide.createIcons();
                }
            });

            selectEl.addEventListener('change', updatePreview);
            methodSelect.addEventListener('change', toggleProofField);

            // Run on load in case of validation back with input
            updatePreview();
            toggleProofField();
        });
    </script>
</x-layouts.user>


<x-layouts.admin>
    <x-slot name="title">Verifikasi Setoran - Admin</x-slot>

    <div style="margin-bottom:16px;">
        <a href="{{ route('admin.sales-deposits.index') }}" 
           style="background:#fff;border:1px solid #cbd5e1;color:#475569;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            ← Kembali ke Daftar
        </a>
    </div>

    <div style="margin-bottom:24px;">
        <h1 style="font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.03em;">Detail Verifikasi Setoran</h1>
    </div>

    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;padding:12px 16px;border-radius:8px;font-size:13.5px;margin-bottom:20px;font-weight:500;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">
        <!-- Left Side: Deposit Details -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
            <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;border-bottom:1px solid #f1f5f9;padding-bottom:10px;">Informasi Setoran</h3>
            
            <div style="display:flex;justify-content:space-between;padding:11px 0;border-bottom:1px solid #f8fafc;">
                <span style="color:#64748b;font-size:13px;">No. Setoran</span>
                <span style="font-family:monospace;font-weight:700;color:#92400e;font-size:13px;">{{ $deposit->deposit_number }}</span>
            </div>
            
            <div style="display:flex;justify-content:space-between;padding:11px 0;border-bottom:1px solid #f8fafc;">
                <span style="color:#64748b;font-size:13px;">Sales Lapangan</span>
                <span style="font-weight:600;color:#0f172a;font-size:13px;">{{ $deposit->sales->name }}</span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:11px 0;border-bottom:1px solid #f8fafc;">
                <span style="color:#64748b;font-size:13px;">Laporan Pengiriman</span>
                <span style="font-family:monospace;font-weight:600;color:#0f172a;font-size:13px;">
                    <a href="{{ route('admin.delivery-reports.show', $deposit->delivery_report_id) }}" style="color:#92400e;text-decoration:none;">
                        {{ $deposit->deliveryReport->report_number }}
                    </a>
                </span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:11px 0;border-bottom:1px solid #f8fafc;">
                <span style="color:#64748b;font-size:13px;">Toko Tujuan</span>
                <span style="font-weight:600;color:#0f172a;font-size:13px;">{{ $deposit->deliveryReport->toko_name }}</span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:11px 0;border-bottom:1px solid #f8fafc;">
                <span style="color:#64748b;font-size:13px;">Nominal Setoran</span>
                <span style="font-weight:800;color:#166534;font-size:16px;">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:11px 0;border-bottom:1px solid #f8fafc;">
                <span style="color:#64748b;font-size:13px;">Tanggal Setor</span>
                <span style="font-weight:600;color:#0f172a;font-size:13px;">{{ $deposit->payment_date->format('d M Y') }}</span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:11px 0;border-bottom:1px solid #f8fafc;">
                <span style="color:#64748b;font-size:13px;">Metode Bayar</span>
                <span style="font-weight:600;color:#0f172a;font-size:13px;">{{ $deposit->payment_method }}</span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:11px 0;border-bottom:1px solid #f8fafc;">
                <span style="color:#64748b;font-size:13px;">Catatan Sales</span>
                <span style="color:#475569;font-size:13px;max-width:250px;text-align:right;word-break:break-word;">{{ $deposit->note ?? '—' }}</span>
            </div>

            <div style="display:flex;flex-direction:column;padding:11px 0;border-bottom:1px solid #f8fafc;gap:8px;">
                <span style="color:#64748b;font-size:13px;">Bukti Pembayaran</span>
                <span style="font-size:13px;font-weight:600;color:#0f172a;width:100%;">
                    @if($deposit->payment_proof_path)
                        @php
                            $fileExtension = strtolower(pathinfo($deposit->payment_proof_path, PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp']))
                            <div style="margin-top: 4px;">
                                <img src="{{ Storage::url($deposit->payment_proof_path) }}" alt="Bukti Pembayaran" style="max-width: 180px; max-height: 180px; border-radius: 8px; border: 1px solid #e2e8f0; display: block; margin-bottom: 8px;">
                                <a href="{{ Storage::url($deposit->payment_proof_path) }}" target="_blank" 
                                   style="background:#fff;border:1px solid #cbd5e1;color:#475569;padding:6px 12px;border-radius:6px;font-size:11.5px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                    Lihat Bukti Pembayaran
                                </a>
                            </div>
                        @else
                            <div style="margin-top: 4px;">
                                <a href="{{ Storage::url($deposit->payment_proof_path) }}" target="_blank" 
                                   style="background:#fff;border:1px solid #cbd5e1;color:#475569;padding:8px 14px;border-radius:6px;font-size:12.5px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                    Lihat Bukti Pembayaran (PDF)
                                </a>
                            </div>
                        @endif
                    @else
                        <span style="color:#64748b;font-style:italic;font-weight:normal;">Tidak ada bukti pembayaran</span>
                    @endif
                </span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:11px 0;">
                <span style="color:#64748b;font-size:13px;">Status</span>
                <span>
                    @if($deposit->status === 'disetujui')
                        <span style="background:#dcfce7;color:#166534;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">DISETUJUI</span>
                    @elseif($deposit->status === 'ditolak')
                        <span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">DITOLAK</span>
                    @else
                        <span style="background:#fef08a;color:#854d0e;font-size:11px;font-weight:700;padding:4px 8px;border-radius:6px;">MENUNGGU VERIFIKASI</span>
                    @endif
                </span>
            </div>
        </div>

        <!-- Right Side: Verification Actions / Verifier Details -->
        <div>
            @if($deposit->status === 'menunggu_verifikasi')
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,0.02);margin-bottom:20px;">
                    <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;border-bottom:1px solid #f1f5f9;padding-bottom:10px;">Aksi Verifikasi</h3>
                    
                    <div style="background:#fdf9f5;border:1px solid #fed7aa;border-radius:8px;padding:14px;margin-bottom:20px;font-size:12.5px;color:#ea580c;font-weight:500;">
                        Sebelum menyetujui, pastikan nominal <strong>Rp {{ number_format($deposit->amount, 0, ',', '.') }}</strong> telah masuk ke kas/mutasi bank secara fisik.
                    </div>

                    <!-- Approve Action -->
                    <form id="approveForm" action="{{ route('admin.sales-deposits.approve', $deposit) }}" method="POST" style="margin-bottom:24px;">
                        @csrf
                        <button type="button" 
                                onclick="openModal()"
                                style="background:#166534;color:#fff;border:none;padding:12px 20px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;width:100%;text-align:center;box-shadow:0 2px 4px rgba(22,101,52,0.2);">
                            ✓ Setujui Setoran
                        </button>
                    </form>

                    <div style="border-top:1px dashed #cbd5e1;padding-top:20px;">
                        <h4 style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:12px;">Tolak Setoran</h4>
                        
                        <!-- Reject Action -->
                        <form action="{{ route('admin.sales-deposits.reject', $deposit) }}" method="POST">
                            @csrf
                            <div style="margin-bottom:12px;">
                                <label style="font-size:11.5px;font-weight:700;color:#475569;display:block;margin-bottom:6px;">Alasan Penolakan</label>
                                <textarea name="rejection_reason" style="width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;color:#0f172a;" rows="3" placeholder="Sebutkan alasan penolakan, misal: Uang mutasi bank belum masuk." required></textarea>
                            </div>
                            <button type="submit" 
                                    style="background:#991b1b;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;width:100%;text-align:center;">
                                ✕ Tolak Setoran
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
                    <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;border-bottom:1px solid #e2e8f0;padding-bottom:10px;">Status Riwayat Verifikasi</h3>
                    
                    @if($deposit->status === 'disetujui')
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;padding:12px;border-radius:8px;font-size:13px;margin-bottom:16px;font-weight:500;">
                            ✓ Setoran telah disetujui dan piutang delivery report terpotong otomatis.
                        </div>
                    @elseif($deposit->status === 'ditolak')
                        <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:12px;border-radius:8px;font-size:13px;margin-bottom:16px;font-weight:500;">
                            ✕ Setoran ditolak. Tidak mempengaruhi piutang laporan.
                        </div>
                        <div style="margin-bottom:16px;">
                            <span style="font-size:12px;color:#64748b;display:block;margin-bottom:4px;">Alasan Penolakan:</span>
                            <span style="font-size:13px;font-weight:600;color:#0f172a;">{{ $deposit->rejection_reason }}</span>
                        </div>
                    @endif

                    @if($deposit->verifier)
                        <div style="display:flex;flex-direction:column;gap:4px;">
                            <span style="font-size:12px;color:#64748b;">Diverifikasi oleh:</span>
                            <span style="font-size:13px;font-weight:700;color:#0f172a;">{{ $deposit->verifier->name }}</span>
                            <span style="font-size:11px;color:#94a3b8;">Pada {{ $deposit->verified_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Custom Modal Confirm -->
    <div id="confirmModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:12px;padding:24px;max-width:400px;width:90%;box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;color:#166534;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;">Konfirmasi Setoran</h3>
            </div>
            <p style="font-size:14px;color:#475569;margin-bottom:24px;line-height:1.5;">
                Apakah Anda yakin dana sebesar <strong style="color:#166534;">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</strong> sudah masuk secara fisik dan valid? Tindakan ini akan memotong piutang toko.
            </p>
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" onclick="closeModal()" style="background:#fff;border:1px solid #cbd5e1;color:#475569;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:background 0.15s;">
                    Batal
                </button>
                <button type="button" onclick="submitApproveForm()" style="background:#166534;color:#fff;border:none;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:background 0.15s;">
                    Ya, Setujui
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('confirmModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }
        function submitApproveForm() {
            document.getElementById('approveForm').submit();
        }
    </script>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot name="title">Pengaturan Sistem</x-slot>

    <style>
        /* ── Container & Hero ── */
        .settings-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
            padding-bottom: 50px;
        }

        .settings-hero {
            background: #fffdfa;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
        }

        .settings-hero h1 {
            font-size: 22px;
            font-weight: 800;
            color: #2c1a0e;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }

        .settings-hero p {
            font-size: 13.5px;
            color: #64748b;
            font-weight: 500;
            line-height: 1.5;
            margin: 0;
        }

        /* ── Modern Layout Grid ── */
        .settings-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 28px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .settings-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        /* ── Sidebar Navigation ── */
        .settings-sidebar {
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px;
        }

        @media (max-width: 1024px) {
            .settings-sidebar {
                flex-direction: row;
                flex-wrap: wrap;
            }
        }

        .settings-tab-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            background: transparent;
            border: 1px solid transparent;
            color: #475569;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s;
            width: 100%;
            outline: none;
        }

        @media (max-width: 1024px) {
            .settings-tab-btn {
                width: auto;
                flex: 1 1 calc(33.333% - 10px);
                min-width: 200px;
            }
        }

        @media (max-width: 768px) {
            .settings-tab-btn {
                flex: 1 1 100%;
            }
        }

        .settings-tab-btn:hover {
            background: #fafaf9;
            color: #92400e;
        }

        .settings-tab-btn.active {
            background: #92400e;
            color: white;
        }

        .tab-btn-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            flex-shrink: 0;
        }

        .settings-tab-btn.active .tab-btn-icon {
            color: white;
        }

        .tab-btn-icon svg {
            width: 16px;
            height: 16px;
        }

        .tab-btn-text {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .tab-title {
            font-size: 13px;
            font-weight: 700;
        }

        .tab-desc {
            font-size: 10.5px;
            color: #94a3b8;
            font-weight: 500;
        }

        .settings-tab-btn.active .tab-desc {
            color: rgba(255, 255, 255, 0.7);
        }

        /* ── Two-Column Pane Grid (Form & Preview) ── */
        .pane-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .pane-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        /* ── Card Styles ── */
        .settings-content-card {
            background: white;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .settings-card-header {
            padding: 16px 20px;
            background: #fafaf9;
            border-bottom: 1px solid #e2e8f0;
        }

        .settings-card-title {
            font-size: 14px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .settings-card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* ── Live Preview Area ── */
        .preview-sticky {
            position: sticky;
            top: 20px;
        }

        .preview-card {
            background: #fafaf9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }

        .preview-card-header {
            font-size: 11px;
            font-weight: 800;
            color: #78350f;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        /* Clean Profile Preview Box */
        .preview-profile-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .preview-avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f5f5f4;
            border: 1.5px solid #e7e5e4;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #78350f;
            font-weight: 800;
            font-size: 12px;
        }

        .preview-row {
            display: flex;
            flex-direction: column;
            gap: 2px;
            font-size: 11px;
        }

        .preview-row-label {
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .preview-row-val {
            color: #475569;
            line-height: 1.4;
        }

        /* Thermal Receipt Style Preview */
        .preview-receipt-paper {
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            padding: 14px;
            font-family: monospace, Courier, monospace;
            font-size: 10.5px;
            color: #1e293b;
            line-height: 1.4;
        }

        /* PDF Official Letterhead Style Preview */
        .preview-pdf-paper {
            background: white;
            border: 1px solid #e2e8f0;
            border-top: 4px solid #78350f;
            border-radius: 6px;
            padding: 14px;
            font-size: 10.5px;
            color: #334155;
            line-height: 1.4;
        }

        /* ── Form Inputs ── */
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media (max-width: 640px) {
            .form-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-label {
            font-size: 12.5px;
            font-weight: 700;
            color: #334155;
        }

        .form-label span {
            color: #ef4444;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #cbd5e1;
            background: white;
            border-radius: 8px;
            font-size: 13px;
            color: #1e293b;
            transition: all 0.15s;
            outline: none;
        }

        .form-control:focus {
            border-color: #92400e;
            box-shadow: 0 0 0 3px rgba(146, 64, 14, 0.05);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .helper-text {
            font-size: 11px;
            color: #64748b;
            margin-top: 1px;
            line-height: 1.4;
        }

        .error-message {
            color: #dc2626;
            font-size: 11px;
            font-weight: 600;
            margin-top: 2px;
        }

        /* ── Informational Tip Banner ── */
        .tip-banner {
            background: #fafaf9;
            border: 1px solid #e7e5e4;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 11.5px;
            color: #57534e;
            line-height: 1.4;
        }

        /* ── Tab Panes ── */
        .settings-pane {
            display: none;
        }

        .settings-pane.active {
            display: block;
        }

        /* ── Submit Button Footer ── */
        .btn-submit-settings {
            background: #92400e;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-submit-settings:hover {
            background: #78350f;
        }
    </style>

    <div class="settings-container">
        <!-- Hero Header -->
        <div class="settings-hero">
            <h1>Pengaturan Sistem</h1>
            <p>Kelola identitas usaha, tampilan cetakan nota penjualan, dan format kop laporan PDF yang digunakan di seluruh aplikasi.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <div class="settings-layout">
                <!-- Kiri: Tab Buttons -->
                <div class="settings-sidebar">
                    <button type="button" class="settings-tab-btn active" data-target="pane-identity">
                        <div class="tab-btn-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div class="tab-btn-text">
                            <span class="tab-title">Identitas Usaha</span>
                            <span class="tab-desc">Nama, kontak, alamat</span>
                        </div>
                    </button>
                    <button type="button" class="settings-tab-btn" data-target="pane-receipt">
                        <div class="tab-btn-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="tab-btn-text">
                            <span class="tab-title">Nota Penjualan</span>
                            <span class="tab-desc">Judul nota & tanda tangan</span>
                        </div>
                    </button>
                    <button type="button" class="settings-tab-btn" data-target="pane-report">
                        <div class="tab-btn-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="tab-btn-text">
                            <span class="tab-title">Laporan PDF</span>
                            <span class="tab-desc">Kop surat & label laporan</span>
                        </div>
                    </button>
                </div>

                <!-- Kanan: Card Content Form & Live Previews -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    
                    <!-- 1. IDENTITAS PANE -->
                    <div id="pane-identity" class="settings-pane active">
                        <div class="pane-grid">
                            <div class="settings-content-card">
                                <div class="settings-card-header">
                                    <h3 class="settings-card-title">Identitas Usaha</h3>
                                </div>
                                <div class="settings-card-body">
                                    <div class="tip-banner">
                                        Perubahan disimpan akan langsung digunakan pada cetakan berikutnya di seluruh sistem.
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <label class="form-label">Nama Usaha / Perusahaan <span>*</span></label>
                                            <input type="text" name="shop_name" value="{{ old('shop_name', $settings['shop_name']) }}" class="form-control" placeholder="Kopi Elang Emas" required>
                                            <span class="helper-text">Nama ini akan muncul di sidebar utama dan dokumen cetak.</span>
                                            @error('shop_name') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Tagline Usaha</label>
                                            <input type="text" name="shop_tagline" value="{{ old('shop_tagline', $settings['shop_tagline']) }}" class="form-control" placeholder="Panel Manajemen">
                                            <span class="helper-text">Motto singkat yang muncul di bawah nama brand pada sidebar.</span>
                                            @error('shop_tagline') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <label class="form-label">Nomor Telepon / WhatsApp <span>*</span></label>
                                            <input type="text" name="shop_phone" value="{{ old('shop_phone', $settings['shop_phone']) }}" class="form-control" placeholder="0812-3456-7890" required>
                                            <span class="helper-text">Nomor kontak resmi kedai untuk dicantumkan di nota penjualan.</span>
                                            @error('shop_phone') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Email Usaha <span>*</span></label>
                                            <input type="email" name="shop_email" value="{{ old('shop_email', $settings['shop_email']) }}" class="form-control" placeholder="hello@elangemas.com" required>
                                            <span class="helper-text">Alamat email korespondensi resmi kedai/gudang kopi.</span>
                                            @error('shop_email') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Alamat Lengkap Usaha <span>*</span></label>
                                        <textarea name="shop_address" rows="3" class="form-control" style="font-family: inherit; resize: vertical;" placeholder="Tuliskan alamat lengkap operasional usaha..." required>{{ old('shop_address', $settings['shop_address']) }}</textarea>
                                        <span class="helper-text">Alamat fisik operasional yang akan tercetak di bagian atas lembar nota.</span>
                                        @error('shop_address') <span class="error-message">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Live Preview Identity -->
                            <div class="preview-sticky">
                                <div class="preview-card">
                                    <div class="preview-card-header">
                                        Preview Identitas Usaha
                                    </div>
                                    <div class="preview-profile-box">
                                        <div style="display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                                            <div class="preview-avatar-circle">
                                                <span class="preview-avatar-initials">KE</span>
                                            </div>
                                            <div>
                                                <div class="preview-row-label">Nama Usaha</div>
                                                <h4 class="preview-shop-name" style="font-size: 13.5px; font-weight: 800; color: #1e293b; margin: 0;">-</h4>
                                            </div>
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 10px;">
                                            <div class="preview-row">
                                                <span class="preview-row-label">Tagline</span>
                                                <span class="preview-shop-tagline preview-row-val">-</span>
                                            </div>
                                            <div class="preview-row">
                                                <span class="preview-row-label">Kontak</span>
                                                <span class="preview-row-val">
                                                    <span class="preview-shop-phone"></span><br>
                                                    <span class="preview-shop-email" style="word-break: break-all;"></span>
                                                </span>
                                            </div>
                                            <div class="preview-row">
                                                <span class="preview-row-label">Alamat</span>
                                                <span class="preview-shop-address preview-row-val">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. NOTA PENJUALAN PANE -->
                    <div id="pane-receipt" class="settings-pane">
                        <div class="pane-grid">
                            <div class="settings-content-card">
                                <div class="settings-card-header">
                                    <h3 class="settings-card-title">Pengaturan Nota Penjualan</h3>
                                </div>
                                <div class="settings-card-body">
                                    <div class="tip-banner">
                                        Tidak mengubah data transaksi lama, hanya memengaruhi tampilan cetak nota setelah disimpan.
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <label class="form-label">Judul Dokumen Nota</label>
                                            <input type="text" name="receipt_title" value="{{ old('receipt_title', $settings['receipt_title']) }}" class="form-control" placeholder="INVOICE PENJUALAN">
                                            <span class="helper-text">Judul transaksi yang akan dicetak di bagian kanan atas nota.</span>
                                            @error('receipt_title') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Teks Terima Kasih</label>
                                            <input type="text" name="receipt_thank_you_text" value="{{ old('receipt_thank_you_text', $settings['receipt_thank_you_text']) }}" class="form-control" placeholder="Terima kasih atas kunjungan Anda!">
                                            <span class="helper-text">Pesan penutup ramah untuk dicantumkan di bagian bawah nota.</span>
                                            @error('receipt_thank_you_text') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Catatan Kaki Nota (Syarat & Ketentuan) <span>*</span></label>
                                        <textarea name="footer_note" rows="3" class="form-control" style="font-family: inherit; resize: vertical;" placeholder="Tuliskan catatan kaki nota..." required>{{ old('footer_note', $settings['footer_note']) }}</textarea>
                                        <span class="helper-text">Catatan ini muncul di bagian bawah nota (garansi, retur, dll).</span>
                                        @error('footer_note') <span class="error-message">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-grid-2" style="margin-top: 6px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                                        <div class="form-group">
                                            <label class="form-label">Tanda Tangan Kiri: Label Penerima</label>
                                            <input type="text" name="receipt_left_signature_label" value="{{ old('receipt_left_signature_label', $settings['receipt_left_signature_label'] ?? '') }}" class="form-control" placeholder="Penerima / Member">
                                            <span class="helper-text">Label tanda tangan di kolom kiri bawah nota.</span>
                                            @error('receipt_left_signature_label') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Tanda Tangan Kanan: Label Pengirim</label>
                                            <input type="text" name="receipt_right_signature_label" value="{{ old('receipt_right_signature_label', $settings['receipt_right_signature_label'] ?? '') }}" class="form-control" placeholder="Hormat Kami,">
                                            <span class="helper-text">Label tanda tangan di kolom kanan bawah nota.</span>
                                            @error('receipt_right_signature_label') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <!-- Spacer -->
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Tanda Tangan Kanan: Nama Penandatangan</label>
                                            <input type="text" name="receipt_right_signature_name" value="{{ old('receipt_right_signature_name', $settings['receipt_right_signature_name'] ?? '') }}" class="form-control" placeholder="Administrator">
                                            <span class="helper-text">Nama penanggung jawab di kolom kanan bawah nota.</span>
                                            @error('receipt_right_signature_name') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Preview Receipt -->
                            <div class="preview-sticky">
                                <div class="preview-card">
                                    <div class="preview-card-header">
                                        Preview Nota Penjualan
                                    </div>
                                    <div class="preview-receipt-paper">
                                        <div style="text-align: center; font-weight: bold;" class="preview-shop-name">MANAJEMEN KOPI</div>
                                        <div style="text-align: center;" class="preview-shop-address">Alamat Kedai Kopi</div>
                                        <div style="text-align: center;">Telp: <span class="preview-shop-phone">-</span></div>
                                        <div style="margin: 6px 0;">--------------------------------</div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span>28 Mei 2026</span>
                                            <span style="font-weight: bold;" class="preview-receipt-title">INVOICE PENJUALAN</span>
                                        </div>
                                        <div style="margin: 6px 0;">--------------------------------</div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span>Kopi Premium 250g</span>
                                            <span>1 x Rp 35.000</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; font-weight: bold;">
                                            <span>TOTAL</span>
                                            <span>Rp 35.000</span>
                                        </div>
                                        <div style="margin: 6px 0;">--------------------------------</div>
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; margin-top: 8px; min-height: 44px;">
                                            <div>
                                                <div class="preview-receipt-left-sig">-</div>
                                                <div style="margin-top: 20px;">(................)</div>
                                            </div>
                                            <div style="text-align: right;">
                                                <div class="preview-receipt-right-sig">-</div>
                                                <div style="margin-top: 20px; font-weight: bold;" class="preview-receipt-right-sig-name">-</div>
                                            </div>
                                        </div>
                                        <div style="margin: 6px 0;">--------------------------------</div>
                                        <div style="text-align: center; font-style: italic;" class="preview-receipt-thank-you">-</div>
                                        <div style="margin-top: 6px; font-size: 9px; color: #64748b;" class="preview-receipt-footer-note">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. LAPORAN PDF PANE -->
                    <div id="pane-report" class="settings-pane">
                        <div class="pane-grid">
                            <div class="settings-content-card">
                                <div class="settings-card-header">
                                    <h3 class="settings-card-title">Pengaturan Laporan PDF</h3>
                                </div>
                                <div class="settings-card-body">
                                    <div class="tip-banner">
                                        Perubahan disimpan akan langsung digunakan sebagai kop surat & label tanda tangan PDF berikutnya.
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <label class="form-label">Kop Surat Laporan</label>
                                            <input type="text" name="report_header_name" value="{{ old('report_header_name', $settings['report_header_name']) }}" class="form-control" placeholder="Kopi Elang Emas">
                                            <span class="helper-text">Nama instansi yang dicetak tebal di bagian kiri atas laporan PDF.</span>
                                            @error('report_header_name') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Sub-judul Kop Laporan</label>
                                            <input type="text" name="report_subtitle" value="{{ old('report_subtitle', $settings['report_subtitle']) }}" class="form-control" placeholder="Manajemen Kopi & Produksi Terintegrasi">
                                            <span class="helper-text">Keterangan penjelas di bawah nama kop surat laporan PDF.</span>
                                            @error('report_subtitle') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <label class="form-label">Label Tanda Tangan: Pembuat</label>
                                            <input type="text" name="report_prepared_by_label" value="{{ old('report_prepared_by_label', $settings['report_prepared_by_label']) }}" class="form-control" placeholder="Staf Administrasi">
                                            <span class="helper-text">Label ini muncul di area tanda tangan pembuat laporan PDF.</span>
                                            @error('report_prepared_by_label') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Label Tanda Tangan: Penyetuju</label>
                                            <input type="text" name="report_approved_by_label" value="{{ old('report_approved_by_label', $settings['report_approved_by_label']) }}" class="form-control" placeholder="Pemilik Gudang / Owner">
                                            <span class="helper-text">Label ini muncul di area tanda tangan penyetuju laporan PDF.</span>
                                            @error('report_approved_by_label') <span class="error-message">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Catatan Tambahan Laporan</label>
                                        <textarea name="report_footer_note" rows="2" class="form-control" style="font-family: inherit; resize: vertical;" placeholder="Tuliskan catatan tambahan laporan (jika ada)...">{{ old('report_footer_note', $settings['report_footer_note']) }}</textarea>
                                        <span class="helper-text">Catatan penjelas tambahan di bawah footer tabel laporan (opsional).</span>
                                        @error('report_footer_note') <span class="error-message">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Live Preview Report -->
                            <div class="preview-sticky">
                                <div class="preview-card">
                                    <div class="preview-card-header">
                                        Preview Laporan PDF
                                    </div>
                                    <div class="preview-pdf-paper">
                                        <div style="border-bottom: 2px solid #334155; padding-bottom: 6px; margin-bottom: 8px;">
                                            <h4 class="preview-report-header-name" style="font-size: 12px; font-weight: 850; color: #1e293b; margin: 0;">-</h4>
                                            <p class="preview-report-subtitle" style="font-size: 9px; color: #64748b; margin: 2px 0 0 0; font-weight: 500;">-</p>
                                        </div>
                                        <div style="font-size: 8px; font-weight: bold; text-transform: uppercase; color: #475569; margin-bottom: 4px;">LAPORAN STOK REALTIME</div>
                                        <div style="background: #f1f5f9; padding: 4px; font-size: 8px; border-radius: 2px; color: #64748b; text-align: center; margin-bottom: 12px;">[ Tabel Transaksi Laporan ]</div>
                                        <div class="preview-report-footer-note" style="font-size: 8px; color: #64748b; font-style: italic; margin-bottom: 12px;">-</div>
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; font-size: 8.5px; border-top: 1px dashed #cbd5e1; padding-top: 8px; min-height: 44px;">
                                            <div>
                                                <div class="preview-report-prepared-by">-</div>
                                                <div style="margin-top: 20px; color: #94a3b8;">( Tanda Tangan )</div>
                                            </div>
                                            <div style="text-align: right;">
                                                <div class="preview-report-approved-by">-</div>
                                                <div style="margin-top: 20px; color: #94a3b8;">( Tanda Tangan )</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shared Action Footer Button -->
                    <div class="settings-content-card" style="border-radius: 12px;">
                        <div style="padding: 14px 20px; background: #fafaf9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">
                                Periksa kembali data sebelum menyimpan perubahan.
                            </span>
                            <button type="submit" class="btn-submit-settings">Simpan Pengaturan</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- Frontend Tab Switching, Persistence & Dynamic Real-time Previews -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ── Tab Switching Logic ──
            const buttons = document.querySelectorAll('.settings-tab-btn');
            const panes = document.querySelectorAll('.settings-pane');

            function switchTab(targetId) {
                buttons.forEach(btn => {
                    if (btn.getAttribute('data-target') === targetId) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });

                panes.forEach(pane => {
                    if (pane.id === targetId) {
                        pane.classList.add('active');
                    } else {
                        pane.classList.remove('active');
                    }
                });

                sessionStorage.setItem('active-settings-tab', targetId);
            }

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    switchTab(target);
                });
            });

            // Redirect automatically to the tab containing any validation error
            let tabWithError = null;
            panes.forEach(pane => {
                if (pane.querySelector('.error-message')) {
                    tabWithError = pane.id;
                }
            });

            if (tabWithError) {
                switchTab(tabWithError);
            } else {
                const savedTab = sessionStorage.getItem('active-settings-tab');
                if (savedTab && document.getElementById(savedTab)) {
                    switchTab(savedTab);
                } else {
                    switchTab('pane-identity');
                }
            }

            // ── Dynamic Real-time Preview Sync Logic ──
            const previewMappings = [
                { selector: 'input[name="shop_name"]', previewClass: '.preview-shop-name', default: 'MANAJEMEN KOPI' },
                { selector: 'input[name="shop_tagline"]', previewClass: '.preview-shop-tagline', default: 'Panel Manajemen' },
                { selector: 'input[name="shop_phone"]', previewClass: '.preview-shop-phone', default: '(021) 1234-5678' },
                { selector: 'input[name="shop_email"]', previewClass: '.preview-shop-email', default: 'hello@kopimanajer.com' },
                { selector: 'textarea[name="shop_address"]', previewClass: '.preview-shop-address', default: 'Alamat Usaha' },
                { selector: 'input[name="receipt_title"]', previewClass: '.preview-receipt-title', default: 'INVOICE PENJUALAN' },
                { selector: 'input[name="receipt_thank_you_text"]', previewClass: '.preview-receipt-thank-you', default: 'Terima kasih atas kunjungan Anda!' },
                { selector: 'textarea[name="footer_note"]', previewClass: '.preview-receipt-footer-note', default: 'Catatan kaki nota...' },
                { selector: 'input[name="receipt_left_signature_label"]', previewClass: '.preview-receipt-left-sig', default: 'Penerima / Member' },
                { selector: 'input[name="receipt_right_signature_label"]', previewClass: '.preview-receipt-right-sig', default: 'Hormat Kami,' },
                { selector: 'input[name="receipt_right_signature_name"]', previewClass: '.preview-receipt-right-sig-name', default: 'Administrator' },
                { selector: 'input[name="report_header_name"]', previewClass: '.preview-report-header-name', default: 'Kopi Elang Emas' },
                { selector: 'input[name="report_subtitle"]', previewClass: '.preview-report-subtitle', default: 'Manajemen Kopi & Laporan Terintegrasi' },
                { selector: 'input[name="report_prepared_by_label"]', previewClass: '.preview-report-prepared-by', default: 'Staf Administrasi' },
                { selector: 'input[name="report_approved_by_label"]', previewClass: '.preview-report-approved-by', default: 'Pemilik Gudang / Owner' },
                { selector: 'textarea[name="report_footer_note"]', previewClass: '.preview-report-footer-note', default: '' }
            ];

            // Helper to update initials
            function updateInitials(name) {
                const initialsEl = document.querySelector('.preview-avatar-initials');
                if (initialsEl) {
                    const cleanName = name.trim().replace(/[^a-zA-Z0-9\s]/g, '');
                    const parts = cleanName.split(/\s+/).filter(Boolean);
                    let initials = '';
                    if (parts.length > 0) {
                        initials += parts[0][0].toUpperCase();
                        if (parts.length > 1) {
                            initials += parts[1][0].toUpperCase();
                        }
                    } else {
                        initials = 'LOGO';
                    }
                    initialsEl.textContent = initials || 'LOGO';
                }
            }

            // Initialize Preview Elements with Existing Saved Data
            previewMappings.forEach(mapping => {
                const inputEl = document.querySelector(mapping.selector);
                if (inputEl) {
                    // Initial load sync
                    document.querySelectorAll(mapping.previewClass).forEach(el => {
                        el.textContent = inputEl.value.trim() || mapping.default;
                    });
                    
                    if (mapping.selector === 'input[name="shop_name"]') {
                        updateInitials(inputEl.value);
                    }

                    // Input event listener sync
                    inputEl.addEventListener('input', function(e) {
                        document.querySelectorAll(mapping.previewClass).forEach(el => {
                            el.textContent = e.target.value.trim() || mapping.default;
                        });
                        
                        if (mapping.selector === 'input[name="shop_name"]') {
                            updateInitials(e.target.value);
                        }
                    });
                }
            });
        });
    </script>
</x-layouts.admin>

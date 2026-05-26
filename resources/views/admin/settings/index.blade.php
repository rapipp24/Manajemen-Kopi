<x-layouts.admin>
    <x-slot name="title">Pengaturan Sistem</x-slot>

    <style>
        .settings-container {
            max-width: 1000px;
            display: flex;
            flex-direction: column;
            gap: 28px;
            padding-bottom: 50px;
        }

        .settings-header {
            margin-bottom: 4px;
        }

        .settings-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: #1c0f05;
            margin-bottom: 6px;
        }

        .settings-header p {
            font-size: 14px;
            color: #847162;
            font-weight: 500;
        }

        /* ── Modern Grid Layout ── */
        .settings-layout {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 32px;
            align-items: start;
        }

        @media (max-width: 768px) {
            .settings-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        /* ── Sidebar Buttons ── */
        .settings-sidebar {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        @media (max-width: 768px) {
            .settings-sidebar {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 8px;
                border-bottom: 1px solid #e8d8c4;
                scrollbar-width: none;
            }
            .settings-sidebar::-webkit-scrollbar {
                display: none;
            }
        }

        .settings-tab-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            background: transparent;
            border: 1px solid transparent;
            color: #6b4c35;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s;
            width: 100%;
            outline: none;
        }

        @media (max-width: 768px) {
            .settings-tab-btn {
                width: auto;
                white-space: nowrap;
                padding: 10px 16px;
            }
        }

        .settings-tab-btn:hover {
            background: rgba(212, 162, 116, 0.08);
            color: #92400e;
        }

        .settings-tab-btn.active {
            background: #92400e;
            color: white;
            box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);
        }

        .settings-tab-btn svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.8;
        }

        /* ── Content Card ── */
        .settings-content-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #e8d8c4;
            box-shadow: 0 4px 18px rgba(120, 53, 15, 0.02);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .settings-card-header {
            padding: 22px 28px;
            background: #fffdfa;
            border-bottom: 1px solid #e8d8c4;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .settings-card-title {
            font-size: 16px;
            font-weight: 750;
            color: #2c1a0e;
            margin: 0;
        }

        .settings-card-body {
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ── Form Inputs ── */
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 640px) {
            .form-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 13.5px;
            font-weight: 700;
            color: #4b382a;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e8d8c4;
            background: #fffdfa;
            border-radius: 12px;
            font-size: 14px;
            color: #2c1a0e;
            transition: all 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: #92400e;
            background: white;
            box-shadow: 0 0 0 4px rgba(146, 64, 14, 0.05);
        }

        .form-control::placeholder {
            color: #b09e8f;
        }

        .helper-text {
            font-size: 12px;
            color: #847162;
            margin-top: 2px;
            line-height: 1.4;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            font-weight: 600;
            margin-top: 4px;
        }

        /* ── Tab Panes ── */
        .settings-pane {
            display: none;
        }

        .settings-pane.active {
            display: block;
        }

        /* ── Submit Button ── */
        .btn-submit-settings {
            background: #92400e;
            color: white;
            border: none;
            padding: 14px 36px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);
        }

        .btn-submit-settings:hover {
            background: #78350f;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(146, 64, 14, 0.2);
        }
    </style>

    <div class="settings-container">
        <div class="settings-header">
            <h1>Pengaturan Sistem</h1>
            <p>Kelola identitas usaha, nota penjualan, dan format cetak laporan dasar.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <div class="settings-layout">
                <!-- Kiri (Desktop) / Atas (Mobile): Tab Buttons -->
                <div class="settings-sidebar">
                    <button type="button" class="settings-tab-btn active" data-target="pane-identity">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Identitas Usaha
                    </button>
                    <button type="button" class="settings-tab-btn" data-target="pane-receipt">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Nota Penjualan
                    </button>
                    <button type="button" class="settings-tab-btn" data-target="pane-report">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan PDF
                    </button>
                </div>

                <!-- Kanan (Desktop) / Bawah (Mobile): Card Form -->
                <div class="settings-content-card">
                    
                    <!-- 1. IDENTITAS USAHA PANE -->
                    <div id="pane-identity" class="settings-pane active">
                        <div class="settings-card-header">
                            <h3 class="settings-card-title">Identitas Usaha</h3>
                        </div>
                        <div class="settings-card-body">
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label class="form-label">Nama Usaha / Perusahaan <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="shop_name" value="{{ old('shop_name', $settings['shop_name']) }}" class="form-control" placeholder="Contoh: Kopi Elang Emas" required>
                                    <span class="helper-text">Nama utama yang akan digunakan di sidebar, kop nota, dan kop laporan.</span>
                                    @error('shop_name') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tagline Usaha</label>
                                    <input type="text" name="shop_tagline" value="{{ old('shop_tagline', $settings['shop_tagline']) }}" class="form-control" placeholder="Contoh: Panel Manajemen">
                                    <span class="helper-text">Motto singkat yang muncul di bawah nama brand pada sidebar.</span>
                                    @error('shop_tagline') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label class="form-label">Nomor Telepon / WhatsApp <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="shop_phone" value="{{ old('shop_phone', $settings['shop_phone']) }}" class="form-control" placeholder="Contoh: 0812-3456-7890" required>
                                    <span class="helper-text">Nomor kontak resmi usaha untuk dicantumkan di nota penjualan.</span>
                                    @error('shop_phone') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Usaha <span style="color: #dc2626;">*</span></label>
                                    <input type="email" name="shop_email" value="{{ old('shop_email', $settings['shop_email']) }}" class="form-control" placeholder="Contoh: admin@elangemas.com" required>
                                    <span class="helper-text">Alamat email korespondensi resmi kedai/gudang kopi.</span>
                                    @error('shop_email') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap Usaha <span style="color: #dc2626;">*</span></label>
                                <textarea name="shop_address" rows="3" class="form-control" style="font-family: inherit; resize: vertical;" placeholder="Tuliskan alamat lengkap operasional usaha..." required>{{ old('shop_address', $settings['shop_address']) }}</textarea>
                                <span class="helper-text">Alamat fisik operasional yang akan tercetak di bagian atas lembar nota.</span>
                                @error('shop_address') <span class="error-message">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 2. PENGATURAN NOTA PENJUALAN PANE -->
                    <div id="pane-receipt" class="settings-pane">
                        <div class="settings-card-header">
                            <h3 class="settings-card-title">Pengaturan Nota Penjualan</h3>
                        </div>
                        <div class="settings-card-body">
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label class="form-label">Judul Dokumen Nota</label>
                                    <input type="text" name="receipt_title" value="{{ old('receipt_title', $settings['receipt_title']) }}" class="form-control" placeholder="Contoh: INVOICE PENJUALAN">
                                    <span class="helper-text">Judul transaksi yang akan dicetak di bagian kanan atas nota.</span>
                                    @error('receipt_title') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Teks Terima Kasih</label>
                                    <input type="text" name="receipt_thank_you_text" value="{{ old('receipt_thank_you_text', $settings['receipt_thank_you_text']) }}" class="form-control" placeholder="Contoh: Terima kasih atas kunjungan Anda!">
                                    <span class="helper-text">Pesan penutup ramah untuk dicantumkan di bagian bawah nota.</span>
                                    @error('receipt_thank_you_text') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Catatan Kaki Nota (Syarat & Ketentuan) <span style="color: #dc2626;">*</span></label>
                                <textarea name="footer_note" rows="3" class="form-control" style="font-family: inherit; resize: vertical;" placeholder="Tuliskan catatan kaki nota..." required>{{ old('footer_note', $settings['footer_note']) }}</textarea>
                                <span class="helper-text">Keterangan kebijakan pengembalian barang atau garansi yang dicetak di dasar nota.</span>
                                @error('footer_note') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-grid-2" style="margin-top: 10px; border-top: 1px dashed #e8d8c4; padding-top: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Tanda Tangan Kiri: Label Penerima</label>
                                    <input type="text" name="receipt_left_signature_label" value="{{ old('receipt_left_signature_label', $settings['receipt_left_signature_label'] ?? '') }}" class="form-control" placeholder="Contoh: Penerima / Member">
                                    <span class="helper-text">Label tanda tangan di kolom kiri bawah nota.</span>
                                    @error('receipt_left_signature_label') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tanda Tangan Kanan: Label Pengirim</label>
                                    <input type="text" name="receipt_right_signature_label" value="{{ old('receipt_right_signature_label', $settings['receipt_right_signature_label'] ?? '') }}" class="form-control" placeholder="Contoh: Hormat Kami,">
                                    <span class="helper-text">Label tanda tangan di kolom kanan bawah nota.</span>
                                    @error('receipt_right_signature_label') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div class="form-group">
                                    <!-- Spacer for grid alignment -->
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tanda Tangan Kanan: Nama Penandatangan</label>
                                    <input type="text" name="receipt_right_signature_name" value="{{ old('receipt_right_signature_name', $settings['receipt_right_signature_name'] ?? '') }}" class="form-control" placeholder="Contoh: Administrator">
                                    <span class="helper-text">Nama penanggung jawab di kolom kanan bawah nota.</span>
                                    @error('receipt_right_signature_name') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. PENGATURAN LAPORAN PDF PANE -->
                    <div id="pane-report" class="settings-pane">
                        <div class="settings-card-header">
                            <h3 class="settings-card-title">Pengaturan Laporan PDF</h3>
                        </div>
                        <div class="settings-card-body">
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label class="form-label">Kop Surat Laporan</label>
                                    <input type="text" name="report_header_name" value="{{ old('report_header_name', $settings['report_header_name']) }}" class="form-control" placeholder="Contoh: Kopi Elang Emas">
                                    <span class="helper-text">Nama instansi/perusahaan yang dicetak tebal di bagian kiri atas laporan PDF.</span>
                                    @error('report_header_name') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Sub-judul Kop Laporan</label>
                                    <input type="text" name="report_subtitle" value="{{ old('report_subtitle', $settings['report_subtitle']) }}" class="form-control" placeholder="Contoh: Manajemen Kopi & Produksi Terintegrasi">
                                    <span class="helper-text">Keterangan penjelas di bawah nama kop surat laporan PDF.</span>
                                    @error('report_subtitle') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label class="form-label">Label Tanda Tangan: Pembuat</label>
                                    <input type="text" name="report_prepared_by_label" value="{{ old('report_prepared_by_label', $settings['report_prepared_by_label']) }}" class="form-control" placeholder="Contoh: Staf Administrasi">
                                    <span class="helper-text">Jabatan yang menandatangani di kolom kiri bawah laporan PDF.</span>
                                    @error('report_prepared_by_label') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Label Tanda Tangan: Penyetuju</label>
                                    <input type="text" name="report_approved_by_label" value="{{ old('report_approved_by_label', $settings['report_approved_by_label']) }}" class="form-control" placeholder="Contoh: Pemilik Gudang / Owner">
                                    <span class="helper-text">Jabatan yang menandatangani di kolom kanan bawah laporan PDF.</span>
                                    @error('report_approved_by_label') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Catatan Tambahan Laporan</label>
                                <textarea name="report_footer_note" rows="2" class="form-control" style="font-family: inherit; resize: vertical;" placeholder="Tuliskan catatan tambahan laporan (jika ada)...">{{ old('report_footer_note', $settings['report_footer_note']) }}</textarea>
                                <span class="helper-text">Keterangan penjelas tambahan yang diletakkan di bagian bawah laporan (opsional).</span>
                                @error('report_footer_note') <span class="error-message">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Shared Action Footer -->
                    <div style="padding: 20px 28px; background: #fffdfa; border-top: 1px solid #e8d8c4; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-submit-settings">Simpan Pengaturan</button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- Frontend Tab Switching & Persistence Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

                // Simpan state tab aktif agar persist saat submit/reload
                sessionStorage.setItem('active-settings-tab', targetId);
            }

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    switchTab(target);
                });
            });

            // Cek jika ada validation error di pane tertentu, otomatis arahkan ke tab tersebut
            let tabWithError = null;
            panes.forEach(pane => {
                if (pane.querySelector('.error-message')) {
                    tabWithError = pane.id;
                }
            });

            if (tabWithError) {
                switchTab(tabWithError);
            } else {
                // Ambil state tab yang tersimpan, default ke 'pane-identity' jika kosong
                const savedTab = sessionStorage.getItem('active-settings-tab');
                if (savedTab && document.getElementById(savedTab)) {
                    switchTab(savedTab);
                } else {
                    switchTab('pane-identity');
                }
            }
        });
    </script>
</x-layouts.admin>

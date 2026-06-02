<x-layouts.user>
    <x-slot name="title">Pengaturan Akun</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header {
            margin-bottom: 24px;
        }
        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
        }
        .page-desc {
            font-size: 13.5px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* ── Settings Container ──────────────── */
        .settings-container {
            max-width: 650px;
            margin: 0 auto;
        }

        /* ── Card Styles ──────────────────────── */
        .settings-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
        }

        .settings-card-header {
            padding: 16px 20px;
            background: var(--cream);
            border-bottom: 1px solid var(--border);
        }

        .settings-card-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--text);
            margin: 0;
        }

        .settings-card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ── Form Controls ────────────────────── */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-label {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text);
        }

        .form-label span {
            color: #ef4444;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            background: #fff;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13.5px;
            color: var(--text);
            transition: all 0.15s ease-in-out;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--brown);
            box-shadow: 0 0 0 3px rgba(42, 23, 14, 0.05);
        }

        .form-control:disabled {
            background-color: var(--brown-light);
            color: var(--muted);
            cursor: not-allowed;
            border-color: var(--border);
        }

        .helper-text {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
            line-height: 1.4;
        }

        .error-message {
            color: #dc2626;
            font-size: 11px;
            font-weight: 600;
            margin-top: 2px;
        }

        /* ── Tip Banner ──────────────────────── */
        .info-banner {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            color: #b45309;
            line-height: 1.4;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .info-banner svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* ── Submit Button ────────────────────── */
        .settings-card-footer {
            padding: 14px 20px;
            background: var(--cream);
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
        }

        .btn-submit {
            background: var(--brown);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 4px rgba(42, 23, 14, 0.1);
        }

        .btn-submit:hover {
            background: var(--brown-hover);
            box-shadow: 0 4px 12px rgba(42, 23, 14, 0.15);
        }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 767px) {
            .settings-container {
                max-width: 100%;
            }
            .settings-card-body {
                padding: 16px;
                gap: 14px;
            }
            .btn-submit {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="settings-container">
        <div class="page-header">
            <h1 class="page-title">Pengaturan Akun</h1>
            <p class="page-desc">Kelola dan perbarui informasi profil pribadi Anda.</p>
        </div>

        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">Informasi Profil</h3>
            </div>

            <form action="{{ route('sales.settings.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="settings-card-body">
                    <!-- Tip Banner -->
                    <div class="info-banner">
                        <i data-lucide="info" style="width: 16px; height: 16px;"></i>
                        <div>
                            <strong>Catatan Keamanan:</strong> Email Anda tidak dapat diubah secara langsung karena terhubung dengan sistem verifikasi. Silakan hubungi Administrator jika diperlukan pembaruan email.
                        </div>
                    </div>

                    <!-- Email (Read-only) -->
                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" value="{{ $user->email }}" class="form-control" disabled>
                        <span class="helper-text">Email ini diverifikasi dan digunakan untuk login.</span>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap <span>*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="Tuliskan nama lengkap Anda..." required>
                        <span class="helper-text">Pastikan nama lengkap Anda sesuai dan benar.</span>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor HP (Phone) -->
                    <div class="form-group">
                        <label class="form-label" for="phone">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               placeholder="Contoh: 081234567890">
                        <span class="helper-text">Nomor kontak aktif yang dapat dihubungi oleh Admin.</span>
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Alamat (Address) -->
                    <div class="form-group">
                        <label class="form-label" for="address">Alamat Tempat Tinggal</label>
                        <textarea name="address" id="address" rows="3" 
                                  class="form-control @error('address') is-invalid @enderror" 
                                  placeholder="Tuliskan alamat lengkap tempat tinggal Anda..."
                                  style="resize: vertical;">{{ old('address', $user->address) }}</textarea>
                        <span class="helper-text">Alamat operasional/pengiriman untuk memudahkan koordinasi logistik.</span>
                        @error('address')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="settings-card-footer">
                    <button type="submit" class="btn-submit">
                        <i data-lucide="save" style="width: 16px; height: 16px;"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Pastikan Lucide Icons ter-render di halaman ini
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</x-layouts.user>

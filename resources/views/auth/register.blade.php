<x-guest-layout left-background="/images/auth-bg.png">
    <style>
        /* ── Headers ────────────────────────────── */
        .register-header-desktop {
            display: block;
            margin-bottom: 24px;
        }

        .register-header-mobile {
            display: none;
            margin-bottom: 24px;
        }

        .auth-title {
            font-size: 26px;
            font-weight: 800;
            color: #2A130D;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 14px;
            color: #7A6F68;
            line-height: 1.5;
        }

        /* ── Info & Alert Boxes ─────────────────── */
        .register-info-desktop {
            background: #fdf8f2;
            border: 1px solid #f2e2d2;
            border-left: 4px solid #A3470D;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 13.5px;
            color: #7c5635;
            line-height: 1.55;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .register-info-desktop svg {
            width: 18px;
            height: 18px;
            color: #A3470D;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .register-info-mobile {
            display: none;
            background: #fdf8f2;
            border: 1px solid #f2e2d2;
            border-left: 4px solid #A3470D;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #7c5635;
            line-height: 1.55;
            align-items: flex-start;
            gap: 10px;
        }

        .register-info-mobile svg {
            width: 18px;
            height: 18px;
            color: #A3470D;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* ── Input Wrappers ─────────────────────── */
        .input-wrapper {
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #574F4A;
            margin-bottom: 8px;
        }

        .field-container {
            position: relative;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #E7E0D5;
            transition: border-color 0.25s ease;
        }

        .field-container:focus-within {
            border-bottom-color: #6B2E16;
        }

        .field-icon {
            position: absolute;
            left: 0;
            color: #A8A29E;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .custom-input {
            width: 100%;
            padding: 12px 36px 12px 28px;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            font-size: 15px;
            color: #2A130D;
            background: transparent !important;
            min-height: 48px;
            font-family: inherit;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #2A130D;
            -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            color: #A8A29E;
            cursor: pointer;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #6B2E16;
        }

        .input-hint {
            font-size: 11.5px;
            color: #8C7D70;
            margin-top: 6px;
            font-style: italic;
        }

        /* ── Submit Button ──────────────────────── */
        .register-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #6B2E16 0%, #A3470D 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            min-height: 48px;
            font-family: inherit;
        }

        .register-btn:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        /* ── Footer / Navigation ────────────────── */
        .login-footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #E7E0D5;
        }

        .login-text {
            font-size: 13.5px;
            color: #8C7D70;
        }

        .login-link {
            color: #A3470D;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-link:hover {
            color: #6B2E16;
        }

        /* ── Custom Layout Slots ─────────────────── */
        .left-logo-ring {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 20px;
            border: 2px solid rgba(217, 192, 168, 0.25);
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.3);
        }

        .left-logo-ring img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }

        .left-desc {
            font-size: 16px;
            color: #D9C0A8;
            line-height: 1.6;
            margin-bottom: 36px;
        }

        .left-info-card {
            background: rgba(42, 19, 13, 0.4);
            border: 1px solid rgba(217, 192, 168, 0.15);
            border-radius: 16px;
            padding: 20px;
            backdrop-filter: blur(5px);
        }

        .left-card-title {
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .left-card-body {
            font-size: 13.5px;
            color: #D9C0A8;
            line-height: 1.55;
        }

        /* Named slot footers */
        .auth-footer-desktop {
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 24px;
            align-items: center;
            font-size: 12.5px;
            color: #8C7D70;
            padding: 0 4px;
        }

        .auth-footer-desktop a {
            color: #6B2E16;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-footer-desktop a:hover {
            color: #A3470D;
        }

        .auth-footer-mobile {
            display: none;
            text-align: center;
            font-size: 12px;
            color: #8C7D70;
        }

        /* ── Register Mobile Header ──────────────── */
        .register-mobile-header-top {
            display: none;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 420px;
            padding: 0 4px;
            margin-bottom: 24px;
        }

        .register-mobile-brand {
            font-size: 15px;
            font-weight: 800;
            color: #2A130D;
            letter-spacing: -0.3px;
        }

        .register-mobile-help-wrapper {
            position: relative;
            display: inline-block;
        }

        .register-mobile-help-icon {
            color: #8C7D70;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(107, 46, 22, 0.05);
            transition: background 0.2s, color 0.2s;
            outline: none;
        }

        .register-mobile-help-icon:hover,
        .register-mobile-help-icon:focus {
            background: rgba(107, 46, 22, 0.1);
            color: #6B2E16;
        }

        .help-tooltip {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background-color: #2A130D;
            color: #ffffff;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 6px;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(42, 19, 13, 0.15);
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-4px);
            transition: opacity 0.2s ease, visibility 0.2s ease, transform 0.2s ease;
        }

        .help-tooltip::before {
            content: '';
            position: absolute;
            bottom: 100%;
            right: 11px;
            border: 5px solid transparent;
            border-bottom-color: #2A130D;
        }

        .register-mobile-help-wrapper:hover .help-tooltip,
        .register-mobile-help-icon:focus + .help-tooltip,
        .register-mobile-help-wrapper:active .help-tooltip {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }

        .register-mobile-brand-middle {
            display: none;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            text-align: center;
        }

        .register-mobile-title {
            font-size: 22px;
            font-weight: 800;
            color: #2A130D;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .register-mobile-subtitle {
            font-size: 13px;
            color: #7A6F68;
            margin-bottom: 4px;
        }

        /* ── Responsive Styling ───────────────────── */
        @media (max-width: 1024px) {
            .register-header-desktop {
                display: none;
            }

            .register-header-mobile {
                display: block;
            }

            .register-info-desktop {
                display: none;
            }

            .register-info-mobile {
                display: flex;
            }

            .auth-card {
                padding: 32px 22px !important;
                max-width: 420px !important;
            }

            /* Boxed Style Inputs for Mobile */
            .field-container {
                border: 1.5px solid #E8D8CC !important;
                border-bottom: 1.5px solid #E8D8CC !important;
                border-radius: 12px;
                background-color: #ffffff;
                padding: 0 12px;
                min-height: 48px;
                height: 48px;
            }

            .field-container:focus-within {
                border-color: #6B2E16 !important;
                box-shadow: 0 0 0 3px rgba(107, 46, 22, 0.06);
            }

            .field-icon {
                left: 12px;
            }

            .custom-input {
                padding-top: 0;
                padding-bottom: 0;
                padding-left: 28px;
                padding-right: 28px;
                min-height: 44px;
                height: 100%;
            }

            .password-toggle {
                right: 12px;
            }

            .register-mobile-header-top {
                display: flex;
            }

            .register-mobile-brand-middle {
                display: flex;
            }

            .auth-footer-desktop {
                display: none;
            }

            .auth-footer-mobile {
                display: block;
                margin-top: 4px;
            }
        }
    </style>

    <!-- Desktop Sidebar Branding Slot -->
    <x-slot name="left">
        <div class="left-logo-ring">
            <img src="/icons/logo-transparent.png" alt="Logo Kopi Elang Emas">
        </div>
        <h1 class="hero-title">Kopi Elang Emas</h1>
        <div class="hero-subtitle">ERM Manajemen Kopi</div>
        <div class="hero-divider"></div>
        <p class="left-desc">Sistem manajemen operasional terpadu untuk efisiensi rantai pasok kopi dari hulu ke hilir.</p>
        
        <div class="left-info-card">
            <h3 class="left-card-title">Kopi Nikmat, Harga Merakyat</h3>
            <p class="left-card-body">Platform khusus mitra sales untuk mengoptimalkan distribusi dan pelaporan harian secara real-time.</p>
        </div>
    </x-slot>

    <!-- Mobile Header Slots -->
    <x-slot name="mobileHeader">
        <!-- Header Top Bar (Brand + WA Help) -->
        <div class="register-mobile-header-top">
            <span class="register-mobile-brand">Kopi Elang Emas</span>
            <div class="register-mobile-help-wrapper">
                <a href="https://wa.me/6285789741206" target="_blank" class="register-mobile-help-icon" aria-label="Hubungi admin via WhatsApp">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                    </svg>
                </a>
                <div class="help-tooltip">Hubungi admin via WhatsApp</div>
            </div>
        </div>
        
        <!-- Header Middle (Logo + Vertikal Titles) -->
        <div class="register-mobile-brand-middle">
            <div class="mobile-logo-box">
                <img src="/icons/logo-transparent.png" alt="Logo Kopi Elang Emas">
            </div>
            <h2 class="register-mobile-title">Mulai Perjalanan Kopi</h2>
            <p class="register-mobile-subtitle">Daftarkan akun sales ERM Manajemen Kopi Anda.</p>
        </div>
    </x-slot>

    <!-- Desktop Form Heading -->
    <div class="register-header-desktop">
        <h2 class="auth-title">Daftar Sebagai Sales</h2>
        <p class="auth-subtitle">Buat akun sales untuk mengajukan barang dan melaporkan pengiriman.</p>
    </div>

    <!-- Desktop Alert Info -->
    <div class="register-info-desktop">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.085 1.085l-.04.02-.086.041a.75.75 0 00-.547.547l-.02.086a.75.75 0 01-1.085 1.085l-.02-.041a.75.75 0 00-.547-.547l-.086-.02a.75.75 0 01-1.085-1.085l.04-.02.086-.041a.75.75 0 00.547-.547l.02-.086a.75.75 0 011.085-1.085l.02.041a.75.75 0 00.547.547z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v.008H12V9zm0 11.25a9 9 0 110-18 9 9 0 010 18z" />
        </svg>
        <span>Setelah mendaftar, Anda perlu memverifikasi email. Akun akan aktif setelah disetujui Admin.</span>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name Input -->
        <div class="input-wrapper">
            <label class="input-label">Nama Lengkap</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <input id="name" class="custom-input" type="text" name="name" value="{{ old('name') }}" placeholder="Nama sesuai KTP" required autofocus autocomplete="name" minlength="3" maxlength="255" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Input -->
        <div class="input-wrapper">
            <label class="input-label">Email</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
                <input id="email" class="custom-input" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="username" maxlength="255" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password Input -->
        <div class="input-wrapper">
            <label class="input-label">Kata Sandi</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <input id="password" class="custom-input" type="password" name="password" placeholder="Min. 8 karakter" required autocomplete="new-password" minlength="8" />
                <div class="password-toggle" onclick="toggleField('password', 'eye-password')">
                    <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <p class="input-hint">Minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</p>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password Input -->
        <div class="input-wrapper">
            <label class="input-label">Konfirmasi Kata Sandi</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <input id="password_confirmation" class="custom-input" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required autocomplete="new-password" minlength="8" />
                <div class="password-toggle" onclick="toggleField('password_confirmation', 'eye-confirm')">
                    <svg id="eye-confirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Mobile Info Box -->
        <div class="register-info-mobile">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.085 1.085l-.04.02-.086.041a.75.75 0 00-.547.547l-.02.086a.75.75 0 01-1.085 1.085l-.02-.041a.75.75 0 00-.547-.547l-.086-.02a.75.75 0 01-1.085-1.085l.04-.02.086-.041a.75.75 0 00.547-.547l.02-.086a.75.75 0 011.085-1.085l.02.041a.75.75 0 00.547.547z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v.008H12V9zm0 11.25a9 9 0 110-18 9 9 0 010 18z" />
            </svg>
            <span>Pastikan email Anda aktif. Kami akan mengirimkan tautan verifikasi untuk mengaktifkan akses dashboard Sales Anda.</span>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="register-btn">
            Daftar & Kirim Verifikasi Email
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </button>

        <!-- Link back to Login -->
        <div class="login-footer">
            <p class="login-text">
                Sudah memiliki akun? <a href="{{ route('login') }}" class="login-link">Masuk</a>
            </p>
        </div>
    </form>

    <!-- Footer Slot -->
    <x-slot name="footer">
        <!-- Desktop Footer -->
        <div class="auth-footer-desktop">
            <a href="/terms">Ketentuan Layanan</a>
            <a href="/privacy-policy">Kebijakan Privasi</a>
        </div>
        <!-- Mobile Footer -->
        <div class="auth-footer-mobile">
            © 2026 Kopi Elang Emas ERM
        </div>
    </x-slot>

    <script>
        function toggleField(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
            }
        }
    </script>
</x-guest-layout>

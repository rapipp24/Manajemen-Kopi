<x-guest-layout>
    <!-- Animated Splash Screen Overlay (PWA Opening Experience) -->
    <div id="login-splash" class="splash-overlay" aria-hidden="false">
        <div class="splash-content">
            <div class="splash-logo-ring">
                <img src="/icons/splash-logo.png" alt="Logo Kopi Elang Emas" onerror="this.parentElement.innerHTML='<span class=\'splash-fallback\'>☕</span>'">
            </div>
            <div class="splash-pulse"></div>
            <div class="splash-progress">
                <div class="splash-progress-bar"></div>
            </div>
        </div>
    </div>

    <style>
        /* ── Splash Screen Overlay ──────────────── */
        .splash-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: #F7F2EC;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            animation: splashFallbackFade 1.2s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            animation-delay: 0.1s;
        }

        .splash-content {
            position: relative;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .splash-logo-ring {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 136px;
            height: 136px;
            border-radius: 50%;
            border: 2px solid #E7E0D5;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 8px 20px -6px rgba(107, 46, 22, 0.12);
            z-index: 2;
            animation: splashLogoIntro 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .splash-logo-ring img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .splash-fallback {
            font-size: 2.5rem;
            color: #A3470D;
        }

        .splash-pulse {
            position: absolute;
            width: 136px;
            height: 136px;
            border-radius: 50%;
            border: 2px solid #A3470D;
            opacity: 0;
            z-index: 1;
            animation: splashPulseRing 1.1s cubic-bezier(0.215, 0.610, 0.355, 1) infinite;
            animation-delay: 0.3s;
        }

        .splash-progress {
            width: 120px;
            height: 3px;
            background: #e7e0d5;
            border-radius: 2px;
            margin-top: 2rem;
            overflow: hidden;
            position: relative;
            z-index: 2;
        }

        .splash-progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #6B2E16, #A3470D);
            border-radius: 2px;
            animation: splashProgressBarLoad 0.9s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            animation-delay: 0.1s;
        }

        .splash-overlay.fade-out {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            transition: opacity 0.3s cubic-bezier(0.25, 1, 0.5, 1), visibility 0.3s !important;
        }

        @keyframes splashFallbackFade {
            0% {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }
            85% {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }
            100% {
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }
        }

        @keyframes splashLogoIntro {
            0% {
                transform: scale(0.6);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes splashPulseRing {
            0% {
                transform: scale(0.95);
                opacity: 0.8;
            }
            100% {
                transform: scale(1.6);
                opacity: 0;
            }
        }

        @keyframes splashProgressBarLoad {
            0% {
                width: 0%;
            }
            100% {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .splash-overlay {
                animation: none !important;
                display: none !important;
                opacity: 0 !important;
                visibility: hidden !important;
                pointer-events: none !important;
            }
        }

        /* ── Form Headers ────────────────────────── */
        .login-header-desktop {
            display: block;
            margin-bottom: 28px;
        }

        .login-header-mobile {
            display: none;
            margin-bottom: 28px;
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

        /* ── Alerts & Warnings ───────────────────── */
        .auth-notice {
            margin-bottom: 24px;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid transparent;
            text-align: left;
        }

        .auth-notice-success {
            background: #f7f9f2;
            border-color: #e1e7cf;
            color: #4a533c;
        }

        .auth-notice-warning {
            background: #fdf8f2;
            border-color: #f2e2d2;
            color: #7c5635;
        }

        .auth-notice-title {
            font-weight: 700;
            font-size: 13.5px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .auth-notice-text {
            font-size: 13px;
            line-height: 1.55;
            opacity: 0.95;
        }

        /* ── Inputs ──────────────────────────────── */
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
            border-bottom-color: #6B2E16; /* brand color */
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
            min-height: 48px; /* touch target */
            font-family: inherit;
        }

        /* Webkit autofill override */
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

        /* ── Remember Me & Forgot Password ───────── */
        .remember-row {
            display: flex;
            align-items: center;
            margin-top: 16px;
            margin-bottom: 24px;
        }

        .remember-checkbox-wrapper {
            display: flex;
            align-items: center;
        }

        .remember-checkbox {
            width: 18px;
            height: 18px;
            accent-color: #6B2E16;
            cursor: pointer;
            flex-shrink: 0;
        }

        .remember-label {
            margin-left: 10px;
            font-size: 13.5px;
            color: #7A6F68;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        .forgot-link {
            font-size: 13px;
            font-weight: 600;
            color: #A3470D;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #6B2E16;
        }

        .desktop-forgot-link {
            display: inline;
        }

        .mobile-forgot-link {
            display: none;
        }

        /* ── Primary Submit Button ───────────────── */
        .login-btn {
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

        .login-btn:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* ── Footer Link to Register ──────────────── */
        .register-footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #E7E0D5;
        }

        .register-text {
            font-size: 13.5px;
            color: #8C7D70;
        }

        .register-link {
            color: #A3470D;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }

        .register-link:hover {
            color: #6B2E16;
        }

        /* ── Named Slot Footers ───────────────────── */
        .auth-footer-desktop {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #8C7D70;
            padding: 0 4px;
        }

        .auth-footer-desktop a {
            color: #6B2E16;
            text-decoration: none;
            font-weight: 600;
            margin-left: 16px;
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

        /* ── Responsive Styling ───────────────────── */
        @media (max-width: 1024px) {
            .login-header-desktop {
                display: none;
            }

            .login-header-mobile {
                display: block;
                margin-bottom: 22px;
            }

            .auth-card {
                padding: 32px 22px !important;
                max-width: 420px !important;
            }

            .desktop-forgot-link {
                display: none;
            }

            .mobile-forgot-link {
                display: inline;
            }

            .remember-row {
                justify-content: space-between;
                width: 100%;
                margin-top: 14px;
                margin-bottom: 20px;
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

            .auth-footer-desktop {
                display: none;
            }

            .auth-footer-mobile {
                display: block;
                margin-top: 4px;
            }
        }
    </style>

    <!-- Form Headings -->
    <div class="login-header-desktop">
        <h2 class="auth-title">Masuk</h2>
        <p class="auth-subtitle">Masuk ke akun Anda untuk mengakses panel kerja.</p>
    </div>

    <div class="login-header-mobile">
        <h2 class="auth-title">Selamat Datang</h2>
        <p class="auth-subtitle">Silakan masuk untuk mengakses panel kerja Anda.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Flash: Success / Registration Notices --}}
        @if (session('status'))
            @php
                $status = session('status');
                $isRegisterSuccess = str_contains($status, 'Pendaftaran berhasil');
                $title = $isRegisterSuccess ? 'Pendaftaran berhasil' : 'Informasi';
                $text = $isRegisterSuccess 
                    ? 'Cek email Anda untuk verifikasi akun. Setelah itu, akun akan menunggu persetujuan Admin.' 
                    : $status;
            @endphp
            <div class="auth-notice auth-notice-success">
                <div class="auth-notice-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px; flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $title }}
                </div>
                <div class="auth-notice-text">
                    {{ $text }}
                </div>
            </div>
        @endif

        {{-- Flash: Warnings --}}
        @if (session('warning'))
            <div class="auth-notice auth-notice-warning">
                <div class="auth-notice-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px; flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    Peringatan
                </div>
                <div class="auth-notice-text">
                    {{ session('warning') }}
                </div>
            </div>
        @endif

        <!-- Email Input -->
        <div class="input-wrapper">
            <label class="input-label">Email</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <input id="email" class="custom-input" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus maxlength="50" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password Input -->
        <div class="input-wrapper">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 8px;">
                <label class="input-label" style="margin-bottom: 0;">Kata Sandi</label>
                <a href="{{ route('password.request') }}" class="forgot-link desktop-forgot-link">Lupa kata sandi?</a>
            </div>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
                <input id="password" class="custom-input" type="password" name="password" placeholder="••••••••" required minlength="8" />
                <div class="password-toggle" onclick="togglePassword()">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password Row -->
        <div class="remember-row">
            <div class="remember-checkbox-wrapper">
                <input id="remember_me" type="checkbox" class="remember-checkbox" name="remember">
                <label for="remember_me" class="remember-label">Ingat saya</label>
            </div>
            <a href="{{ route('password.request') }}" class="forgot-link mobile-forgot-link">Lupa kata sandi?</a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="login-btn">
            Masuk
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </button>

        <!-- Link to Register -->
        <div class="register-footer">
            <p class="register-text">
                Belum punya akun sales? <a href="{{ route('register') }}" class="register-link">Daftar sebagai Sales</a>
            </p>
        </div>
    </form>

    <!-- Footer Slots for named rendering -->
    <x-slot name="footer">
        <!-- Desktop Footer -->
        <div class="auth-footer-desktop">
            <span class="footer-copy">© 2026 Kopi Elang Emas ERM</span>
            <div class="footer-links">
                <a href="https://wa.me/6285789741206" target="_blank">Bantuan</a>
                <a href="/privacy-policy">Privasi</a>
            </div>
        </div>
        <!-- Mobile Footer -->
        <div class="auth-footer-mobile">
            © 2026 Kopi Elang Emas. Operational Excellence.
        </div>
    </x-slot>

    <script>
        // PWA Splash Screen Animation controller
        document.addEventListener('DOMContentLoaded', function() {
            const splash = document.getElementById('login-splash');
            if (splash) {
                setTimeout(function() {
                    splash.classList.add('fade-out');
                    splash.setAttribute('aria-hidden', 'true');
                    setTimeout(function() {
                        splash.style.display = 'none';
                    }, 300);
                }, 950);
            }
        });

        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
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
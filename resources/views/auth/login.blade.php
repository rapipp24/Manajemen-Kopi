<x-guest-layout>
    <style>
        .form-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .lock-icon-container {
            width: 52px;
            height: 52px;
            background: #fdf6e3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: #1a1512;
        }

        .header-title {
            font-size: 24px;
            font-weight: 800;
            color: #1a1512;
            margin: 0 0 6px;
            letter-spacing: -0.5px;
        }

        .header-sub {
            font-size: 14px;
            color: #78716c;
            margin: 0;
        }

        /* ── Error Alert ─────────────────────────── */
        .auth-error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #dc2626;
        }

        /* ── Input Fields ────────────────────────── */
        .input-wrapper {
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #57534e;
            margin-bottom: 8px;
        }

        .field-container {
            position: relative;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #e7e0d5;
            transition: border-color 0.25s ease;
        }

        .field-container:focus-within {
            border-bottom-color: #1a1512;
        }

        .field-icon {
            position: absolute;
            left: 0;
            color: #a8a29e;
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .custom-input {
            width: 100%;
            padding: 11px 36px 11px 30px;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            font-size: 15px;
            color: #1a1512;
            background: transparent !important;
            min-height: 44px; /* touch target */
            font-family: 'Inter', sans-serif;
        }

        /* Fix Autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #1a1512;
            -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            color: #a8a29e;
            cursor: pointer;
            width: 20px;
            height: 20px;
            transition: color 0.2s;
            flex-shrink: 0;
            padding: 2px;
        }

        .password-toggle:hover {
            color: #1a1512;
        }

        /* ── Remember Me ─────────────────────────── */
        .remember-row {
            display: flex;
            align-items: center;
            margin-top: 16px;
            margin-bottom: 24px;
        }

        .remember-checkbox {
            width: 16px;
            height: 16px;
            accent-color: #1a1512;
            cursor: pointer;
            flex-shrink: 0;
        }

        .remember-label {
            margin-left: 10px;
            font-size: 13px;
            color: #78716c;
            font-weight: 500;
            cursor: pointer;
            line-height: 1.3;
        }

        /* ── Login Button ────────────────────────── */
        .login-btn {
            width: 100%;
            padding: 15px;
            background: #1a1512;
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
            transition: background 0.25s, transform 0.15s;
            min-height: 50px; /* touch target */
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.2px;
        }

        .login-btn:hover {
            background: #2d2520;
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* ── Footer Help ─────────────────────────── */
        .footer-help {
            text-align: center;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid #f0ebe3;
        }

        .help-text {
            font-size: 13px;
            color: #a8a29e;
            margin-bottom: 10px;
        }

        .help-link {
            color: #1a1512;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .register-link {
            color: #92400e;
            font-weight: 800;
            text-decoration: none;
        }

        .forgot-link {
            font-size: 11px;
            font-weight: 700;
            color: #78716c;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #1a1512;
        }

        /* ── Mobile Responsive ───────────────────── */
        @media (max-width: 640px) {
            .form-header {
                margin-bottom: 24px;
            }
            .header-title {
                font-size: 22px;
            }
            .custom-input {
                font-size: 16px; /* prevent iOS zoom on focus */
                min-height: 48px;
            }
            .login-btn {
                font-size: 16px;
                min-height: 52px;
                border-radius: 14px;
            }
            .input-wrapper {
                margin-bottom: 18px;
            }
        }
    </style>

    <div class="form-header">
        <div class="lock-icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 22px; height: 22px;">
                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
            </svg>
        </div>
        <h2 class="header-title">Selamat Datang Kembali</h2>
        <p class="header-sub">Silakan masuk ke akun Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Flash: pesan sukses registrasi atau info --}}
        @if (session('status'))
            <div style="background:#f0fdf4; border:1px solid #86efac; border-left:3px solid #22c55e; color:#166534;
                        padding:12px 14px; border-radius:10px; margin-bottom:20px; font-size:13px; line-height:1.5;">
                {{ session('status') }}
            </div>
        @endif

        {{-- Flash: pesan warning dari kondisi akun --}}
        @if (session('warning'))
            <div style="background:#fffbeb; border:1px solid #fcd34d; border-left:3px solid #f59e0b; color:#92400e;
                        padding:12px 14px; border-radius:10px; margin-bottom:20px; font-size:13px; line-height:1.5;">
                {{ session('warning') }}
            </div>
        @endif

        <div class="input-wrapper">
            <label class="input-label">Email atau Username</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <input id="email" class="custom-input" type="email" name="email" :value="old('email')" placeholder="admin@manajemenkopi.test" required autofocus maxlength="50" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="input-wrapper">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 8px;">
                <label class="input-label" style="margin-bottom: 0;">Kata Sandi</label>
                <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
            </div>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
                <input id="password" class="custom-input" type="password" name="password" placeholder="••••••••" required minlength="8" />
                <div class="password-toggle" onclick="togglePassword()">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="remember-row">
            <input id="remember_me" type="checkbox" class="remember-checkbox" name="remember">
            <label for="remember_me" class="remember-label">Ingat saya di perangkat ini</label>
        </div>

        <button type="submit" class="login-btn">
            Masuk
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </button>

        <div class="footer-help">
            <div class="help-text">
                Butuh bantuan akses? 
                <a href="https://wa.me/6281234567890" target="_blank" class="help-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a.75.75 0 01-1.074-.765 5.99 5.99 0 01.123-1.006A8.274 8.274 0 013 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                    Hubungi Support
                </a>
            </div>
            <p style="font-size: 13px; color: #94a3b8;">
                Belum punya akun? <a href="{{ route('register') }}" class="register-link">Daftar Sekarang</a>
            </p>
        </div>
    </form>

    <script>
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
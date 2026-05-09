<x-guest-layout>
    <style>
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .user-icon-container {
            width: 54px;
            height: 54px;
            background: #fdfae9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            color: #1a1512;
        }

        .header-title {
            font-size: 26px;
            font-weight: 800;
            color: #1a1512;
            margin-bottom: 6px;
            letter-spacing: -0.8px;
        }

        .header-sub {
            font-size: 14px;
            color: #64748b;
        }

        .input-wrapper {
            margin-bottom: 22px;
        }

        .input-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a1512;
            margin-bottom: 8px;
        }

        .field-container {
            position: relative;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .field-container:focus-within {
            border-bottom-color: #1a1512;
        }

        .field-icon {
            position: absolute;
            left: 0;
            color: #94a3b8;
            width: 18px;
            height: 18px;
        }

        .custom-input {
            width: 100%;
            padding: 10px 35px 10px 30px;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            font-size: 15px;
            color: #1a1512;
            background: transparent !important;
        }

        /* Fix Autofill background */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #1a1512;
            -webkit-box-shadow: 0 0 0px 1000px white inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            color: #94a3b8;
            cursor: pointer;
            width: 20px;
            height: 20px;
            transition: color 0.2s;
        }
        
        .password-toggle:hover {
            color: #1a1512;
        }

        .register-btn {
            width: 100%;
            padding: 16px;
            background: #1a1512;
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .register-btn:hover {
            background: #2d2520;
            transform: translateY(-1px);
        }

        .footer-login {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f8fafc;
        }

        .login-text {
            font-size: 13px;
            color: #94a3b8;
        }

        .login-link {
            color: #1a1512;
            font-weight: 700;
            text-decoration: none;
        }
    </style>

    <div class="form-header">
        <div class="user-icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 24px; height: 24px;">
                <path d="M5.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM2.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM18.75 7.5a.75.75 0 00-1.5 0v2.25H15a.75.75 0 000 1.5h2.25V13.5a.75.75 0 001.5 0v-2.25H21a.75.75 0 000-1.5h-2.25V7.5z" />
            </svg>
        </div>
        <h2 class="header-title">Daftar Akun Baru</h2>
        <p class="header-sub">Bergabunglah dengan ekosistem Kopi Elang Mas</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="input-wrapper">
            <label class="input-label">Nama Lengkap</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <input id="name" class="custom-input" type="text" name="name" :value="old('name')" placeholder="Masukkan nama lengkap Anda" required autofocus autocomplete="name" maxlength="50" pattern="[a-zA-Z\s]+" title="Nama hanya boleh berisi huruf dan spasi" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="input-wrapper">
            <label class="input-label">Email</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
                <input id="email" class="custom-input" type="email" name="email" :value="old('email')" placeholder="email@contoh.com" required autocomplete="username" maxlength="50" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="input-wrapper">
            <label class="input-label">Kata Sandi</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <input id="password" class="custom-input" type="password" name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password" minlength="8" />
                <div class="password-toggle" onclick="toggleField('password', 'eye-password')">
                    <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="input-wrapper">
            <label class="input-label">Konfirmasi Kata Sandi</label>
            <div class="field-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="field-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <input id="password_confirmation" class="custom-input" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required autocomplete="new-password" minlength="8" />
                <div class="password-toggle" onclick="toggleField('password_confirmation', 'eye-confirm')">
                    <svg id="eye-confirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <button type="submit" class="register-btn">
            Daftar Akun
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>

        <div class="footer-login">
            <p class="login-text">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="login-link">Masuk Sekarang</a>
            </p>
        </div>
    </form>

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

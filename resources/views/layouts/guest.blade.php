<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kopi Elang Emas') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#6B2E16">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Kopi Elang">
        
        <!-- PWA Icons & Manifest -->
        <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
        <link rel="manifest" href="/manifest.json">

        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: #F7F2EC;
                min-height: 100vh;
                color: #2A130D;
            }

            .auth-container {
                display: grid;
                grid-template-columns: 1.1fr 1fr;
                min-height: 100vh;
            }

            /* ── Sisi Kiri: Premium Sidebar (Desktop Only) ─────────────────── */
            .auth-side-left {
                position: relative;
                background-image: url('/images/bg.jpg');
                background-size: cover;
                background-position: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 80px;
                color: #ffffff;
                overflow: hidden;
            }

            /* Dark brown overlay for readability */
            .auth-side-left::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(58, 26, 9, 0.9) 0%, rgba(26, 13, 6, 0.95) 100%);
                z-index: 0;
            }

            /* Subtle pattern overlay on sidebar */
            .auth-side-left::after {
                content: '';
                position: absolute;
                inset: 0;
                background-image: radial-gradient(rgba(163, 71, 13, 0.15) 1px, transparent 1px);
                background-size: 24px 24px;
                opacity: 0.8;
                pointer-events: none;
                z-index: 1;
            }

            .left-content {
                position: relative;
                z-index: 2;
                max-width: 520px;
            }

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
                margin-bottom: 2.5rem;
                box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.3);
            }

            .left-logo-ring img {
                width: 80%;
                height: 80%;
                object-fit: contain;
            }

            .hero-title {
                font-size: clamp(32px, 4.5vw, 44px);
                font-weight: 800;
                line-height: 1.15;
                margin-bottom: 8px;
                letter-spacing: -1px;
                color: #ffffff;
            }

            .hero-subtitle {
                font-size: clamp(14px, 2.5vw, 18px);
                font-weight: 600;
                color: #A3470D; /* brand accent color */
                letter-spacing: 2px;
                text-transform: uppercase;
                margin-bottom: 24px;
            }

            .hero-divider {
                width: 60px;
                height: 3px;
                background: linear-gradient(90deg, #A3470D, #D9C0A8);
                border-radius: 2px;
                margin-bottom: 28px;
            }

            .hero-quote {
                font-size: 16px;
                font-style: italic;
                color: #D9C0A8;
                line-height: 1.6;
                font-weight: 400;
            }

            /* ── Mobile Brand Header (Centered for Mobile) ─────────────── */
            .mobile-brand-header {
                display: none;
                flex-direction: column;
                align-items: center;
                margin-bottom: 24px;
                text-align: center;
            }

            .mobile-logo-box {
                width: 56px;
                height: 56px;
                border-radius: 14px;
                border: 1.5px solid rgba(217, 192, 168, 0.25);
                background: #3A1A09;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                margin-bottom: 12px;
                box-shadow: 0 6px 15px -4px rgba(107, 46, 22, 0.15);
            }

            .mobile-logo-box img {
                width: 82%;
                height: 82%;
                object-fit: contain;
            }

            .mobile-brand-text {
                font-size: 20px;
                font-weight: 800;
                color: #2A130D;
                letter-spacing: -0.5px;
                margin-bottom: 2px;
            }

            .mobile-brand-sub {
                font-size: 12px;
                color: #A3470D;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            /* ── Sisi Kanan: Form Container ─────────────────────── */
            .auth-side-right {
                background-color: #F7F2EC;
                background-image: radial-gradient(#e1d6c7 1.5px, transparent 1.5px);
                background-size: 20px 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 40px 24px;
                overflow-y: auto;
            }

            .auth-card-wrapper {
                width: 100%;
                max-width: 460px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 24px;
            }

            .auth-card {
                background: #ffffff;
                width: 100%;
                padding: 48px;
                border-radius: 24px;
                box-shadow: 0 12px 40px -10px rgba(107, 46, 22, 0.08), 0 4px 12px -5px rgba(107, 46, 22, 0.04);
                border: 1px solid rgba(231, 224, 213, 0.5);
            }

            /* ── Responsive Tablet & Mobile ───────────────── */
            @media (max-width: 1024px) {
                .auth-container {
                    grid-template-columns: 1fr;
                }
                .auth-side-left {
                    display: none;
                }
                .mobile-brand-header {
                    display: flex;
                }
                .auth-side-right {
                    flex: 1;
                    padding: 40px 16px;
                }
                .auth-card {
                    padding: 36px 24px;
                    border-radius: 20px;
                }
            }

            @media (max-width: 480px) {
                .auth-side-right {
                    padding: 24px 12px;
                }
                .auth-card {
                    padding: 28px 20px;
                    border-radius: 16px;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <!-- Left Side (visible on desktop only) -->
            <div class="auth-side-left">
                <div class="left-content">
                    <div class="left-logo-ring">
                        <img src="/icons/logo-transparent.png" alt="Logo Kopi Elang Emas">
                    </div>
                    <h1 class="hero-title">Kopi Elang Emas</h1>
                    <div class="hero-subtitle">ERM Manajemen Kopi</div>
                    <div class="hero-divider"></div>
                    <p class="hero-quote">"Kopi Nikmat, Harga Merakyat"</p>
                </div>
            </div>

            <!-- Right Side (Form & Footer) -->
            <div class="auth-side-right">
                <!-- Mobile Brand Header (centered, visible on mobile only) -->
                <div class="mobile-brand-header">
                    <div class="mobile-logo-box">
                        <img src="/icons/logo-transparent.png" alt="Logo Kopi Elang Emas">
                    </div>
                    <div class="mobile-brand-text">Kopi Elang Emas</div>
                    <div class="mobile-brand-sub">ERM Manajemen Kopi</div>
                </div>

                <div class="auth-card-wrapper">
                    <div class="auth-card">
                        {{ $slot }}
                    </div>
                    @if(isset($footer))
                        {{ $footer }}
                    @endif
                </div>
            </div>
        </div>
    
        <!-- Service Worker Registration (Non-blocking) -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js')
                        .then((reg) => console.log('Service worker registered successfully', reg.scope))
                        .catch((err) => console.error('Service worker registration failed:', err));
                });
            }
        </script>
    </body>
</html>

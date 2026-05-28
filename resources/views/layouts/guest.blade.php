<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kopi Elang Mas') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- PWA Basic Meta Tags -->
        <meta name="theme-color" content="#1c0f05">
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
            }

            body {
                font-family: 'Inter', sans-serif;
                margin: 0;
                background-color: #f5f0e8;
                min-height: 100vh;
            }

            .auth-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                min-height: 100vh;
            }

            /* ── Sisi Kiri: Hero/Brand ─────────────────── */
            .auth-side-left {
                position: relative;
                background: #1a1512;
                background-image: url('/images/auth-bg.png');
                background-size: cover;
                background-position: center;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                padding: 60px;
                color: #ffffff;
            }

            .auth-side-left::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(14, 11, 9, 0.95) 10%, rgba(14, 11, 9, 0.4) 100%);
            }

            .left-content {
                position: relative;
                z-index: 1;
                max-width: 500px;
            }

            .brand-badge {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 13px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                margin-bottom: 24px;
                color: #f59e0b;
            }

            .brand-badge span {
                background: rgba(245, 158, 11, 0.2);
                padding: 6px 10px;
                border-radius: 6px;
                display: flex;
                align-items: center;
            }

            .hero-title {
                font-size: 48px;
                font-weight: 800;
                line-height: 1.1;
                margin-bottom: 20px;
                letter-spacing: -1px;
            }

            .hero-desc {
                font-size: 16px;
                line-height: 1.6;
                color: #d1d5db;
                margin-bottom: 40px;
            }

            .left-footer {
                display: flex;
                gap: 20px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #9ca3af;
                margin-top: 40px;
                padding-top: 25px;
                border-top: 1px solid rgba(255,255,255,0.1);
            }

            /* ── Mobile Brand Strip ────────────────────── */
            .mobile-brand-strip {
                display: none;
                background: #1a1512;
                padding: 18px 20px;
                align-items: center;
                gap: 10px;
            }

            .mobile-brand-logo {
                width: 32px;
                height: 32px;
                background: rgba(245, 158, 11, 0.2);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #f59e0b;
                flex-shrink: 0;
            }

            .mobile-brand-text {
                font-size: 15px;
                font-weight: 800;
                color: #ffffff;
                letter-spacing: -0.3px;
            }

            .mobile-brand-sub {
                font-size: 11px;
                color: #9ca3af;
                font-weight: 500;
            }

            /* ── Sisi Kanan: Form ─────────────────────── */
            .auth-side-right {
                background-color: #f5f0e8;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 40px 24px;
                overflow-y: auto;
            }

            .auth-card {
                background: #ffffff;
                width: 100%;
                max-width: 460px;
                padding: 52px 48px;
                border-radius: 32px;
                box-shadow: 0 8px 40px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.04);
            }

            /* ── Tablet: 768px - 1024px ───────────────── */
            @media (max-width: 1024px) {
                .auth-container {
                    grid-template-columns: 1fr;
                    min-height: 100vh;
                }
                .auth-side-left {
                    display: none;
                }
                .auth-side-right {
                    padding: 32px 24px;
                    align-items: flex-start;
                    padding-top: 40px;
                }
                .auth-card {
                    padding: 40px 36px;
                    border-radius: 24px;
                    max-width: 480px;
                    margin: 0 auto;
                }
            }

            /* ── Mobile: <= 640px ─────────────────────── */
            @media (max-width: 640px) {
                body {
                    background-color: #ffffff;
                }
                .auth-container {
                    display: flex;
                    flex-direction: column;
                    min-height: 100vh;
                    min-height: 100dvh;
                }
                .mobile-brand-strip {
                    display: flex;
                    flex-shrink: 0;
                }
                .auth-side-right {
                    flex: 1;
                    padding: 28px 16px 32px;
                    background-color: #ffffff;
                    align-items: flex-start;
                }
                .auth-card {
                    padding: 28px 20px 32px;
                    border-radius: 0;
                    box-shadow: none;
                    max-width: 100%;
                    background: transparent;
                }
            }

            /* ── Very Small: <= 375px ─────────────────── */
            @media (max-width: 375px) {
                .auth-side-right {
                    padding: 20px 12px 28px;
                }
                .auth-card {
                    padding: 20px 16px 28px;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <!-- Mobile Brand Strip (only visible on mobile) -->
            <div class="mobile-brand-strip">
                <div class="mobile-brand-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                </div>
                <div>
                    <div class="mobile-brand-text">Kopi Elang Emas</div>
                    <div class="mobile-brand-sub">Sistem Manajemen Produksi</div>
                </div>
            </div>

            <!-- Left Side -->
            <div class="auth-side-left">
                <div class="left-content">
                    <div class="brand-badge">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </span>
                        INDUSTRIAL ALCHEMIST
                    </div>
                    <h1 class="hero-title">Kopi Elang Emas</h1>
                    <p class="hero-desc">Sistem Manajemen Produksi</p>
                    
                    <div class="left-footer">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 14px; height: 14px;">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4.13-5.5z" clip-rule="evenodd" />
                            </svg>
                            ENTERPRISE GRADE
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 14px; height: 14px;">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4.13-5.5z" clip-rule="evenodd" />
                            </svg>
                            PRODUCTION READY
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div class="auth-side-right">
                <div class="auth-card">
                    {{ $slot }}
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

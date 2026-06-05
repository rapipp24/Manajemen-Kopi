<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') | Kopi Elang Emas</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: #F7F2EC;
                color: #2A130D;
                min-height: 100vh;
                line-height: 1.6;
                padding: 40px 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .legal-container {
                width: 100%;
                max-width: 760px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            /* ── Header Branding ─────────────────────── */
            .legal-header {
                text-align: center;
                margin-bottom: 32px;
            }

            .logo-ring {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 64px;
                height: 64px;
                border-radius: 18px;
                border: 2px solid #D9C0A8;
                background: #ffffff;
                overflow: hidden;
                margin-bottom: 16px;
                box-shadow: 0 4px 12px rgba(107, 46, 22, 0.08);
            }

            .logo-ring img {
                width: 80%;
                height: 80%;
                object-fit: contain;
            }

            .brand-name {
                font-size: 18px;
                font-weight: 800;
                color: #2A130D;
                letter-spacing: -0.3px;
            }

            .brand-sub {
                font-size: 11px;
                color: #A3470D;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            /* ── Card Content ────────────────────────── */
            .legal-card {
                background: #ffffff;
                width: 100%;
                padding: 48px;
                border-radius: 24px;
                box-shadow: 0 12px 40px -10px rgba(107, 46, 22, 0.08), 0 4px 12px -5px rgba(107, 46, 22, 0.04);
                border: 1px solid rgba(231, 224, 213, 0.5);
                margin-bottom: 24px;
            }

            .legal-title {
                font-size: clamp(22px, 3.5vw, 28px);
                font-weight: 800;
                color: #2A130D;
                letter-spacing: -0.5px;
                margin-bottom: 12px;
                text-align: center;
            }

            .legal-intro {
                font-size: 14.5px;
                color: #7A6F68;
                text-align: center;
                margin-bottom: 32px;
                line-height: 1.6;
            }

            /* ── Document Body Styling ───────────────── */
            .legal-body h3 {
                font-size: 16px;
                font-weight: 800;
                color: #2A130D;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                margin-top: 28px;
                margin-bottom: 12px;
                display: flex;
                align-items: center;
                gap: 8px;
                border-bottom: 1px solid #E7E0D5;
                padding-bottom: 6px;
            }

            .legal-body p {
                font-size: 14.5px;
                color: #4A3A31;
                margin-bottom: 18px;
                line-height: 1.7;
            }

            .legal-body ul {
                margin-left: 20px;
                margin-bottom: 18px;
                color: #4A3A31;
            }

            .legal-body li {
                font-size: 14.5px;
                margin-bottom: 8px;
                line-height: 1.6;
            }

            /* ── Navigation / Action Buttons ─────────── */
            .legal-actions {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
                margin-top: 12px;
                width: 100%;
            }

            .btn-back {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 12px 32px;
                background: linear-gradient(135deg, #6B2E16 0%, #A3470D 100%);
                color: #ffffff;
                text-decoration: none;
                border-radius: 12px;
                font-size: 14.5px;
                font-weight: 700;
                transition: opacity 0.2s, transform 0.15s;
                box-shadow: 0 4px 12px rgba(107, 46, 22, 0.15);
            }

            .btn-back:hover {
                opacity: 0.92;
                transform: translateY(-1px);
            }

            .btn-back:active {
                transform: translateY(0);
            }

            .legal-links {
                display: flex;
                gap: 20px;
                font-size: 13px;
                font-weight: 600;
                color: #8C7D70;
            }

            .legal-links a {
                color: #A3470D;
                text-decoration: none;
                transition: color 0.2s;
            }

            .legal-links a:hover {
                color: #6B2E16;
            }

            /* ── Responsive Mobile ───────────────────── */
            @media (max-width: 640px) {
                body {
                    padding: 24px 12px;
                }
                .legal-card {
                    padding: 28px 20px;
                    border-radius: 18px;
                }
                .legal-body p, .legal-body li {
                    font-size: 14px;
                }
            }

            @media (max-width: 480px) {
                .legal-links {
                    flex-direction: column;
                    align-items: center;
                    gap: 12px;
                }
            }
        </style>
    </head>
    <body>
        <div class="legal-container">
            <!-- Branding Header -->
            <div class="legal-header">
                <div class="logo-ring">
                    <img src="/icons/logo-transparent.png" alt="Logo Kopi Elang Emas">
                </div>
                <div class="brand-name">Kopi Elang Emas</div>
                <div class="brand-sub">ERM Manajemen Kopi</div>
            </div>

            <!-- Content Card -->
            <div class="legal-card">
                <h1 class="legal-title">@yield('page_title')</h1>
                <p class="legal-intro">@yield('page_intro')</p>
                
                <div class="legal-body">
                    @yield('content')
                </div>
            </div>

            <!-- Actions and Footer Links -->
            <div class="legal-actions">
                <a href="{{ route('login') }}" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Kembali ke Login
                </a>
                
                <div class="legal-links">
                    @yield('footer_links')
                </div>
            </div>
        </div>
    </body>
</html>

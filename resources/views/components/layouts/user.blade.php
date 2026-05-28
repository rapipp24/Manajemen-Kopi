<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — Portal Sales</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

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
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brown:       #2a170e; /* Dark espresso brown */
            --brown-light: #f5efe6; /* Soft warm beige */
            --brown-hover: #1c0d02; /* Darker espresso */
            --accent:      #c5a059; /* Soft amber/gold */
            --cream:       #fbf9f4; /* Off-white cream */
            --text:        #2a170e; /* Dark coffee text */
            --muted:       #705f56; /* Warm brown-gray muted */
            --border:      #eae3d2; /* Soft warm border */
            --surface:     #ffffff;
            --bg:          #fcfaf5; /* Warm background cream */
            --sidebar-w:   230px;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: row;
        }

        /* ── Sidebar ─────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }

        .brand-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }
        .brand-name span { color: var(--accent); }

        .brand-role {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 4px;
        }

        /* ── Nav ─────────────────────────────────────── */
        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-section {
            font-size: 10px;
            font-weight: 800;
            color: #bfaea5;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 12px 10px 6px;
            margin-top: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: var(--brown-light);
            color: var(--text);
        }

        .nav-item.active {
            background: var(--brown);
            color: var(--surface);
            font-weight: 600;
        }

        .nav-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            opacity: 0.85;
        }

        .nav-item.active .nav-icon { opacity: 1; color: var(--accent); }

        /* ── Sidebar footer ──────────────────────────── */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
            background: var(--cream);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 0;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            background: var(--brown-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            color: var(--brown);
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .user-name { font-size: 13px; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-label { font-size: 10.5px; color: var(--muted); font-weight: 500; }

        .btn-logout {
            width: 100%;
            padding: 9px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--muted);
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn-logout:hover { border-color: #fca5a5; color: #ef4444; background: #fef2f2; }

        /* ── Main Content ────────────────────────────── */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-date {
            font-size: 12.5px;
            color: var(--muted);
            font-weight: 500;
        }

        .page-body {
            padding: 28px;
            max-width: 1080px;
            width: 100%;
            margin: 0;
        }

        /* ── Flash Messages ──────────────────────────── */
        .flash {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .flash-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

        /* ── Responsive Mobile / Drawer ──────────────── */
        .mobile-topbar {
            display: none;
            height: 56px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 16px;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: var(--text);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.15s;
        }
        .menu-toggle:hover {
            background: var(--brown-light);
        }

        .menu-toggle svg {
            width: 22px;
            height: 22px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(42, 23, 14, 0.4);
            backdrop-filter: blur(2px);
            z-index: 95;
        }

        @media (max-width: 1023px) {
            body {
                display: block;
            }
            .sidebar {
                position: fixed;
                left: 0;
                transform: translateX(-100%);
                height: 100vh;
                z-index: 100;
            }
            body.sidebar-open .sidebar {
                transform: translateX(0);
            }
            body.sidebar-open .sidebar-overlay {
                display: block;
            }
            .main {
                margin-left: 0 !important;
            }
            .topbar {
                display: none;
            }
            .mobile-topbar {
                display: flex;
            }
            .page-body {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Mobile Topbar -->
    <div class="mobile-topbar">
        <button type="button" class="menu-toggle" onclick="toggleSidebar()">
            <i data-lucide="menu"></i>
        </button>
        <span style="font-size: 15px; font-weight: 800; color: var(--text); letter-spacing: -0.02em;">Kopi <span>Elang Emas</span></span>
        <div class="user-avatar" style="width: 28px; height: 28px; font-size: 11px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
    </div>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-name">Kopi <span>Elang Emas</span></div>
            <div class="brand-role">PORTAL SALES</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Produk</div>

            <a href="{{ route('sales.products') }}"
               class="nav-item {{ request()->routeIs('sales.products') ? 'active' : '' }}">
               <i data-lucide="layout-grid" class="nav-icon"></i>
                Katalog Produk
            </a>

            <div class="nav-section">Pengajuan Barang</div>

            <a href="{{ route('sales.orders.create') }}"
               class="nav-item {{ request()->routeIs('sales.orders.create') ? 'active' : '' }}">
               <i data-lucide="plus-circle" class="nav-icon"></i>
                Buat Pengajuan
            </a>

            <a href="{{ route('sales.orders.index') }}"
               class="nav-item {{ request()->routeIs('sales.orders.index') || request()->routeIs('sales.orders.show') ? 'active' : '' }}">
               <i data-lucide="clock" class="nav-icon"></i>
                Riwayat Pengajuan
            </a>

            <div class="nav-section">Pengiriman ke Toko</div>

            <a href="{{ route('sales.delivery-reports.create') }}"
               class="nav-item {{ request()->routeIs('sales.delivery-reports.create') ? 'active' : '' }}">
               <i data-lucide="send" class="nav-icon"></i>
                Buat Laporan Kirim
            </a>

            <a href="{{ route('sales.delivery-reports.index') }}"
               class="nav-item {{ request()->routeIs('sales.delivery-reports.index') || request()->routeIs('sales.delivery-reports.show') ? 'active' : '' }}">
               <i data-lucide="file-text" class="nav-icon"></i>
                Riwayat Pengiriman
            </a>

            <a href="{{ route('sales.deposits.index') }}"
               class="nav-item {{ request()->routeIs('sales.deposits*') ? 'active' : '' }}">
               <i data-lucide="wallet" class="nav-icon"></i>
                Setoran Uang
            </a>

            <a href="{{ route('sales.returns.index') }}"
               class="nav-item {{ request()->routeIs('sales.returns*') ? 'active' : '' }}">
               <i data-lucide="corner-up-left" class="nav-icon"></i>
                Return Barang
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="flex:1;min-width:0;">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-label">Sales</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <span class="topbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <div class="page-body">
            @if(session('success'))
                <div class="flash flash-success">
                    <i data-lucide="check-circle-2" style="width:16px;height:16px;"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">
                    <i data-lucide="alert-triangle" style="width:16px;height:16px;"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-open');
        }
        lucide.createIcons();
    </script>

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

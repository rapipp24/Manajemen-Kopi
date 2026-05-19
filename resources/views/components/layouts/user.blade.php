<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — Kopi Elang Emas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brown:       #92400e;
            --brown-light: #fef3c7;
            --brown-hover: #78350f;
            --cream:       #fffbf5;
            --sidebar-w:   220px;
            --text:        #1c1917;
            --muted:       #78716c;
            --border:      #e7e5e4;
            --surface:     #ffffff;
            --bg:          #f5f0eb;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ───────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
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

        .brand-name span { color: var(--brown); }

        .brand-role {
            font-size: 11px;
            font-weight: 500;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-top: 4px;
        }

        .sidebar-nav {
            padding: 12px 12px;
            flex: 1;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            color: #a8a29e;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 8px 8px 4px;
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
            transition: all 0.15s;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: var(--bg);
            color: var(--text);
        }

        .nav-item.active {
            background: var(--brown-light);
            color: var(--brown);
            font-weight: 600;
        }

        .nav-item svg { flex-shrink: 0; opacity: 0.8; }
        .nav-item.active svg { opacity: 1; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            border-radius: 8px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--brown-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: var(--brown);
            flex-shrink: 0;
        }

        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-label { font-size: 11px; color: var(--muted); }

        .btn-logout {
            width: 100%;
            margin-top: 8px;
            padding: 8px;
            background: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            font-family: inherit;
        }

        .btn-logout:hover { border-color: #fca5a5; color: #ef4444; background: #fef2f2; }

        /* ── Main Content ───────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }

        .topbar-breadcrumb {
            font-size: 13px;
            color: var(--muted);
        }

        .page-body {
            padding: 28px 32px;
            max-width: 1100px;
            width: 100%;
        }

        /* ── Flash Messages ─────────────────────────── */
        .flash {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .flash-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-name">Kopi <span>Elang Emas</span></div>
            <div class="brand-role">Portal Sales</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu</div>

            <a href="{{ route('sales.products') }}"
               class="nav-item {{ request()->routeIs('sales.products') ? 'active' : '' }}">
                <i data-lucide="layout-grid" style="width:16px;height:16px;"></i>
                Katalog Produk
            </a>

            <div class="nav-section-label">Pengajuan Barang</div>

            <a href="{{ route('sales.orders.create') }}"
               class="nav-item {{ request()->routeIs('sales.orders.create') ? 'active' : '' }}">
                <i data-lucide="plus-circle" style="width:16px;height:16px;"></i>
                Buat Pengajuan
            </a>

            <a href="{{ route('sales.orders.index') }}"
               class="nav-item {{ request()->routeIs('sales.orders.index') || request()->routeIs('sales.orders.show') ? 'active' : '' }}">
                <i data-lucide="clock" style="width:16px;height:16px;"></i>
                Riwayat Pengajuan
            </a>

            <div class="nav-section-label">Pengiriman</div>

            <a href="{{ route('sales.delivery-reports.create') }}"
               class="nav-item {{ request()->routeIs('sales.delivery-reports.create') ? 'active' : '' }}">
                <i data-lucide="send" style="width:16px;height:16px;"></i>
                Buat Laporan Kirim
            </a>

            <a href="{{ route('sales.delivery-reports.index') }}"
               class="nav-item {{ request()->routeIs('sales.delivery-reports.index') || request()->routeIs('sales.delivery-reports.show') ? 'active' : '' }}">
                <i data-lucide="file-text" style="width:16px;height:16px;"></i>
                Riwayat Pengiriman
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info">
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

    {{-- Main --}}
    <div class="main">
        <div class="topbar">
            <span class="topbar-title">{{ $title ?? 'Dashboard' }}</span>
            <span class="topbar-breadcrumb">{{ now()->translatedFormat('l, d M Y') }}</span>
        </div>

        <div class="page-body">
            @if(session('success'))
                <div class="flash flash-success">
                    <i data-lucide="check-circle" style="width:16px;height:16px;"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">
                    <i data-lucide="alert-circle" style="width:16px;height:16px;"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>

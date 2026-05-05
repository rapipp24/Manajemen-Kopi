<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} · Manajemen Kopi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Lora:wght@500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brown-950: #1c0f05;
            --brown-900: #231510;
            --brown-800: #3b1f12;
            --brown-700: #5c2d18;
            --brown-500: #92400e;
            --brown-400: #b45309;
            --brown-200: #d4a274;
            --brown-100: #f0dcc8;
            --cream-50:  #fdfaf6;
            --cream-100: #f7f0e6;
            --cream-200: #edddc8;
            --green-800: #1a3a2a;
            --green-600: #2d6a4f;
            --green-100: #d8f3dc;
            --text-main: #2c1a0e;
            --text-mid:  #6b4c35;
            --text-muted:#9e7c62;
            --border:    #e8d8c4;
            --sidebar-w: 252px;
            --topbar-h:  60px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--cream-50);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* ═══════════════ SIDEBAR ═══════════════ */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--brown-900);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            border-right: 1px solid var(--brown-800);
        }

        .sidebar-brand {
            padding: 22px 18px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2px;
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--brown-500), var(--brown-400));
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .brand-name {
            font-family: 'Lora', serif;
            font-size: 16px;
            font-weight: 600;
            color: var(--cream-100);
            line-height: 1.2;
        }

        .brand-tagline {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.3px;
            padding-left: 44px;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 14px 10px;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-group {
            margin-bottom: 4px;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 600;
            color: #6b5242;
            letter-spacing: 1.1px;
            text-transform: uppercase;
            padding: 10px 10px 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            border-radius: 7px;
            color: #c4a882;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 1px;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: var(--cream-100);
        }

        .nav-item.active {
            background: rgba(180, 83, 9, 0.18);
            color: #f5d9a8;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 18px;
            background: var(--brown-400);
            border-radius: 0 2px 2px 0;
        }

        .nav-item svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            opacity: 0.8;
        }

        .nav-item.active svg { opacity: 1; }

        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.05);
            margin: 10px 10px;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 14px 18px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brown-500), var(--brown-700));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fef3c7;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #e5c9a8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: var(--text-muted);
        }

        .btn-logout-small {
            background: none;
            border: none;
            color: #6b5242;
            cursor: pointer;
            padding: 4px;
            border-radius: 5px;
            transition: color 0.15s;
            display: flex;
            align-items: center;
        }

        .btn-logout-small:hover { color: #ef9a9a; }
        .btn-logout-small svg { width: 15px; height: 15px; }

        /* ═══════════════ TOPBAR ═══════════════ */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--cream-50);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 100;
        }

        .topbar-title {
            font-family: 'Lora', serif;
            font-size: 18px;
            font-weight: 500;
            color: var(--text-main);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-date {
            font-size: 12.5px;
            color: var(--text-muted);
            padding-right: 12px;
            border-right: 1px solid var(--border);
        }

        .topbar-badge {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            background: #fef9f0;
            border: 1px solid var(--brown-100);
            color: var(--brown-500);
        }

        /* ═══════════════ MAIN CONTENT ═══════════════ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            flex: 1;
            padding: 24px 28px;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* Flash messages */
        .flash-success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-left: 3px solid #22c55e;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13.5px;
        }

        .flash-error {
            background: #fff5f5;
            border: 1px solid #fca5a5;
            border-left: 3px solid #ef4444;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13.5px;
        }
    </style>
</head>
<body>

    <!-- ═══ SIDEBAR ═══ -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <div class="brand-icon">☕</div>
                <span class="brand-name">Kopi Nusantara</span>
            </div>
            <span class="brand-tagline">Panel Manajemen</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group">
                <p class="nav-label">Ringkasan</p>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Beranda
                </a>
            </div>

            <div class="nav-divider"></div>

            <div class="nav-group">
                <p class="nav-label">Data Master</p>
                <a href="{{ route('admin.units.index') }}"
                   class="nav-item {{ request()->routeIs('admin.units*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Satuan
                </a>
                <a href="{{ route('admin.suppliers.index') }}"
                   class="nav-item {{ request()->routeIs('admin.suppliers*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                    Supplier
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Customer
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    Bahan Baku
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/>
                    </svg>
                    Produk Kopi
                </a>
            </div>

            <div class="nav-divider"></div>

            <div class="nav-group">
                <p class="nav-label">Operasional</p>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Terima Bahan
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    Produksi
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                    Packing
                </a>
            </div>

            <div class="nav-divider"></div>

            <div class="nav-group">
                <p class="nav-label">Penjualan</p>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Catat Penjualan
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Order Masuk
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                    </svg>
                    Return
                </a>
            </div>

            <div class="nav-divider"></div>

            <div class="nav-group">
                <p class="nav-label">Laporan</p>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Laporan Stok
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Absensi
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ═══ TOPBAR ═══ -->
    <header class="topbar">
        <span class="topbar-title">{{ $title ?? 'Beranda' }}</span>
        <div class="topbar-right" style="display: flex; align-items: center; gap: 20px;">
            <span class="topbar-date" id="current-date"></span>
            <span class="topbar-badge" style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Admin</span>
            
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- ═══ MAIN CONTENT ═══ -->
    <main class="main-wrapper">
        @if (session('success'))
            <div class="flash-success">✓ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="flash-error">⚠ {{ session('error') }}</div>
        @endif

        {{ $slot }}
    </main>

    <script>
        // Isi tanggal di topbar
        const d = new Date();
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('current-date').textContent = d.toLocaleDateString('id-ID', opts);
    </script>
</body>
</html>

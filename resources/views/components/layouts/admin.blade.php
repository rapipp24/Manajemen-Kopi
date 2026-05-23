<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} · Manajemen Kopi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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
            font-family: 'Inter', sans-serif;
            background: var(--cream-50);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* ═══════════════ SIDEBAR ═══════════════ */
        /* ═══════════════ SIDEBAR ═══════════════ */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: #110c08; /* Deep Coffee Black */
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            border-right: 1px solid rgba(255,255,255,0.05);
            box-shadow: 10px 0 30px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 24px 20px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.03), transparent);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #92400e, #78350f);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(146, 64, 14, 0.3);
            flex-shrink: 0;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 800;
            color: #f3f4f6;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .brand-tagline {
            font-size: 10px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
            padding-left: 50px;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 20px 12px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        /* Custom Scrollbar for Webkit */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.2);
        }

        .nav-group {
            margin-bottom: 24px;
        }

        .nav-label {
            font-size: 11px;
            font-weight: 700;
            color: #4b5563;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0 12px 10px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 4px;
            border: 1px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: #f3f4f6;
            transform: translateX(4px);
        }

        .nav-item.active {
            background: rgba(146, 64, 14, 0.1);
            color: #fbbf24;
            border-color: rgba(251, 191, 36, 0.1);
            font-weight: 600;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.7;
            transition: all 0.2s;
        }

        .nav-item:hover svg, .nav-item.active svg {
            opacity: 1;
            color: #fbbf24;
        }

        .nav-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.05), transparent);
            margin: 16px 0;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background: rgba(255,255,255,0.03);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #92400e;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 700;
            color: #f3f4f6;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: #6b7280;
            font-weight: 500;
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
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 600;
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
                <div class="brand-icon"></div>
                <span class="brand-name">Kopi Elang Emas</span>
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
                <a href="{{ route('admin.product-categories.index') }}"
                   class="nav-item {{ request()->routeIs('admin.product-categories*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Jenis Produk
                </a>
                <a href="{{ route('admin.customers.index') }}"
                   class="nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Member
                </a>
                <a href="{{ route('admin.raw-materials.index') }}"
                   class="nav-item {{ request()->routeIs('admin.raw-materials*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    <span>Bahan Baku</span>
                    @if($lowStockCount > 0)
                        <span style="background: #ef4444; color: white; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 10px; margin-left: auto;">
                            {{ $lowStockCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Manajemen User
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
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
                <a href="{{ route('admin.raw-material-receipts.index') }}" 
                   class="nav-item {{ request()->routeIs('admin.raw-material-receipts*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Terima Bahan
                </a>
                <a href="{{ route('admin.productions.index') }}"
                   class="nav-item {{ request()->routeIs('admin.productions*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    Produksi
                </a>
                <a href="{{ route('admin.packings.index') }}"
                   class="nav-item {{ request()->routeIs('admin.packings*') ? 'active' : '' }}">
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
                <a href="{{ route('admin.sales.index') }}" class="nav-item {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Catat Penjualan (Direct)
                </a>
                <a href="{{ route('admin.sales-orders.index') }}" class="nav-item {{ request()->routeIs('admin.sales-orders*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Pengajuan Barang Sales</span>
                    @if($pendingSalesOrderCount > 0)
                        <span style="background: #ef4444; color: white; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 10px; margin-left: auto;">
                            {{ $pendingSalesOrderCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.delivery-reports.index') }}" class="nav-item {{ request()->routeIs('admin.delivery-reports*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Monitoring Sales
                </a>
                <a href="{{ route('admin.sales-deposits.index') }}" class="nav-item {{ request()->routeIs('admin.sales-deposits*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Verifikasi Setoran</span>
                    @if(isset($pendingDepositCount) && $pendingDepositCount > 0)
                        <span style="background: #ef4444; color: white; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 10px; margin-left: auto;">
                            {{ $pendingDepositCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.returns.index') }}" class="nav-item {{ request()->routeIs('admin.returns*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                    </svg>
                    <span>Verifikasi Return</span>
                    @if(isset($pendingReturnCount) && $pendingReturnCount > 0)
                        <span style="background: #ef4444; color: white; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 10px; margin-left: auto;">
                            {{ $pendingReturnCount }}
                        </span>
                    @endif
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

            <div class="nav-divider"></div>

            <div class="nav-group">
                <p class="nav-label">Sistem</p>
                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan Nota
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
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout-small confirm-action" 
                            data-confirm-title="Keluar dari Sistem?" 
                            data-confirm-text="Anda harus login kembali untuk mengakses panel manajemen."
                            data-confirm-icon="question"
                            title="Logout">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ═══ TOPBAR ═══ -->
    <header class="topbar">
        <span class="topbar-title">{{ $title ?? 'Beranda' }}</span>
        <div class="topbar-right" style="display: flex; align-items: center; gap: 20px;">
            <span class="topbar-date" id="current-date"></span>
            <span class="topbar-badge" style="background: {{ auth()->user()->isAdmin() ? '#fef3c7' : '#e0f2fe' }}; color: {{ auth()->user()->isAdmin() ? '#92400e' : '#0369a1' }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                {{ auth()->user()->isAdmin() ? 'Admin' : 'Member' }}
            </span>
        </div>
    </header>

    <!-- ═══ MAIN CONTENT ═══ -->
    <main class="main-wrapper">
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        timerProgressBar: true,
                    });
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "{{ session('error') }}",
                        confirmButtonText: 'Tutup',
                        customClass: {
                            confirmButton: 'swal-confirm-btn'
                        },
                        buttonsStyling: false
                    });
                });
            </script>
        @endif

        {{ $slot }}
    </main>

    <script>
        // Persistence Sidebar Scroll (Ubah ke sessionStorage agar reset saat tab tutup)
        const sidebarNav = document.querySelector('.sidebar-nav');
        if (sidebarNav) {
            // Jika di halaman dashboard, pastikan scroll di atas
            if (window.location.pathname.endsWith('/admin/dashboard')) {
                sessionStorage.removeItem('sidebar-scroll');
            }

            const savedScroll = sessionStorage.getItem('sidebar-scroll');
            if (savedScroll) {
                sidebarNav.scrollTop = savedScroll;
            }

            sidebarNav.addEventListener('click', function(e) {
                if (e.target.closest('.nav-item')) {
                    sessionStorage.setItem('sidebar-scroll', sidebarNav.scrollTop);
                }
            });
        }

        // Tampilan Tanggal Statis
        const d = new Date();
        const dateOpts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const clockElement = document.getElementById('current-date');
        if (clockElement) {
            clockElement.textContent = d.toLocaleDateString('id-ID', dateOpts);
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert Configuration for Global Theme
        const SwalCustom = Swal.mixin({
            customClass: {
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn'
            },
            buttonsStyling: false
        });

        // Inject global styles for SweetAlert
        const style = document.createElement('style');
        style.innerHTML = `
            .swal-confirm-btn {
                background: #92400e !important;
                color: white !important;
                padding: 10px 24px !important;
                border-radius: 10px !important;
                font-weight: 700 !important;
                font-family: 'Inter', sans-serif !important;
                margin: 0 8px !important;
                border: none !important;
                cursor: pointer !important;
            }
            .swal-cancel-btn {
                background: #f5f5f4 !important;
                color: #78716c !important;
                padding: 10px 24px !important;
                border-radius: 10px !important;
                font-weight: 600 !important;
                font-family: 'Inter', sans-serif !important;
                margin: 0 8px !important;
                border: 1px solid #e7e5e4 !important;
                cursor: pointer !important;
            }
            .swal2-popup {
                border-radius: 20px !important;
                padding: 2em !important;
            }
            .swal2-title {
                color: #1c1917 !important;
                font-weight: 800 !important;
            }
            .swal2-html-container {
                color: #44403c !important;
            }
        `;
        document.head.appendChild(style);

        // Global Confirmation Handler
        document.addEventListener('click', function(e) {
            const confirmBtn = e.target.closest('.confirm-action');
            if (confirmBtn) {
                e.preventDefault();
                const form = confirmBtn.closest('form');
                const message = confirmBtn.getAttribute('data-confirm-text') || 'Apakah Anda yakin?';
                const title = confirmBtn.getAttribute('data-confirm-title') || 'Konfirmasi';
                const icon = confirmBtn.getAttribute('data-confirm-icon') || 'warning';

                Swal.fire({
                    title: title,
                    text: message,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'swal-confirm-btn',
                        cancelButton: 'swal-cancel-btn'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reset sidebar scroll jika ini adalah form logout
                        if (form && form.action.includes('logout')) {
                            sessionStorage.removeItem('sidebar-scroll');
                            localStorage.removeItem('sidebar-scroll'); // Bersihkan sisa localStorage lama juga
                        }

                        // If it's a submit button inside a form
                        if (form) {
                            // Add a hidden input to preserve the button value if it has a name
                            if (confirmBtn.name && confirmBtn.value) {
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = confirmBtn.name;
                                hiddenInput.value = confirmBtn.value;
                                form.appendChild(hiddenInput);
                            }
                            form.submit();
                        } else if (confirmBtn.tagName === 'A') {
                            window.location.href = confirmBtn.href;
                        }
                    }
                });
            }
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }

        document.querySelectorAll('.currency-input').forEach(function(input) {
            input.addEventListener('keyup', function(e) {
                this.value = formatRupiah(this.value);
            });
        });

        // Clean currency before submit
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                form.querySelectorAll('.currency-input').forEach(function(input) {
                    input.value = input.value.replace(/\./g, '');
                });
            });
        });
    </script>
</body>
</html>

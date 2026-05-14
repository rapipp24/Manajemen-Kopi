<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Produk' }} — Manajemen Kopi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #faf8f5;
            color: #1e293b;
            min-height: 100vh;
        }

        /* ── Navbar ──────────────────────────────── */
        .navbar {
            background: #1c1917;
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .navbar-brand {
            font-size: 18px;
            font-weight: 700;
            color: #f5f0eb;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link {
            color: #a8a29e;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.08);
            color: #f5f0eb;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar-user span {
            color: #a8a29e;
            font-size: 14px;
        }

        .btn-logout-user {
            padding: 7px 16px;
            border-radius: 8px;
            border: 1px solid #44403c;
            background: transparent;
            color: #a8a29e;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .btn-logout-user:hover {
            border-color: #ef4444;
            color: #ef4444;
        }

        /* ── Content ──────────────────────────────── */
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('sales.products') }}" class="navbar-brand">
            ☕ Manajemen Kopi
        </a>
        <div class="navbar-menu">
            <a href="{{ route('sales.products') }}"
               class="nav-link {{ request()->routeIs('sales.products') ? 'active' : '' }}">
                Produk
            </a>
            <a href="{{ route('sales.orders.index') }}" class="nav-link {{ request()->routeIs('sales.orders*') ? 'active' : '' }}">
                Pengajuan Barang
            </a>
        </div>
        <div class="navbar-user">
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout-user">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="content-wrapper">
        @if (session('success'))
            <div style="background:#dcfce7; border:1px solid #86efac; color:#166534;
                        padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px;">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </div>
</body>
</html>

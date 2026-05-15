<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Produk' }} — Kopi Elang Emas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f8f9fa;
            color: #1e293b;
            min-height: 100vh;
        }

        /* ── Navbar ──────────────────────────────── */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.025em;
        }

        .navbar-brand span {
            color: #92400e;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-link {
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            color: #0f172a;
            background: #f1f5f9;
        }

        .nav-link.active {
            color: #92400e;
            background: #fff7ed;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .btn-logout-user {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout-user:hover {
            border-color: #f87171;
            color: #ef4444;
            background: #fef2f2;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 40px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('sales.products') }}" class="navbar-brand">
           Kopi <span>Elang Emas</span>
        </a>
        <div class="navbar-menu">
            <a href="{{ route('sales.products') }}"
               class="nav-link {{ request()->routeIs('sales.products') ? 'active' : '' }}">
                <i data-lucide="package" style="width: 18px; height: 18px;"></i>
                Katalog
            </a>
            <a href="{{ route('sales.orders.index') }}" class="nav-link {{ request()->routeIs('sales.orders*') ? 'active' : '' }}">
                <i data-lucide="history" style="width: 18px; height: 18px;"></i>
                Riwayat Pengajuan
            </a>
        </div>
        <div class="navbar-user">
            <span class="user-name">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout-user">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="content-wrapper">
        @if (session('success'))
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534;
                        padding:14px 20px; border-radius:12px; margin-bottom:24px; font-size:14px; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

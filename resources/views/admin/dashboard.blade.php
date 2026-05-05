<x-layouts.admin>
    <x-slot name="title">Beranda</x-slot>

    <style>
        /* ── Greeting Banner ───────────────────────── */
        .greeting {
            margin-bottom: 28px;
        }

        .greeting-text {
            font-family: 'Lora', serif;
            font-size: 22px;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .greeting-sub {
            font-size: 13.5px;
            color: var(--text-muted);
        }

        /* ── Stats Row ───────────────────────────────── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 20px;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(92, 45, 24, 0.08);
            transform: translateY(-1px);
        }

        .stat-card:first-child {
            border-left: 3px solid var(--brown-400);
        }

        .stat-card:nth-child(2) {
            border-left: 3px solid #2d6a4f;
        }

        .stat-card:nth-child(3) {
            border-left: 3px solid #d97706;
        }

        .stat-card:nth-child(4) {
            border-left: 3px solid #c2410c;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-value.empty {
            font-size: 22px;
            color: #c9b49e;
        }

        .stat-note {
            font-size: 11.5px;
            color: var(--text-muted);
        }

        /* ── Body Grid ───────────────────────────────── */
        .body-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 18px;
        }

        /* ── Section Card ────────────────────────────── */
        .section-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .section-head {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-head h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
        }

        .section-head a {
            font-size: 12px;
            color: var(--brown-400);
            text-decoration: none;
        }

        .section-head a:hover { text-decoration: underline; }

        .section-body {
            padding: 20px;
        }

        /* ── Quick Actions ───────────────────────────── */
        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 16px;
        }

        .qa-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--cream-50);
            text-decoration: none;
            color: var(--text-mid);
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s;
        }

        .qa-item:hover {
            background: var(--cream-100);
            border-color: var(--brown-200);
            color: var(--text-main);
        }

        .qa-icon {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .qa-icon.brown  { background: #fef3c7; }
        .qa-icon.green  { background: #d8f3dc; }
        .qa-icon.orange { background: #ffedd5; }
        .qa-icon.red    { background: #fee2e2; }

        /* ── Activity Feed ───────────────────────────── */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid #faf5ef;
        }

        .activity-item:last-child { border-bottom: none; }

        .activity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 5px;
            flex-shrink: 0;
        }

        .activity-dot.brown  { background: var(--brown-400); }
        .activity-dot.green  { background: #2d6a4f; }
        .activity-dot.orange { background: #d97706; }

        .activity-text {
            font-size: 13px;
            color: var(--text-mid);
            line-height: 1.5;
        }

        .activity-time {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .empty-state {
            padding: 32px 20px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
        }

        .empty-state span {
            display: block;
            font-size: 28px;
            margin-bottom: 8px;
            opacity: 0.5;
        }
    </style>

    <!-- Greeting -->
    <div class="greeting">
        @php
            $hour = (int) now()->setTimezone('Asia/Jakarta')->format('H');
            $salam = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 18 ? 'Selamat sore' : 'Selamat malam'));
        @endphp
        <p class="greeting-text">{{ $salam }}, {{ explode(' ', auth()->user()->name)[0] }}. ☕</p>
        <p class="greeting-sub">Ini ringkasan aktivitas produksi dan penjualan hari ini.</p>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                Stok Bahan Baku
            </div>
            <div class="stat-value {{ $stats['raw_material_count'] == 0 ? 'empty' : '' }}">
                {{ $stats['raw_material_count'] }} <span style="font-size: 14px; font-weight: 500;">jenis</span>
            </div>
            <div class="stat-note">{{ $stats['raw_material_count'] == 0 ? 'Data belum tersedia' : 'Bahan baku terdaftar' }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" /></svg>
                Barang Jadi
            </div>
            <div class="stat-value {{ $stats['product_count'] == 0 ? 'empty' : '' }}">
                {{ $stats['product_count'] }} <span style="font-size: 14px; font-weight: 500;">produk</span>
            </div>
            <div class="stat-note">{{ $stats['product_count'] == 0 ? 'Data belum tersedia' : 'Produk siap jual' }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                Order Masuk
            </div>
            <div class="stat-value {{ $stats['order_count'] == 0 ? 'empty' : '' }}">
                {{ $stats['order_count'] }} <span style="font-size: 14px; font-weight: 500;">order</span>
            </div>
            <div class="stat-note">Bulan ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                Penjualan
            </div>
            <div class="stat-value {{ $stats['total_sales'] == 0 ? 'empty' : '' }}">
                <span style="font-size: 16px; font-weight: 600;">Rp</span> {{ number_format($stats['total_sales'], 0, ',', '.') }}
            </div>
            <div class="stat-note">Bulan ini</div>
        </div>
    </div>

    <!-- Body Grid -->
    <div class="body-grid">

        <!-- Aktivitas Terakhir -->
        <div class="section-card">
            <div class="section-head">
                <h3>Aktivitas Terakhir</h3>
                <a href="#">Lihat semua</a>
            </div>
            <div class="activity-list">
                @forelse($recent_activities as $activity)
                <div style="display: flex; gap: 12px; padding: 12px; border-bottom: 1px solid #f8fafc; align-items: center;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ $activity['icon'] == 'user-plus' ? '#fffbeb' : '#f0f9ff' }}; display: flex; align-items: center; justify-content: center; color: {{ $activity['icon'] == 'user-plus' ? '#92400e' : '#0284c7' }}; flex-shrink: 0;">
                        @if($activity['icon'] == 'user-plus')
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        @else
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <p style="font-size: 13px; font-weight: 500; color: #1e293b;">{{ $activity['description'] }}</p>
                        <p style="font-size: 11px; color: #64748b;">{{ $activity['time_human'] }}</p>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mx-auto mb-2 text-gray-400 opacity-50"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125h-9.75ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                    Belum ada aktivitas tercatat.<br>Mulai dengan menambah data supplier atau bahan baku.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="section-card">
            <div class="section-head">
                <h3>Aksi Cepat</h3>
            </div>
            <div class="quick-actions">
                <a href="#" class="qa-item">
                    <div class="qa-icon brown"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg></div>
                    Terima Bahan Baku
                </a>
                <a href="#" class="qa-item">
                    <div class="qa-icon green"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.99l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></div>
                    Mulai Produksi
                </a>
                <a href="#" class="qa-item">
                    <div class="qa-icon orange"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg></div>
                    Catat Packing
                </a>
                <a href="#" class="qa-item">
                    <div class="qa-icon red"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg></div>
                    Catat Penjualan
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="qa-item">
                    <div class="qa-icon brown"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg></div>
                    Kelola Supplier
                </a>
                <a href="{{ route('admin.units.index') }}" class="qa-item">
                    <div class="qa-icon green"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h.008v.008H6V7.5Z" /></svg></div>
                    Kelola Satuan
                </a>
            </div>
        </div>

    </div>

</x-layouts.admin>

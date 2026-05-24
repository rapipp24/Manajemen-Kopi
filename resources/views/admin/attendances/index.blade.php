<x-layouts.admin>
    <x-slot name="title">Papan Absensi Karyawan Gudang</x-slot>

    <style>
        /* Warm off-white background senada brand kopi */
        body {
            background-color: #faf7f2;
        }

        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .page-title-block h1 {
            font-size: 22px;
            font-weight: 800;
            color: #1c1917;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .page-title-block p {
            font-size: 13.5px;
            color: #78716c;
            margin: 4px 0 0 0;
        }

        /* Date Picker Card - Premium Minimalist */
        .date-picker-card {
            background: #fff;
            border: 1px solid #e7e5e4;
            border-radius: 12px;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .date-picker-card label {
            font-size: 11px;
            font-weight: 700;
            color: #78716c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .date-control {
            padding: 6px 12px;
            border: 1px solid #d6d3d1;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #1c1917;
            background: #fff;
            outline: none;
            transition: all 0.15s;
            cursor: pointer;
        }
        .date-control:focus {
            border-color: #9a4a12;
            box-shadow: 0 0 0 3px rgba(154,74,18,0.1);
        }

        /* Summary Cards - Premium Mockup Style */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .summary-card {
            background: #fff;
            border: 1px solid #e7e5e4;
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.01);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.04);
        }

        /* Accent vertical bar */
        .summary-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
        }

        .card-header-flex {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .icon-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .icon-circle svg {
            width: 14px;
            height: 14px;
        }

        .summary-card .label {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.1;
            white-space: nowrap;
        }

        .theme-belum .label {
            white-space: normal;
        }

        .summary-card .val {
            font-size: 34px;
            font-weight: 800;
            color: #111827;
            line-height: 1;
            margin-top: 4px;
        }

        /* Summary Colors & Accents */
        .theme-total::before { background-color: #3b82f6; }
        .theme-total .icon-circle { background-color: #eff6ff; color: #3b82f6; }

        .theme-hadir::before { background-color: #10b981; }
        .theme-hadir .icon-circle { background-color: #ecfdf5; color: #10b981; }

        .theme-izin::before { background-color: #f59e0b; }
        .theme-izin .icon-circle { background-color: #fffbeb; color: #f59e0b; }

        .theme-sakit::before { background-color: #06b6d4; }
        .theme-sakit .icon-circle { background-color: #ecfeff; color: #06b6d4; }

        .theme-alfa::before { background-color: #ef4444; }
        .theme-alfa .icon-circle { background-color: #fef2f2; color: #ef4444; }

        .theme-belum::before { background-color: #64748b; }
        .theme-belum .icon-circle { background-color: #f8fafc; color: #64748b; }

        /* Search & Filter Row */
        .controls-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .search-container {
            position: relative;
            width: 100%;
            max-width: 320px;
        }
        .search-input {
            width: 100%;
            padding: 9px 12px 9px 36px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13.5px;
            color: #1c1917;
            background: #fff;
            outline: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            transition: all 0.15s;
            box-sizing: border-box;
        }
        .search-input:focus {
            border-color: #9a4a12;
            box-shadow: 0 0 0 3px rgba(154,74,18,0.08);
        }
        .search-icon-wrapper {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            display: flex;
            align-items: center;
        }
        .search-icon-wrapper svg {
            width: 16px;
            height: 16px;
        }

        .filter-buttons-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .sort-select-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-secondary-control {
            background: #fff;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            padding: 8px 30px 8px 32px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            transition: all 0.15s;
            appearance: none;
            outline: none;
        }
        .btn-secondary-control:hover {
            background: #faf9f6;
            color: #1f2937;
            border-color: #d1d5db;
        }
        
        /* Custom icons for select wrapper */
        .select-sort-icon {
            position: absolute;
            left: 12px;
            pointer-events: none;
            color: #6b7280;
            display: flex;
            align-items: center;
        }
        .select-sort-icon svg {
            width: 14px;
            height: 14px;
        }
        
        .select-chevron-icon {
            position: absolute;
            right: 12px;
            pointer-events: none;
            color: #6b7280;
            display: flex;
            align-items: center;
        }
        .select-chevron-icon svg {
            width: 12px;
            height: 12px;
        }

        /* Large Board Card */
        .board-card {
            background: #fff;
            border: 1px solid #e7e5e4;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.01), 0 2px 4px -1px rgba(0,0,0,0.006);
        }

        /* Coffee Header Board */
        .board-header {
            background-color: #a14d12; /* Coffee Accent */
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            flex-wrap: wrap;
            gap: 12px;
        }
        .board-header-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .board-header-left svg {
            width: 18px;
            height: 18px;
            color: rgba(255,255,255,0.85);
        }
        .board-header-left span {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
        }
        .board-header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .board-stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            color: rgba(255,255,255,0.9);
            font-weight: 600;
        }
        .board-stat-item svg {
            width: 15px;
            height: 15px;
            color: rgba(255,255,255,0.8);
        }

        /* Table Style */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        thead tr {
            background: #faf9f6;
            border-bottom: 1px solid #e7e5e4;
        }
        th {
            padding: 12px 20px;
            font-size: 10.5px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.75px;
        }
        tbody tr {
            border-bottom: 1px solid #f2efe9;
            transition: background 0.1s;
        }
        tbody tr:hover {
            background: #fafaf8;
        }
        tbody tr:last-child {
            border-bottom: none;
        }
        td {
            padding: 14px 20px;
            font-size: 13.5px;
            color: #111827;
            vertical-align: middle;
        }

        /* Status badges */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11.5px;
            font-weight: 700;
            white-space: nowrap;
        }
        .status-hadir { background-color: #ecfdf5; color: #047857; }
        .status-izin  { background-color: #fffbeb; color: #b45309; }
        .status-sakit { background-color: #f0fdfa; color: #0f766e; }
        .status-alfa  { background-color: #fef2f2; color: #b91c1c; }
        .status-belum { background-color: #f1f5f9; color: #475569; }

        .note-empty {
            color: #cbd5e1;
            font-size: 13px;
        }

        /* Quick Action Buttons (Compact bordered style like mockup) */
        .action-flex {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .btn-quick {
            background: #fff;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            padding: 5px 10px;
            border-radius: 7px;
            font-size: 11.5px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.15s;
        }
        .btn-quick svg {
            width: 12px;
            height: 12px;
            color: #6b7280;
        }
        
        .btn-quick:hover:not(:disabled) {
            background: #f9fafb;
            color: #111827;
            border-color: #d1d5db;
        }

        .btn-quick:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Selected Status styles */
        .btn-quick.active-hadir {
            background-color: #ecfdf5;
            color: #047857;
            border-color: #a7f3d0;
            font-weight: 700;
        }
        .btn-quick.active-hadir svg { color: #047857; }

        .btn-quick.active-izin {
            background-color: #fffbeb;
            color: #b45309;
            border-color: #fde68a;
            font-weight: 700;
        }
        .btn-quick.active-izin svg { color: #b45309; }

        .btn-quick.active-sakit {
            background-color: #f0fdfa;
            color: #0f766e;
            border-color: #99f6e4;
            font-weight: 700;
        }
        .btn-quick.active-sakit svg { color: #0f766e; }

        .btn-quick.active-alfa {
            background-color: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
            font-weight: 700;
        }
        .btn-quick.active-alfa svg { color: #b91c1c; }

        /* Premium Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(28, 25, 23, 0.4); /* warm slate dark color */
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 24px;
            border: 1px solid #e7e5e4;
            width: 100%;
            max-width: 440px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalSlideUp {
            from { transform: translateY(16px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }
        .modal-title {
            font-size: 16px;
            font-weight: 800;
            color: #1c1917;
        }
        .close-btn {
            font-size: 22px;
            font-weight: 700;
            color: #a8a29e;
            cursor: pointer;
            border: none;
            background: none;
            line-height: 1;
        }
        .close-btn:hover {
            color: #1c1917;
        }
        .modal-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #44403c;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .modal-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d6d3d1;
            border-radius: 8px;
            font-size: 13.5px;
            color: #1c1917;
            background: #fff;
            box-sizing: border-box;
            outline: none;
            resize: vertical;
            min-height: 80px;
            transition: border-color 0.15s;
        }
        .modal-textarea:focus {
            border-color: #a14d12;
            box-shadow: 0 0 0 3px rgba(161,77,18,0.08);
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 20px;
        }
        .btn-cancel {
            background: #fff;
            color: #78716c;
            border: 1px solid #d6d3d1;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-cancel:hover { background: #fafaf9; }
        .btn-confirm {
            background: #a14d12;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-confirm:hover { background: #853e0d; }

        /* Empty Search Row */
        .empty-search-row td {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 24px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .summary-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
            .theme-belum .label {
                white-space: nowrap;
            }
        }
        @media (max-width: 640px) {
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .theme-belum .label {
                white-space: normal;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .date-picker-card {
                width: 100%;
                box-sizing: border-box;
                justify-content: space-between;
            }
            .controls-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .search-container {
                max-width: 100%;
            }
            .filter-buttons-group {
                width: 100%;
                justify-content: flex-start;
            }
        }
        @media (max-width: 400px) {
            .summary-grid {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }
    </style>

    <div class="page-container">
        @if(session('success'))
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:13px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:13px;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:13px;">
                <ul style="margin:0; padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Header --}}
        <div class="page-header">
            <div class="page-title-block">
                <h1>Absensi Karyawan Gudang</h1>
                <p>Pencatatan kehadiran harian karyawan gudang.</p>
            </div>
            
            <form action="{{ route('admin.attendances.index') }}" method="GET" id="date-form">
                <div class="date-picker-card">
                    <label for="date-select">Tanggal Absensi</label>
                    <input type="date" name="date" id="date-select" class="date-control" 
                           value="{{ $selectedDate }}" onchange="this.form.submit()">
                </div>
            </form>
        </div>

        {{-- 6 Summary Cards --}}
        <div class="summary-grid">
            {{-- Total Aktif --}}
            <div class="summary-card theme-total">
                <div class="card-header-flex">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                    <span class="label">Total Aktif</span>
                </div>
                <span class="val">{{ $summary['total_active'] }}</span>
            </div>

            {{-- Hadir --}}
            <div class="summary-card theme-hadir">
                <div class="card-header-flex">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="label">Hadir</span>
                </div>
                <span class="val">{{ $summary['hadir'] }}</span>
            </div>

            {{-- Izin --}}
            <div class="summary-card theme-izin">
                <div class="card-header-flex">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="label">Izin</span>
                </div>
                <span class="val">{{ $summary['izin'] }}</span>
            </div>

            {{-- Sakit --}}
            <div class="summary-card theme-sakit">
                <div class="card-header-flex">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <span class="label">Sakit</span>
                </div>
                <span class="val">{{ $summary['sakit'] }}</span>
            </div>

            {{-- Alfa --}}
            <div class="summary-card theme-alfa">
                <div class="card-header-flex">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="label">Alfa</span>
                </div>
                <span class="val">{{ $summary['alfa'] }}</span>
            </div>

            {{-- Belum Dicatat --}}
            <div class="summary-card theme-belum">
                <div class="card-header-flex">
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <span class="label">Belum Dicatat</span>
                </div>
                <span class="val">{{ $summary['belum_dicatat'] }}</span>
            </div>
        </div>

        {{-- Search & Sorters Control Row --}}
        <div class="controls-row">
            {{-- Instant Frontend Search --}}
            <div class="search-container">
                <div class="search-icon-wrapper">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z"/>
                    </svg>
                </div>
                <input type="text" id="karyawan-search-input" class="search-input" placeholder="Cari karyawan...">
            </div>

            <div class="filter-buttons-group">
                {{-- Dropdown Urutkan --}}
                <div class="sort-select-wrapper">
                    <div class="select-sort-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
                        </svg>
                    </div>
                    <select id="sort-select" class="btn-secondary-control">
                        <option value="name-asc">Nama A-Z</option>
                        <option value="name-desc">Nama Z-A</option>
                        <option value="status">Status</option>
                    </select>
                    <div class="select-chevron-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Board --}}
        <div class="board-card">
            {{-- Coffee Styled Header --}}
            <div class="board-header">
                <div class="board-header-left">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/>
                    </svg>
                    <span>Absensi tanggal: {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</span>
                </div>
                
                <div class="board-header-right">
                    <div class="board-stat-item">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A9.342 9.342 0 0012.438 14H12.43a9.342 9.342 0 00-2.562.237M12.43 14a9.337 9.337 0 00-4.122-.952 4.125 4.125 0 00-7.533 2.493M12.43 14v-.003c0-1.113.285-2.16.786-3.07M12.75 7.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 8.25a2.25 2.25 0 120-4.5 2.25 2.25 0 020 4.5z"/>
                        </svg>
                        <span>{{ $summary['total_active'] }} karyawan aktif</span>
                    </div>
                    <div class="board-stat-item">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $summary['belum_dicatat'] }} belum dicatat</span>
                    </div>
                </div>
            </div>

            {{-- Table Grid --}}
            <div class="table-responsive">
                <table id="employees-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Nama Karyawan</th>
                            <th style="width: 15%;">Status Hari Ini</th>
                            <th style="width: 25%;">Catatan / Alasan</th>
                            <th style="width: 35%;">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody id="employees-tbody">
                        @forelse($employees as $emp)
                        @php
                            $att = $attendances->get($emp->id);
                            $currentStatus = $att ? $att->status : 'belum_dicatat';
                        @endphp
                        <tr class="employee-row" data-name="{{ strtolower($emp->name) }}" data-status="{{ $currentStatus }}">
                            <td class="emp-name-cell" style="font-weight: 700; font-size: 14.5px;">
                                {{ $emp->name }}
                                @if(!$emp->is_active)
                                    <span style="color:#ef4444; font-size:11px; font-weight:700; margin-left:4px; font-style:italic;">(Nonaktif)</span>
                                @endif
                            </td>
                            <td>
                                @if($currentStatus === 'belum_dicatat')
                                    <span class="badge-pill status-belum">Belum Dicatat</span>
                                @elseif($currentStatus === 'hadir')
                                    <span class="badge-pill status-hadir">Hadir</span>
                                @elseif($currentStatus === 'izin')
                                    <span class="badge-pill status-izin">Izin</span>
                                @elseif($currentStatus === 'sakit')
                                    <span class="badge-pill status-sakit">Sakit</span>
                                @elseif($currentStatus === 'alfa')
                                    <span class="badge-pill status-alfa">Alfa</span>
                                @endif
                            </td>
                            <td class="note-container">
                                @if($att && $att->note)
                                    <span style="color: #475569; font-weight: 500;">{{ $att->note }}</span>
                                @else
                                    <span class="note-empty">--</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-flex">
                                    {{-- Hadir --}}
                                    <form action="{{ route('admin.attendances.mark') }}" method="POST" class="quick-mark-form">
                                        @csrf
                                        <input type="hidden" name="warehouse_employee_id" value="{{ $emp->id }}">
                                        <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
                                        <input type="hidden" name="status" value="hadir">
                                        <button type="submit" class="btn-quick btn-hadir {{ $currentStatus === 'hadir' ? 'active-hadir' : '' }}" 
                                                {{ $currentStatus === 'hadir' ? 'disabled' : '' }}>
                                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Hadir
                                        </button>
                                    </form>

                                    {{-- Izin --}}
                                    <button type="button" class="btn-quick btn-izin {{ $currentStatus === 'izin' ? 'active-izin' : '' }}"
                                            onclick="openIzinModal('{{ $emp->id }}', '{{ $emp->name }}', '{{ $att ? $att->note : '' }}')">
                                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Izin
                                    </button>

                                    {{-- Sakit --}}
                                    <form action="{{ route('admin.attendances.mark') }}" method="POST" class="quick-mark-form">
                                        @csrf
                                        <input type="hidden" name="warehouse_employee_id" value="{{ $emp->id }}">
                                        <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
                                        <input type="hidden" name="status" value="sakit">
                                        <button type="submit" class="btn-quick btn-sakit {{ $currentStatus === 'sakit' ? 'active-sakit' : '' }}"
                                                {{ $currentStatus === 'sakit' ? 'disabled' : '' }}>
                                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            Sakit
                                        </button>
                                    </form>

                                    {{-- Alfa --}}
                                    <form action="{{ route('admin.attendances.mark') }}" method="POST" class="quick-mark-form">
                                        @csrf
                                        <input type="hidden" name="warehouse_employee_id" value="{{ $emp->id }}">
                                        <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
                                        <input type="hidden" name="status" value="alfa">
                                        <button type="submit" class="btn-quick btn-alfa {{ $currentStatus === 'alfa' ? 'active-alfa' : '' }}"
                                                {{ $currentStatus === 'alfa' ? 'disabled' : '' }}>
                                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Alfa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                Tidak ada karyawan gudang aktif untuk tanggal terpilih.
                            </td>
                        </tr>
                        @endforelse
                        
                        {{-- Row placeholder untuk pencarian kosong --}}
                        <tr id="empty-search-row" class="empty-search-row" style="display: none;">
                            <td colspan="4">Tidak ada karyawan yang cocok.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Premium Modal untuk Izin --}}
    <div id="izinModal" class="modal">
        <div class="modal-content">
            <form action="{{ route('admin.attendances.mark') }}" method="POST" id="izin-form" class="quick-mark-form">
                @csrf
                <div class="modal-header">
                    <span class="modal-title">Form Izin Karyawan</span>
                    <button type="button" class="close-btn" onclick="closeIzinModal()">&times;</button>
                </div>
                
                <input type="hidden" name="warehouse_employee_id" id="izin-emp-id">
                <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
                <input type="hidden" name="status" value="izin">

                <div style="margin-bottom: 16px; background: #faf8f5; padding: 12px; border-radius: 8px; border: 1px solid #e7e5e4;">
                    <span style="font-size:12px; color:#64748b; font-weight:700; text-transform: uppercase;">Nama Karyawan</span>
                    <div style="font-size:15px; color:#1c1917; font-weight:800; margin-top:4px;" id="izin-emp-name"></div>
                </div>

                <div>
                    <label for="izin-note" class="modal-label">Alasan Izin <span style="color:#dc2626;">*</span></label>
                    <textarea name="note" id="izin-note" class="modal-textarea" required
                              placeholder="Masukkan alasan izin karyawan secara detail (wajib)..."></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeIzinModal()">Batal</button>
                    <button type="submit" class="btn-confirm" id="btn-submit-izin">Simpan Izin</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        const modal = document.getElementById('izinModal');
        const empIdInput = document.getElementById('izin-emp-id');
        const empNameLabel = document.getElementById('izin-emp-name');
        const noteTextarea = document.getElementById('izin-note');

        function openIzinModal(id, name, existingNote) {
            empIdInput.value = id;
            empNameLabel.textContent = name;
            noteTextarea.value = existingNote || '';
            modal.style.display = 'flex';
        }

        function closeIzinModal() {
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target === modal) {
                closeIzinModal();
            }
        }

        // Prevent double submit and disable buttons when forms are submitted
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.quick-mark-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButtons = form.querySelectorAll('button[type="submit"]');
                    submitButtons.forEach(btn => {
                        btn.disabled = true;
                        btn.innerHTML = 'Proses...';
                    });
                    
                    // Disable other buttons in same row to prevent simultaneous clicking
                    const row = form.closest('tr');
                    if (row) {
                        const allButtons = row.querySelectorAll('.btn-quick');
                        allButtons.forEach(btn => {
                            btn.disabled = true;
                        });
                    }
                });
            });

            // --- Instant Frontend Search & Empty Message ---
            const searchInput = document.getElementById('karyawan-search-input');
            const rows = document.querySelectorAll('.employee-row');
            const emptySearchRow = document.getElementById('empty-search-row');

            function performSearch() {
                const query = searchInput.value.toLowerCase().trim();
                let matchCount = 0;

                rows.forEach(row => {
                    const name = row.getAttribute('data-name');
                    if (name.includes(query)) {
                        row.style.display = '';
                        matchCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Tampilkan pesan kosong jika tidak ada hasil yang cocok
                if (matchCount === 0 && rows.length > 0) {
                    emptySearchRow.style.display = '';
                } else {
                    emptySearchRow.style.display = 'none';
                }
            }

            searchInput.addEventListener('input', performSearch);

            // --- Instant Frontend Sorting (Nama A-Z, Z-A, Status) ---
            const sortSelect = document.getElementById('sort-select');
            const tbody = document.getElementById('employees-tbody');

            // Map bobot status untuk sorting prioritas status
            const statusPriority = {
                'hadir': 1,
                'izin': 2,
                'sakit': 3,
                'alfa': 4,
                'belum_dicatat': 5
            };

            sortSelect.addEventListener('change', function() {
                const sortType = sortSelect.value;
                const rowsArray = Array.from(rows);

                rowsArray.sort((a, b) => {
                    const nameA = a.getAttribute('data-name');
                    const nameB = b.getAttribute('data-name');
                    const statusA = a.getAttribute('data-status');
                    const statusB = b.getAttribute('data-status');

                    if (sortType === 'name-asc') {
                        return nameA.localeCompare(nameB);
                    } else if (sortType === 'name-desc') {
                        return nameB.localeCompare(nameA);
                    } else if (sortType === 'status') {
                        const prioA = statusPriority[statusA] || 99;
                        const prioB = statusPriority[statusB] || 99;
                        
                        if (prioA !== prioB) {
                            return prioA - prioB;
                        }
                        // Jika status sama, urutkan alfabetis nama
                        return nameA.localeCompare(nameB);
                    }
                    return 0;
                });

                // Kosongkan tbody dari baris karyawan lama (tapi pertahankan empty-search-row di paling bawah)
                rowsArray.forEach(row => {
                    tbody.appendChild(row);
                });
                tbody.appendChild(emptySearchRow);
            });
        });
    </script>
</x-layouts.admin>

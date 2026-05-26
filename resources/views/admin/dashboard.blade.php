<x-layouts.admin>
    <x-slot name="title">Beranda</x-slot>

    <!-- External Libraries for Dashboard -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        /* ── Dashboard Layout ── */
        .dashboard-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
            padding-bottom: 40px;
        }

        /* ── Top Highlight Section ── */
        .top-section {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
        }

        .highlight-card {
            background: linear-gradient(135deg, #451a03 0%, #78350f 100%);
            border-radius: 24px;
            padding: 32px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(69, 26, 3, 0.15);
        }

        .highlight-card::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        .highlight-card .main-val {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: -1px;
            margin: 8px 0;
        }

        .highlight-details {
            display: flex;
            gap: 24px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ── Filter Card ── */
        .filter-card {
            background: #fffbeb;
            border-radius: 24px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border: 1px solid #fef3c7;
        }

        /* ── Stats Row ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .premium-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
        }

        /* ── Content Grid ── */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
        }

        .content-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ── Tables & Lists ── */
        .table-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .order-row {
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f8fafc;
            transition: background 0.2s;
        }

        .order-row:hover { background: #f8fafc; }
        .order-row:last-child { border-bottom: none; }

        /* ── Quick Actions ── */
        .action-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            text-decoration: none;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .action-pill:hover {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        /* ── Typo ── */
        .label-small { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        .val-large { font-size: 24px; font-weight: 800; color: #1e293b; }
    </style>

    <div class="dashboard-container">
        
        <!-- 1. Top Section (Highlight & Filter) -->
        <div class="top-section">
            <div class="highlight-card">
                @php
                    $hour = (int) now()->setTimezone('Asia/Jakarta')->format('H');
                    $salam = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 18 ? 'Selamat sore' : 'Selamat malam'));
                @endphp
                <div>
                    <p style="font-size: 14px; opacity: 0.8; font-weight: 500; margin-bottom: 4px;">{{ $salam }}, {{ auth()->user()->name }}</p>
                    <div style="display: flex; align-items: center; gap: 12px; margin: 4px 0;">
                        <h1 id="total-sales-value" class="main-val" data-full-value="Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}" style="margin: 0;">Rp ••••••••</h1>
                        <button id="toggle-sales-visibility" style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 10px; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; outline: none;" title="Tampilkan/Sembunyikan Nominal">
                            <svg id="eye-icon" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <p style="font-size: 13px; opacity: 0.7;">Total Uang Masuk Periode Terpilih</p>
                </div>
                
                <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <a href="{{ route('admin.reports') }}" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); padding: 14px 28px; border-radius: 16px; color: white; text-decoration: none; font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 12px; transition: all 0.2s; box-shadow: 0 8px 20px rgba(0,0,0,0.1); width: fit-content;">
                        Analisis Laba Bersih & Kotor
                    </a>
                </div>
            </div>

            <div class="filter-card">
                <p class="label-small" style="margin-bottom: 12px; color: #92400e;">Konfigurasi Laporan</p>
                <form id="filter-form" method="GET" action="{{ route('admin.dashboard') }}">
                    <div style="position: relative;">
                        <div id="date-picker-trigger" style="display: flex; align-items: center; background: #fff; border: 1.5px solid #fde68a; border-radius: 16px; padding: 12px 16px; gap: 12px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.05);">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #fffbeb; display: flex; align-items: center; justify-content: center; color: #92400e;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: -1px;">Rentang Tanggal</p>
                                <span id="date-display" style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}</span>
                            </div>
                        </div>
                        <input type="text" id="date-range-input" style="position: absolute; opacity: 0; pointer-events: none; width: 100%; height: 100%; top: 0; left: 0;">
                        <input type="hidden" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}">
                        <input type="hidden" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Stats Grid -->
        <div class="stats-grid">
            <!-- Card Bahan Baku -->
            <div class="premium-card" style="display: grid; grid-template-columns: 1fr auto; align-items: center; padding: 28px; background: #ffffff; border-radius: 24px; border: 1.5px solid #f1f5f9; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02); position: relative; overflow: hidden;">
                <!-- Decorative Subtle Background Pattern -->
                <div style="position: absolute; bottom: -20px; right: -20px; width: 120px; height: 120px; background: rgba(120, 53, 15, 0.02); border-radius: 50%; pointer-events: none;"></div>
                
                <div>
                    <span style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px;">Bahan Baku</span>
                    <div style="display: flex; align-items: baseline; gap: 8px; margin: 12px 0 16px 0;">
                        <h3 style="font-size: 36px; font-weight: 850; color: #1e293b; margin: 0; line-height: 1;">{{ $stats['raw_material_count'] }}</h3>
                        <span style="font-size: 14px; font-weight: 700; color: #64748b;">Jenis Terdaftar</span>
                    </div>
                    
                    @php
                        $rawMaterialCount = $stats['raw_material_count'] ?? 0;
                        $criticalCount = $stats['critical_materials_count'] ?? 0;
                        $rawAvailability = $rawMaterialCount > 0 ? round((($rawMaterialCount - $criticalCount) / $rawMaterialCount) * 100) : 100;
                    @endphp

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($criticalCount > 0)
                                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #fee2e2; border: 1px solid #fecaca; border-radius: 99px; color: #ef4444; font-size: 11px; font-weight: 700;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444;"></span>
                                    {{ $criticalCount }} Bahan Kritis
                                </div>
                            @else
                                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 99px; color: #059669; font-size: 11px; font-weight: 700;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span>
                                    Stok Sangat Aman
                                </div>
                            @endif
                        </div>
                        
                        <!-- Progress Bar Ketersediaan -->
                        <div style="margin-top: 4px; width: 100%; min-width: 200px;">
                            <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 4px;">
                                <span>Rasio Aman</span>
                                <span>{{ $rawAvailability }}%</span>
                            </div>
                            <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 99px; overflow: hidden;">
                                <div style="width: {{ $rawAvailability }}%; height: 100%; background: {{ $rawAvailability == 100 ? '#10b981' : ($rawAvailability >= 70 ? '#f59e0b' : '#ef4444') }}; border-radius: 99px; transition: width 0.5s ease-in-out;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Icon Section -->
                <div style="width: 72px; height: 72px; border-radius: 20px; background: #fffbeb; border: 1.5px solid #fde68a; display: flex; align-items: center; justify-content: center; color: #78350f; box-shadow: 0 4px 12px rgba(120, 53, 15, 0.05); position: relative; z-index: 2;">
                    <!-- Sack of Coffee Beans (SVG) -->
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>

            <!-- Card Barang Jadi -->
            <div class="premium-card" style="display: grid; grid-template-columns: 1fr auto; align-items: center; padding: 28px; background: #ffffff; border-radius: 24px; border: 1.5px solid #f1f5f9; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.02); position: relative; overflow: hidden;">
                <!-- Decorative Subtle Background Pattern -->
                <div style="position: absolute; bottom: -20px; right: -20px; width: 120px; height: 120px; background: rgba(120, 53, 15, 0.02); border-radius: 50%; pointer-events: none;"></div>
                
                <div>
                    <span style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px;">Barang Jadi</span>
                    <div style="display: flex; align-items: baseline; gap: 8px; margin: 12px 0 16px 0;">
                        <h3 style="font-size: 36px; font-weight: 850; color: #1e293b; margin: 0; line-height: 1;">{{ $stats['product_count'] }}</h3>
                        <span style="font-size: 14px; font-weight: 700; color: #64748b;">Produk Aktif</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 99px; color: #2563eb; font-size: 11px; font-weight: 700;">
                                <svg style="width: 12px; height: 12px; color: #2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Siap Kirim
                            </div>
                        </div>
                        
                        <!-- Info Stok Global Detail -->
                        <div style="margin-top: 4px; display: flex; flex-direction: column; gap: 2px;">
                            <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Total Volume Fisik</span>
                            <span style="font-size: 16px; font-weight: 800; color: #78350f;">{{ number_format($stats['total_product_stock'], 0, ',', '.') }} <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">Pcs</span></span>
                        </div>
                    </div>
                </div>

                <!-- Right Icon Section -->
                <div style="width: 72px; height: 72px; border-radius: 20px; background: #faf5ff; border: 1.5px solid #e9d5ff; display: flex; align-items: center; justify-content: center; color: #6b21a8; box-shadow: 0 4px 12px rgba(107, 33, 168, 0.05); position: relative; z-index: 2;">
                    <!-- Package/Cup SVG -->
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 3. Main Content Grid -->
        <div class="content-grid">
            <!-- Left Column: Chart & Orders -->
            <div class="content-section">
                <!-- Sales Chart -->
                <div class="table-card">
                    <div class="table-header">
                        <h3 style="font-size: 15px; font-weight: 700; color: #1e293b;">Grafik Tren Uang Masuk</h3>
                        <span style="font-size: 11px; font-weight: 700; color: #94a3b8; background: #f8fafc; padding: 4px 10px; border-radius: 8px;">PERIODE TERPILIH</span>
                    </div>
                    <div style="padding: 24px;">
                        @if(count($chartValues) > 0)
                            <div id="salesChart"></div>
                        @else
                            <div style="height: 280px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; background: #f8fafc; border-radius: 24px; border: 2px dashed #e2e8f0; gap: 12px;">
                                <svg style="width: 48px; height: 48px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p style="font-size: 13px; font-weight: 500;">Belum ada data penjualan pada periode ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Alert Stok Kritis -->
                <div class="table-card" style="border-left: 6px solid #ef4444;">
                    <div class="table-header" style="background: #fff1f2; border-bottom: 1px solid #fecaca;">
                        <h3 style="color: #991b1b; font-size: 14px; font-weight: 700;">Peringatan Stok Kritis</h3>
                        <span style="font-size: 10px; font-weight: 800; color: #ef4444; background: white; padding: 2px 8px; border-radius: 6px;">PERLU TINDAKAN</span>
                    </div>
                    <div style="padding: 0;">
                        <!-- Bahan Baku Hampir Habis -->
                        @forelse($low_stock_materials as $material)
                        <div class="order-row">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444;"></div>
                                <div>
                                    <p style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $material->name }}</p>
                                    <p style="font-size: 11px; color: #94a3b8;">Bahan Baku • Hampir Habis</p>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 13px; font-weight: 800; color: #ef4444;">{{ number_format($material->current_stock, 0) }} {{ $material->unit->code }}</p>
                                <p style="font-size: 10px; color: #cbd5e1;">Min: {{ number_format($material->minimum_stock, 0) }}</p>
                            </div>
                        </div>
                        @empty
                        <div style="padding: 16px 24px; color: #94a3b8; font-size: 12px; font-weight: 500; border-bottom: 1px solid #f8fafc;">
                            Tidak ada bahan baku yang hampir habis.
                        </div>
                        @endforelse

                        <!-- Produk Jadi Kosong -->
                        @forelse($out_of_stock_products as $product)
                        <div class="order-row">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: #b91c1c;"></div>
                                <div>
                                    <p style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $product->name }}</p>
                                    <p style="font-size: 11px; color: #ef4444;">Produk Jadi • Kosong</p>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <span style="font-size: 10px; font-weight: 800; background: #fee2e2; color: #b91c1c; padding: 2px 8px; border-radius: 4px;">HABIS</span>
                            </div>
                        </div>
                        @empty
                        <div style="padding: 16px 24px; color: #94a3b8; font-size: 12px; font-weight: 500;">
                            Tidak ada produk jadi yang kosong.
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Column: Info Cards & Actions -->
            <div class="content-section">
                <!-- Today Summary Card -->
                <div class="premium-card" style="border-left: 6px solid #78350f; background: #fffdfa; border: 1.5px solid #fef3c7; box-shadow: 0 4px 20px rgba(120, 53, 15, 0.05);">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; border-bottom: 1.5px solid #fef3c7; padding-bottom: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: #fffbeb; display: flex; align-items: center; justify-content: center; color: #78350f; border: 1px solid #fde68a;">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 style="font-size: 14px; font-weight: 800; color: #78350f; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Ringkasan Hari Ini</h3>
                    </div>

                    <!-- Total Uang Masuk Hari Ini -->
                    <div style="padding-bottom: 16px;">
                        <p class="label-small" style="color: #94a3b8; font-size: 10px; margin-bottom: 4px; font-weight: 800;">Total Uang Masuk</p>
                        <h4 style="font-size: 22px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                            Rp {{ number_format($today_cash_in, 0, ',', '.') }}
                        </h4>
                        <span style="font-size: 11px; color: #64748b; font-weight: 500; display: block; margin-top: 4px; line-height: 1.4;">Pembayaran admin + setoran sales disetujui</span>
                    </div>

                    <div style="height: 1px; background: #f1f5f9; margin: 4px 0 16px 0;"></div>

                    <!-- Produksi Hari Ini -->
                    <div style="padding-bottom: 4px;">
                        <p class="label-small" style="color: #94a3b8; font-size: 10px; margin-bottom: 4px; font-weight: 800;">Produksi Kopi</p>
                        <h4 style="font-size: 22px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                            {{ number_format($today_production_output, 0, ',', '.') }} <span style="font-size: 13px; color: #94a3b8; font-weight: 500;">pcs</span>
                        </h4>
                        <span style="font-size: 11px; color: #64748b; font-weight: 500; display: block; margin-top: 4px; line-height: 1.4;">{{ $today_production_count }} batch selesai hari ini</span>
                    </div>
                </div>

                <!-- Help Banner -->
                <div style="margin-top: 12px; padding: 24px; border-radius: 24px; background: #f8fafc; border: 1px dashed #cbd5e1; text-align: center;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: white; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                        <svg style="width: 20px; height: 20px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Pusat Bantuan</h4>
                    <p style="font-size: 11px; color: #94a3b8; margin-bottom: 16px;">Butuh panduan penggunaan sistem Manajemen Kopi?</p>
                    <a href="#" target="_blank" style="font-size: 12px; font-weight: 700; color: #475569; text-decoration: none; display: inline-block; padding: 8px 16px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">Pelajari Sekarang</a>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateTrigger = document.getElementById('date-picker-trigger');
            
            // Flatpickr setup
            const fp = flatpickr("#date-range-input", {
                mode: "range",
                dateFormat: "Y-m-d",
                onOpen: function() {
                    dateTrigger.style.borderColor = '#92400e';
                    dateTrigger.style.boxShadow = '0 0 0 4px rgba(146, 64, 14, 0.05)';
                },
                onClose: function(selectedDates) {
                    dateTrigger.style.borderColor = '#f1f5f9';
                    dateTrigger.style.boxShadow = '0 2px 6px rgba(0,0,0,0.02)';
                    
                    if (selectedDates.length === 2) {
                        const start = selectedDates[0].toLocaleDateString('en-CA');
                        const end = selectedDates[1].toLocaleDateString('en-CA');
                        document.getElementById('start_date').value = start;
                        document.getElementById('end_date').value = end;
                        setTimeout(() => { document.getElementById('filter-form').submit(); }, 300);
                    }
                }
            });

            if (dateTrigger) {
                dateTrigger.addEventListener('click', () => {
                    fp.open();
                });
            }

            // Toggle Sales Visibility
            const toggleBtn = document.getElementById('toggle-sales-visibility');
            const salesVal = document.getElementById('total-sales-value');
            const eyeIcon = document.getElementById('eye-icon');
            let isHidden = true;

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    isHidden = !isHidden;
                    if (isHidden) {
                        salesVal.textContent = 'Rp ••••••••';
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />`;
                    } else {
                        salesVal.textContent = salesVal.dataset.fullValue;
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
                    }
                });
            }

            @if(count($chartValues) > 0)
            const options = {
                series: [{
                    name: 'Total Uang Masuk',
                    data: {!! json_encode($chartValues) !!}
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                    sparkline: { enabled: false },
                    fontFamily: "'Inter', sans-serif",
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: { enabled: true, delay: 150 },
                        dynamicAnimation: { enabled: true, speed: 350 }
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 4,
                    lineCap: 'round'
                },
                colors: ['#92400e'], // Brown 500
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    }
                },
                labels: {!! json_encode($chartLabels) !!},
                dataLabels: { enabled: false },
                markers: {
                    size: 5,
                    colors: ['#fff'],
                    strokeColors: '#92400e',
                    strokeWidth: 3,
                    hover: { size: 7 }
                },
                grid: {
                    show: true,
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    padding: { left: 10, right: 10 }
                },
                xaxis: {
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: { colors: '#94a3b8', fontWeight: 600, fontSize: '11px' }
                    }
                },
                yaxis: {
                    decimalsInFloat: 0,
                    labels: {
                        formatter: function(val) {
                            if (val >= 1000000) return (val / 1000000).toFixed(1).replace('.0', '') + ' Jt';
                            if (val >= 1000) return (val / 1000).toFixed(0) + ' Rb';
                            return val;
                        },
                        style: { colors: '#94a3b8', fontWeight: 600, fontSize: '11px' }
                    }
                },
                tooltip: {
                    x: { show: true },
                    y: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    },
                    marker: { show: false },
                    theme: 'light',
                    style: { fontSize: '13px' },
                    onDatasetHover: { highlightDataSeries: true },
                }
            };

            const chart = new ApexCharts(document.querySelector("#salesChart"), options);
            chart.render();
            @endif
        });
    </script>
</x-layouts.admin>

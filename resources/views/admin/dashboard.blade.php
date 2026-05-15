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
            grid-template-columns: repeat(3, 1fr);
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
                    <p style="font-size: 13px; opacity: 0.7;">Total Penjualan Kotor Periode Terpilih</p>
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
            <div class="premium-card">
                <p class="label-small">Bahan Baku</p>
                <h3 class="val-large">{{ $stats['raw_material_count'] }} <span style="font-size: 13px; color: #94a3b8; font-weight: 500;">Jenis</span></h3>
            </div>
            <div class="premium-card">
                <p class="label-small">Barang Jadi</p>
                <h3 class="val-large">{{ $stats['product_count'] }} <span style="font-size: 13px; color: #94a3b8; font-weight: 500;">Produk</span></h3>
            </div>
            <div class="premium-card">
                <p class="label-small">Order Masuk</p>
                <h3 class="val-large">{{ $stats['order_count'] }} <span style="font-size: 13px; color: #94a3b8; font-weight: 500;">Order</span></h3>
            </div>
        </div>

        <!-- 3. Main Content Grid -->
        <div class="content-grid">
            <!-- Left Column: Chart & Orders -->
            <div class="content-section">
                <!-- Sales Chart -->
                <div class="table-card">
                    <div class="table-header">
                        <h3 style="font-size: 15px; font-weight: 700; color: #1e293b;">Grafik Penjualan</h3>
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
                @if($low_stock_materials->count() > 0 || $out_of_stock_products->count() > 0)
                <div class="table-card" style="border-left: 6px solid #ef4444;">
                    <div class="table-header" style="background: #fff1f2; border-bottom: 1px solid #fecaca;">
                        <h3 style="color: #991b1b; font-size: 14px; font-weight: 700;">Peringatan Stok Kritis</h3>
                        <span style="font-size: 10px; font-weight: 800; color: #ef4444; background: white; padding: 2px 8px; border-radius: 6px;">PERLU TINDAKAN</span>
                    </div>
                    <div style="padding: 0;">
                        @foreach($low_stock_materials as $material)
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
                        @endforeach
                        @foreach($out_of_stock_products as $product)
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
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Latest Orders -->
                <div class="table-card">
                    <div class="table-header">
                        <h3 style="font-size: 15px; font-weight: 700; color: #1e293b;">Pesanan Perlu Diproses</h3>
                        <a href="{{ route('admin.sales-orders.index') }}" style="font-size: 12px; font-weight: 700; color: #92400e; text-decoration: none;">Kelola Pesanan →</a>
                    </div>
                    <div style="padding: 0;">
                        @forelse($latest_orders as $order)
                        <div class="order-row">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="width: 40px; height: 40px; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; color: #94a3b8; border: 1px solid #f1f5f9;">
                                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <div>
                                    <p style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $order->order_number }}</p>
                                    <p style="font-size: 11px; color: #94a3b8;">{{ $order->user->name ?? 'Member' }} • {{ $order->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 13px; font-weight: 800; color: #1e293b;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                <span style="font-size: 9px; font-weight: 800; text-transform: uppercase; background: #fffbeb; color: #92400e; padding: 2px 8px; border-radius: 10px; border: 1px solid #fef3c7;">{{ $order->status }}</span>
                            </div>
                        </div>
                        @empty
                        <div style="padding: 48px 24px; text-align: center; color: #cbd5e1; display: flex; flex-direction: column; align-items: center; gap: 12px;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 24px; height: 24px; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p style="font-size: 13px; font-weight: 500; color: #94a3b8;">Tidak ada pesanan yang perlu diproses.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Info Cards & Actions -->
            <div class="content-section">
                <!-- Today Summary Card -->

                <!-- Quick Actions -->
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <p class="label-small">Aksi Cepat</p>
                    <a href="{{ route('admin.raw-material-receipts.create') }}" class="action-pill">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: #1d4ed8;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span>Terima Bahan Baku</span>
                    </a>
                    <a href="#" class="action-pill">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #f0fdf4; display: flex; align-items: center; justify-content: center; color: #16a34a;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <span>Mulai Produksi</span>
                    </a>
                    <a href="{{ route('admin.sales.create') }}" class="action-pill">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #fff7ed; display: flex; align-items: center; justify-content: center; color: #c2410c;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span>Input Penjualan</span>
                    </a>
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
                    name: 'Total Penjualan',
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

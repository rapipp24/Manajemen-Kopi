<x-layouts.admin>
    <x-slot name="title">Laporan Keuangan & Margin</x-slot>

    <style>
        .report-page-bg {
            background-color: #fafaf9;
            min-height: 100vh;
            padding: 32px 24px;
            color: #334155;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .report-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            border-bottom: 1px solid #e7e5e4;
            padding-bottom: 16px;
        }

        .report-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 4px 0;
        }

        .report-subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }

        .date-filter-box {
            background-color: #ffffff;
            border: 1px solid #e7e5e4;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        /* KPI Grid - Desktop */
        .main-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        /* KPI Cards Styling */
        .kpi-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px 22px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03), 0 1px 2px -1px rgba(15, 23, 42, 0.02);
            display: flex;
            flex-direction: column;
            min-height: 120px;
            justify-content: space-between;
            position: relative;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -4px rgba(15, 23, 42, 0.06), 0 4px 6px -2px rgba(15, 23, 42, 0.04);
            border-color: #d1d5db;
        }

        .kpi-card.kpi-omset { border-left: 4px solid #10b981; } /* Emerald */
        .kpi-card.kpi-selisih-surplus { border-left: 4px solid #10b981; } /* Emerald */
        .kpi-card.kpi-selisih-defisit { border-left: 4px solid #f43f5e; } /* Rose */
        .kpi-card.kpi-piutang { border-left: 4px solid #f59e0b; } /* Amber */
        .kpi-card.kpi-kelebihan { border-left: 4px solid #7c3aed; } /* Purple */

        /* Pengeluaran Tercatat Styling */
        .expense-section {
            margin-bottom: 24px;
        }
        .expense-card {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 16px 22px;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03), 0 1px 2px -1px rgba(15, 23, 42, 0.02);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .expense-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px -2px rgba(15, 23, 42, 0.04);
            border-color: #d1d5db;
        }
        .expense-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .expense-label {
            font-size: 11px;
            font-weight: 750;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .expense-helper {
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.4;
        }
        .expense-value {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            white-space: nowrap;
            letter-spacing: -0.3px;
        }

        /* Analisis Margin Produk Styling */
        .analysis-section {
            margin-bottom: 24px;
        }
        .analysis-card {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 20px 24px;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03), 0 1px 2px -1px rgba(15, 23, 42, 0.02);
        }
        .analysis-grid {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto 1fr;
            gap: 16px;
            align-items: center;
        }
        .analysis-operator-col {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }
        .analysis-operator {
            font-size: 26px;
            font-weight: 300;
            color: #94a3b8;
            user-select: none;
        }
        .analysis-col {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .analysis-col.highlighting-col {
            background-color: #f0f9ff;
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px dashed #bae6fd;
        }
        .analysis-label {
            font-size: 11px;
            font-weight: 750;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .analysis-label.highlight-label {
            color: #0369a1;
        }
        .analysis-value {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            white-space: nowrap;
            letter-spacing: -0.3px;
        }
        .analysis-value.highlight-value {
            color: #0369a1;
            font-size: 19px;
        }
        .analysis-helper {
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.4;
        }
        .analysis-helper.highlight-helper {
            color: #0369a1;
        }

        /* Responsive Media Queries */
        /* Tablet (max-width: 1024px) */
        @media (max-width: 1024px) {
            .main-kpi-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            .analysis-grid {
                grid-template-columns: 1fr;
                gap: 16px;
                align-items: stretch;
            }
            .analysis-operator-col {
                display: none;
            }
            .analysis-col.highlighting-col {
                background-color: #f0f9ff;
                border: 1px solid #bae6fd;
            }
        }

        /* Mobile (max-width: 640px) */
        @media (max-width: 640px) {
            .main-kpi-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .expense-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .analysis-grid {
                grid-template-columns: 1fr;
                gap: 16px;
                align-items: stretch;
            }
            .analysis-operator-col {
                display: none;
            }
        }

        .kpi-section-header {
            margin-bottom: 14px;
            padding-left: 4px;
        }
        .kpi-section-title {
            font-size: 14px;
            font-weight: 750;
            color: #1e293b;
            margin: 0;
            letter-spacing: -0.2px;
        }
        .kpi-section-helper {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0 0 0;
        }

        .kpi-label {
            font-size: 11.5px;
            font-weight: 700;
            color: #64748b;
            margin: 0 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-value {
            font-size: 21px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 6px 0;
            white-space: nowrap;
            letter-spacing: -0.5px;
        }

        .kpi-helper {
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.45;
            margin: 8px 0 0 0;
        }

        .kpi-badge {
            display: inline-flex;
            align-items: center;
            padding: 2.5px 8px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: fit-content;
            margin-top: 6px;
        }
        .badge-surplus {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #d1fae5;
        }
        .badge-defisit {
            background-color: #fff1f2;
            color: #be123c;
            border: 1px solid #ffe4e6;
        }

        .kpi-micro-text {
            font-size: 11px;
            color: #b45309;
            background-color: #fef3c7;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 600;
            margin-top: 6px;
            border: 1px solid #fde68a;
        }

        .report-section {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #e7e5e4;
            padding: 20px;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 16px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #f2f0ef;
        }

        .simple-note {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 12px;
            color: #475569;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .report-table th {
            padding: 10px;
            font-weight: 600;
            color: #64748b;
            border-bottom: 1px solid #e7e5e4;
            text-align: left;
        }

        .report-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f2f0ef;
            color: #334155;
        }

        .badge-status {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .status-belum { background: #fffbeb; color: #b45309; }
        .status-dp { background: #eff6ff; color: #1d4ed8; }
        .status-lunas { background: #ecfdf5; color: #15803d; }
        .status-lewat-tempo { background: #fef2f2; color: #dc2626; }
        .status-bayar-lebih { background: #faf5ff; color: #7c3aed; }

        .section-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e7e5e4;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            border-radius: 20px;
            padding: 2px 8px;
            margin-left: 8px;
        }

        .section-count.count-warning { background: #fef3c7; color: #92400e; }
        .section-count.count-purple { background: #f3e8ff; color: #6d28d9; }

        .section-empty-msg {
            text-align: center;
            color: #94a3b8;
            padding: 24px 16px;
            font-size: 13px;
        }

        .btn-lihat-detail {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            border: 1px solid #e2e8f0;
            transition: background 0.15s ease;
        }
        .btn-lihat-detail:hover {
            background: #e2e8f0;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: 700; }
        .text-amber { color: #b45309; }
        .text-red { color: #dc2626; }
        .text-purple { color: #7c3aed; }

        .btn-toggle-helpers {
            background-color: #ffffff;
            border: 1px solid #e7e5e4;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-toggle-helpers:hover {
            background-color: #f5f5f4;
            border-color: #d6d3d1;
            color: #1e293b;
        }

        .hide-helpers .report-helper-text {
            display: none !important;
        }
    </style>

    <div class="report-page-bg">
        <div class="report-container">
            
            <div class="report-header">
                <div>
                    <h1 class="report-title">Laporan Keuangan & Margin</h1>
                    <p class="report-subtitle">Catatan sederhana margin keuntungan gudang dan tagihan lapangan.</p>
                </div>
                
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button type="button" id="toggleReportHelpers" class="btn-toggle-helpers">
                        Sembunyikan penjelasan
                    </button>

                    <form id="filter-form" method="GET" action="{{ route('admin.reports') }}">
                        <div class="date-filter-box" id="date-range-trigger">
                            <span id="date-range-display">{{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}</span>
                            <input type="text" id="date-range-input" style="position: absolute; opacity: 0; pointer-events: none; width: 0; height: 0;">
                            <input type="hidden" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}">
                            <input type="hidden" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                    </form>
                </div>
            </div>

            <div class="simple-note report-helper-text">
                Laporan memisahkan uang masuk, pengeluaran tercatat, tagihan toko, dan margin produk agar mudah dipahami.
            </div>

            <!-- SECTION A: Ringkasan Utama -->
            <div class="kpi-section-header">
                <h2 class="kpi-section-title">Ringkasan Utama</h2>
                <p class="kpi-section-helper report-helper-text">Posisi uang masuk, selisih tercatat, dan tagihan toko.</p>
            </div>

            <div class="main-kpi-grid">
                <!-- Total Uang Masuk -->
                <div class="kpi-card kpi-omset">
                    <div>
                        <p class="kpi-label">Total Uang Masuk</p>
                        <p class="kpi-value">{{ $totalCashIn < 0 ? '-Rp ' . number_format(abs($totalCashIn), 0, ',', '.') : 'Rp ' . number_format($totalCashIn, 0, ',', '.') }}</p>
                    </div>
                    <p class="kpi-helper report-helper-text">Kas/setoran yang sudah benar-benar diterima. Return tidak otomatis mengurangi kas masuk.</p>
                </div>

                <!-- Selisih Tercatat -->
                <div class="kpi-card {{ $selisihTercatat < 0 ? 'kpi-selisih-defisit' : 'kpi-selisih-surplus' }}">
                    <div>
                        <p class="kpi-label">Selisih Tercatat</p>
                        <p class="kpi-value">{{ $selisihTercatat < 0 ? '-Rp ' . number_format(abs($selisihTercatat), 0, ',', '.') : 'Rp ' . number_format($selisihTercatat, 0, ',', '.') }}</p>
                        @if($selisihTercatat < 0)
                            <span class="kpi-badge badge-defisit">Defisit Tercatat</span>
                        @else
                            <span class="kpi-badge badge-surplus">Surplus Tercatat</span>
                        @endif
                    </div>
                    <p class="kpi-helper report-helper-text">Selisih antara uang masuk dan uang keluar tercatat, bukan laba bersih.</p>
                </div>

                <!-- Sisa Tagihan Toko -->
                <div class="kpi-card kpi-piutang">
                    <div>
                        <p class="kpi-label">Sisa Tagihan Toko</p>
                        <p class="kpi-value">{{ $totalSisaPiutang < 0 ? '-Rp ' . number_format(abs($totalSisaPiutang), 0, ',', '.') : 'Rp ' . number_format($totalSisaPiutang, 0, ',', '.') }}</p>
                        @if(count($tagihanBelumBayar) > 0)
                            <div class="kpi-micro-text">{{ count($tagihanBelumBayar) }} laporan belum lunas</div>
                        @endif
                    </div>
                    <p class="kpi-helper report-helper-text">Tagihan lapangan yang belum lunas</p>
                </div>

                <!-- Kelebihan Bayar Toko -->
                <div class="kpi-card kpi-kelebihan">
                    <div>
                        <p class="kpi-label">Kelebihan Bayar Toko</p>
                        <p class="kpi-value">Rp {{ number_format($totalKelebihanBayar, 0, ',', '.') }}</p>
                    </div>
                    <p class="kpi-helper report-helper-text">Saldo lebih bayar akibat setoran melebihi tagihan efektif setelah return.</p>
                </div>
            </div>

            <!-- Pengeluaran Tercatat -->
            <div class="kpi-section-header" style="margin-top: 28px;">
                <h2 class="kpi-section-title">Pengeluaran Tercatat</h2>
                <p class="kpi-section-helper report-helper-text">Total pengeluaran yang terdata dalam sistem.</p>
            </div>

            <div class="expense-section">
                <div class="expense-card">
                    <div class="expense-info">
                        <span class="expense-label">Total Uang Keluar Tercatat</span>
                        <span class="expense-helper report-helper-text">Pembelian bahan baku yang tercatat</span>
                    </div>
                    <span class="expense-value">{{ $totalCashOut < 0 ? '-Rp ' . number_format(abs($totalCashOut), 0, ',', '.') : 'Rp ' . number_format($totalCashOut, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Analisis Margin Produk -->
            <div class="kpi-section-header" style="margin-top: 28px;">
                <h2 class="kpi-section-title">Analisis Margin Produk</h2>
                <p class="kpi-section-helper report-helper-text">Rincian nilai penjualan, HPP produk, dan margin.</p>
            </div>

            <div class="analysis-section">
                <div class="analysis-card">
                    <!-- Total Return Diterima Info Bar -->
                    <div style="border-bottom: 1px dashed #e5e7eb; padding-bottom: 12px; margin-bottom: 16px; font-size: 13px; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 8px; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <span style="font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-size: 11px;">Total Return Diterima</span>
                            <span style="font-size: 14px; font-weight: 800; color: #b45309;">Rp {{ number_format($totalReturnDiterima, 0, ',', '.') }}</span>
                        </div>
                        <span class="report-helper-text" style="font-size: 11.5px; color: #64748b;">Nilai barang return (Potong Tagihan) yang sudah diterima admin pada periode ini. Tipe Tukar Barang tidak mengurangi tagihan.</span>
                    </div>

                    <div class="analysis-grid">
                        <!-- Total Penjualan Bersih -->
                        <div class="analysis-col">
                            <span class="analysis-label">Total Penjualan Bersih</span>
                            <span class="analysis-value">{{ $totalNilaiPenjualan < 0 ? '-Rp ' . number_format(abs($totalNilaiPenjualan), 0, ',', '.') : 'Rp ' . number_format($totalNilaiPenjualan, 0, ',', '.') }}</span>
                            <span class="analysis-helper report-helper-text">Nilai penjualan setelah dikurangi return diterima.</span>
                        </div>

                        <!-- Subtraction Operator -->
                        <div class="analysis-operator-col">
                            <span class="analysis-operator">−</span>
                        </div>

                        <!-- Total HPP Produk -->
                        <div class="analysis-col">
                            <span class="analysis-label">Total HPP Produk</span>
                            <span class="analysis-value">{{ $totalHppProduk < 0 ? '-Rp ' . number_format(abs($totalHppProduk), 0, ',', '.') : 'Rp ' . number_format($totalHppProduk, 0, ',', '.') }}</span>
                            <span class="analysis-helper report-helper-text">Modal produk dari barang yang dihitung dalam margin</span>
                        </div>

                        <!-- Equal Operator -->
                        <div class="analysis-operator-col">
                            <span class="analysis-operator">=</span>
                        </div>

                        <!-- Margin Produk -->
                        <div class="analysis-col highlighting-col">
                            <span class="analysis-label highlight-label">Margin Produk</span>
                            <span class="analysis-value highlight-value">{{ $labaMargin < 0 ? '-Rp ' . number_format(abs($labaMargin), 0, ',', '.') : 'Rp ' . number_format($labaMargin, 0, ',', '.') }}</span>
                            <span class="analysis-helper highlight-helper report-helper-text">Total Penjualan Bersih dikurangi Total HPP Produk Bersih.</span>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ═══ SECTION 1: Tagihan Toko Belum Dibayar ═══ --}}
            <div class="report-section">
                <h3 class="section-title">
                    Tagihan Toko Belum Dibayar
                    <span class="section-count count-warning">{{ count($tagihanBelumBayar) }}</span>
                </h3>

                @if(count($tagihanBelumBayar) > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Nama Toko</th>
                            <th>Sales</th>
                            <th>Tgl Kirim</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-right">Total Tagihan</th>
                            <th class="text-right">Sudah Dibayar</th>
                            <th class="text-right">Sisa Tagihan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tagihanBelumBayar as $item)
                        <tr>
                            <td><strong>{{ $item['toko_name'] }}</strong></td>
                            <td>{{ $item['sales_name'] }}</td>
                            <td>{{ $item['delivery_date'] }}</td>
                            <td>{{ $item['due_date'] }}</td>
                            <td class="text-right">Rp {{ number_format($item['tagihan_efektif'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item['total_paid'], 0, ',', '.') }}</td>
                            <td class="text-right text-bold text-amber">Rp {{ number_format($item['sisa_tagihan'], 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($item['lewat_tempo'])
                                    <span class="badge-status status-lewat-tempo">Lewat Tempo</span>
                                @elseif($item['total_paid'] > 0)
                                    <span class="badge-status status-dp">DP</span>
                                @else
                                    <span class="badge-status status-belum">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.delivery-reports.show', $item['id']) }}" class="btn-lihat-detail">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="section-empty-msg">Tidak ada tagihan yang belum dibayar pada periode ini.</p>
                @endif
            </div>

            {{-- ═══ SECTION 2: Bayar Lebih Belum Diselesaikan ═══ --}}
            <div class="report-section">
                <h3 class="section-title">
                    Bayar Lebih Belum Diselesaikan
                    <span class="section-count count-purple">{{ count($bayarLebihBelumSelesai) }}</span>
                </h3>

                @if(count($bayarLebihBelumSelesai) > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Nama Toko</th>
                            <th>Sales</th>
                            <th>Tgl Kirim</th>
                            <th class="text-right">Total Tagihan</th>
                            <th class="text-right">Sudah Dibayar</th>
                            <th class="text-right">Bayar Lebih</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bayarLebihBelumSelesai as $item)
                        <tr>
                            <td><strong>{{ $item['toko_name'] }}</strong></td>
                            <td>{{ $item['sales_name'] }}</td>
                            <td>{{ $item['delivery_date'] }}</td>
                            <td class="text-right">Rp {{ number_format($item['tagihan_efektif'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item['total_paid'], 0, ',', '.') }}</td>
                            <td class="text-right text-bold text-purple">Rp {{ number_format($item['bayar_lebih'], 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge-status status-bayar-lebih">Belum Diselesaikan</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.delivery-reports.show', $item['id']) }}" class="btn-lihat-detail">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="section-empty-msg">Tidak ada bayar lebih yang perlu diselesaikan pada periode ini.</p>
                @endif
            </div>

        </div>
    </div>

    <!-- Scripting -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr
            const fp = flatpickr("#date-range-input", {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: ["{{ $startDate->format('Y-m-d') }}", "{{ $endDate->format('Y-m-d') }}"],
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        document.getElementById('start_date').value = instance.formatDate(selectedDates[0], "Y-m-d");
                        document.getElementById('end_date').value = instance.formatDate(selectedDates[1], "Y-m-d");
                        document.getElementById('filter-form').submit();
                    }
                }
            });
            document.getElementById('date-range-trigger').addEventListener('click', () => fp.open());

            // Helper Toggle logic
            const toggleBtn = document.getElementById('toggleReportHelpers');
            const reportContainer = document.querySelector('.report-container');
            const storageKey = 'admin_report_show_helpers';

            function setHelpersVisible(visible) {
                if (visible) {
                    reportContainer.classList.remove('hide-helpers');
                    toggleBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        Sembunyikan penjelasan
                    `;
                    localStorage.setItem(storageKey, 'show');
                } else {
                    reportContainer.classList.add('hide-helpers');
                    toggleBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
                        Tampilkan penjelasan
                    `;
                    localStorage.setItem(storageKey, 'hidden');
                }
            }

            // Read preference
            const savedPreference = localStorage.getItem(storageKey);
            if (savedPreference === 'hidden') {
                setHelpersVisible(false);
            } else {
                setHelpersVisible(true);
            }

            // Click listener
            toggleBtn.addEventListener('click', function() {
                const isCurrentlyHidden = reportContainer.classList.contains('hide-helpers');
                setHelpersVisible(isCurrentlyHidden);
            });
        });
    </script>
</x-layouts.admin>

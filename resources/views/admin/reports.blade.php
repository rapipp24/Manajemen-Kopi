<x-layouts.admin>
    <x-slot name="title">Analisis Laba Bersih & Kotor</x-slot>

    <style>
        .report-container {
            padding: 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .premium-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        padding: 32px;
        border-radius: 24px;
        position: relative;
        overflow: hidden;
    }

    .stat-card.sales { background: #fffbeb; color: #92400e; }
    .stat-card.profit { background: linear-gradient(135deg, #451a03 0%, #78350f 100%); color: white; }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
    }

    .chart-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .filter-btn {
        background: white;
        padding: 10px 20px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
</style>

<div class="report-container">
    <div class="report-header">
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="{{ route('admin.dashboard') }}" class="filter-btn" style="padding: 8px; border-radius: 50%;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 style="font-size: 24px; font-weight: 800; color: #1e293b;">Analisis Laba</h1>
                <p style="color: #64748b; font-size: 14px;">Evaluasi performa finansial bisnis Anda</p>
            </div>
        </div>

        <form id="filter-form" method="GET" action="{{ route('admin.reports') }}">
            <div class="filter-btn" id="date-range-trigger">
                <svg style="width: 18px; height: 18px; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span id="date-range-display">{{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}</span>
                <input type="text" id="date-range-input" style="position: absolute; opacity: 0; pointer-events: none;">
                <input type="hidden" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}">
            </div>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="stat-grid">
        <div class="stat-card sales">
            <p class="stat-label">Total Penjualan</p>
            <p class="stat-value" style="font-size: 32px;">Rp {{ number_format($totalGrossSales, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card profit">
            <p class="stat-label" style="color: rgba(255,255,255,0.7)">Total Laba</p>
            <p class="stat-value" style="font-size: 32px;">Rp {{ number_format($labaKotor, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="chart-section">
        <div class="premium-card">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 24px;">Tren Penjualan Harian</h3>
            <div id="sales-chart"></div>
        </div>

        <div class="premium-card">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 24px;">5 Produk Terlaris</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($topProducts as $item)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 12px;">
                    <div>
                        <p style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $item->product->name ?? 'Produk Terhapus' }}</p>
                        <p style="font-size: 12px; color: #64748b;">{{ $item->total_qty }} terjual</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 14px; font-weight: 700; color: #92400e;">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
                
                @if($topProducts->isEmpty())
                <p style="text-align: center; color: #94a3b8; font-size: 14px; padding: 20px;">Belum ada data penjualan</p>
                @endif

                <div style="margin-top: 12px; padding-top: 16px; border-top: 1px dashed #e2e8f0;">
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Margin Keuntungan</p>
                    <p style="font-size: 18px; font-weight: 800; color: #16a34a;">
                        {{ $totalGrossSales > 0 ? number_format(($labaKotor / $totalGrossSales) * 100, 1) : 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flatpickr
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

        // Chart
        const options = {
            series: [{
                name: 'Penjualan',
                data: @json($chartSales)
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                zoom: { enabled: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#78350f'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#sales-chart"), options);
        chart.render();
    });
</script>
</x-layouts.admin>

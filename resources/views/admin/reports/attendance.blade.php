<x-layouts.admin>
    <x-slot name="title">Rekap Absensi Bulanan</x-slot>

    <style>
        .recap-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
            padding-bottom: 40px;
        }

        /* ── Header & Filter Card ── */
        .recap-header-card {
            background: white;
            border-radius: 24px;
            padding: 28px;
            border: 1px solid #e8d8c4;
            box-shadow: 0 4px 20px rgba(120, 53, 15, 0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            background: #fffdfa;
            border: 1.5px solid #e8d8c4;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 13.5px;
            color: #2c1a0e;
            font-weight: 600;
            outline: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-select:hover, .filter-select:focus {
            border-color: #92400e;
            box-shadow: 0 2px 8px rgba(146, 64, 14, 0.05);
        }

        .btn-filter {
            background: #92400e;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);
        }

        .btn-filter:hover {
            background: #78350f;
            transform: translateY(-1px);
        }

        /* ── Summary Cards ── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 16px;
        }

        .summary-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            border: 1px solid #e8d8c4;
            box-shadow: 0 4px 16px rgba(120, 53, 15, 0.02);
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
        }

        .theme-hadir::before { background: #22c55e; }
        .theme-izin::before  { background: #eab308; }
        .theme-sakit::before { background: #06b6d4; }
        .theme-alfa::before  { background: #ef4444; }
        .theme-belum::before { background: #9e7c62; }

        .card-label {
            font-size: 11px;
            font-weight: 800;
            color: #9e7c62;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-val {
            font-size: 26px;
            font-weight: 800;
            color: #2c1a0e;
        }

        /* ── Action Buttons ── */
        .recap-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-action-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 12px;
            background: white;
            border: 1.5px solid #e8d8c4;
            color: #6b4c35;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-action-outline:hover {
            background: #fffdfa;
            border-color: #92400e;
            color: #92400e;
            transform: translateY(-1px);
        }

        /* ── Table Card ── */
        .recap-table-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #e8d8c4;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(120, 53, 15, 0.03);
        }

        .recap-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .recap-table th {
            background: #fffdfa;
            padding: 16px 20px;
            font-size: 11.5px;
            font-weight: 800;
            color: #9e7c62;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 1.5px solid #e8d8c4;
        }

        .recap-table td {
            padding: 16px 20px;
            font-size: 13.5px;
            color: #2c1a0e;
            border-bottom: 1px solid #f7f0e6;
            vertical-align: middle;
        }

        .recap-table tr:last-child td {
            border-bottom: none;
        }

        .recap-table tr:hover td {
            background: #fffdfa;
        }

        .total-row {
            background: #fffdfa;
            font-weight: 700;
            border-top: 2px solid #e8d8c4;
        }

        .total-row td {
            font-weight: 700;
            color: #1c0f05;
        }

        /* ── Breadcrumb Link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #9e7c62;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            margin-bottom: 8px;
        }

        .back-link:hover {
            color: #92400e;
        }

        /* ── Responsive ── */
        @media (max-width: 1023px) {
            .summary-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .recap-header-card {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-select, .btn-filter {
                width: 100%;
            }
        }
    </style>

    <div class="recap-container">
        <!-- Breadcrumb back link -->
        <div>
            <a href="{{ route('admin.basic-reports.index') }}" class="back-link">
                ➔ Kembali ke Laporan Dasar
            </a>
        </div>

        <!-- Header Card -->
        <div class="recap-header-card">
            <div>
                <h1 style="font-size: 20px; font-weight: 800; color: #1c0f05; margin-bottom: 4px;">Rekap Absensi Bulanan</h1>
                <p style="font-size: 13px; color: #9e7c62;">
                    Periode: {{ $months[$month] }} {{ $year }} ({{ $daysInMonth }} hari)
                </p>
            </div>

            <form method="GET" action="{{ route('admin.reports.attendance') }}">
                <div class="filter-group">
                    {{-- Dropdown Bulan --}}
                    <select name="month" class="filter-select">
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ $month === $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>

                    {{-- Dropdown Tahun --}}
                    <select name="year" class="filter-select">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year === $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn-filter">Filter</button>
                </div>
            </form>
        </div>

        <!-- Summary Grid -->
        <div class="summary-grid">
            <div class="summary-card theme-hadir">
                <span class="card-label">Total Hadir</span>
                <span class="card-val">{{ $totals['hadir'] }}</span>
            </div>
            <div class="summary-card theme-izin">
                <span class="card-label">Total Izin</span>
                <span class="card-val">{{ $totals['izin'] }}</span>
            </div>
            <div class="summary-card theme-sakit">
                <span class="card-label">Total Sakit</span>
                <span class="card-val">{{ $totals['sakit'] }}</span>
            </div>
            <div class="summary-card theme-alfa">
                <span class="card-label">Total Alfa</span>
                <span class="card-val">{{ $totals['alfa'] }}</span>
            </div>
            <div class="summary-card theme-belum">
                <span class="card-label">Belum Dicatat</span>
                <span class="card-val">{{ $totals['belum_dicatat'] }}</span>
            </div>
        </div>

        <!-- Action Links -->
        <div class="recap-actions">
            <a href="{{ route('admin.reports.attendance.export', ['month' => $month, 'year' => $year]) }}" class="btn-action-outline">
                <svg style="width: 15px; height: 15px; color: #2d6a4f;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh CSV
            </a>
            <a href="{{ route('admin.reports.attendance.print', ['month' => $month, 'year' => $year]) }}" target="_blank" class="btn-action-outline">
                <svg style="width: 15px; height: 15px; color: #92400e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/>
                </svg>
                Cetak / Print
            </a>
        </div>

        <!-- Detailed Table -->
        <div class="recap-table-card">
            <table class="recap-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Karyawan</th>
                        <th>Status</th>
                        <th style="text-align: center; width: 100px;">Hadir</th>
                        <th style="text-align: center; width: 100px;">Izin</th>
                        <th style="text-align: center; width: 100px;">Sakit</th>
                        <th style="text-align: center; width: 100px;">Alfa</th>
                        <th style="text-align: center; width: 120px;">Belum Dicatat</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($recap as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                <span style="font-weight: 700; color: #1c0f05;">{{ $row['employee']->name }}</span>
                            </td>
                            <td>
                                @if($row['employee']->is_active)
                                    <span style="font-size: 11px; font-weight: 700; background: #ecfdf5; color: #059669; padding: 2px 8px; border-radius: 4px; border: 1px solid #a7f3d0; text-transform: uppercase;">Aktif</span>
                                @else
                                    <span style="font-size: 11px; font-weight: 700; background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 4px; border: 1px solid #fecaca; text-transform: uppercase; font-style: italic;">Nonaktif</span>
                                @endif
                            </td>
                            <td style="text-align: center; font-weight: 600; color: #166534;">{{ $row['hadir'] }}</td>
                            <td style="text-align: center; font-weight: 600; color: #b45309;">{{ $row['izin'] }}</td>
                            <td style="text-align: center; font-weight: 600; color: #0891b2;">{{ $row['sakit'] }}</td>
                            <td style="text-align: center; font-weight: 600; color: #be123c;">{{ $row['alfa'] }}</td>
                            <td style="text-align: center; font-weight: 600; color: #9e7c62;">{{ $row['belum_dicatat'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 30px; color: #9e7c62; font-style: italic;">
                                Tidak ada data absensi untuk periode ini.
                            </td>
                        </tr>
                    @endforelse

                    @if(count($recap) > 0)
                        <tr class="total-row">
                            <td colspan="3">TOTAL KESELURUHAN</td>
                            <td style="text-align: center;">{{ $totals['hadir'] }}</td>
                            <td style="text-align: center;">{{ $totals['izin'] }}</td>
                            <td style="text-align: center;">{{ $totals['sakit'] }}</td>
                            <td style="text-align: center;">{{ $totals['alfa'] }}</td>
                            <td style="text-align: center;">{{ $totals['belum_dicatat'] }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>

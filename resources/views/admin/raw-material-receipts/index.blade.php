<x-layouts.admin>
    <x-slot name="title">Penerimaan Bahan Baku</x-slot>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .page-title p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .btn-primary {
            background: var(--brown-400);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: var(--brown-500);
            transform: translateY(-1px);
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            padding: 14px 20px;
            background: #fcfaf8;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
        }

        .table td {
            padding: 14px 20px;
            font-size: 14px;
            color: var(--text-main);
            border-bottom: 1px solid #f8fafc;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .text-right { text-align: right; }
        
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: var(--text-muted);
        }
    </style>

    <div class="page-header">
        <div class="page-title">
            <h1>Penerimaan Bahan Baku</h1>
            <p>Kelola riwayat masuknya bahan mentah dari supplier.</p>
        </div>
        <a href="{{ route('admin.raw-material-receipts.create') }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Penerimaan
        </a>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Penerimaan</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>No. Nota</th>
                    <th class="text-right">Total Transaksi</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $receipt)
                    <tr>
                        <td>
                            <span style="font-weight: 700; color: var(--brown-400);">{{ $receipt->receipt_number }}</span>
                        </td>
                        <td>{{ date('d/m/Y', strtotime($receipt->receipt_date)) }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $receipt->supplier->name }}</div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $receipt->reference_number ?? '-' }}</span>
                        </td>
                        <td class="text-right" style="font-weight: 700;">
                            Rp {{ number_format($receipt->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.raw-material-receipts.show', $receipt) }}" style="color: var(--brown-400); font-weight: 600; text-decoration: none; font-size: 13px;">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.3;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                <p>Belum ada transaksi penerimaan bahan baku.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $receipts->links() }}
    </div>
</x-layouts.admin>

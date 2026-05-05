<x-layouts.user>
    <x-slot name="title">Produk Kopi</x-slot>

    <style>
        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 26px;
            font-weight: 700;
            color: #1c1917;
            margin-bottom: 6px;
        }

        .page-header p {
            color: #78716c;
            font-size: 14px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e7e5e4;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.25s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.1);
        }

        .product-img {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #1c1917, #44403c);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
        }

        .product-body {
            padding: 18px;
        }

        .product-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .badge-standard  { background: #fef3c7; color: #92400e; }
        .badge-premium   { background: #fce7f3; color: #9d174d; }

        .product-name {
            font-size: 16px;
            font-weight: 600;
            color: #1c1917;
            margin-bottom: 6px;
        }

        .product-desc {
            font-size: 13px;
            color: #78716c;
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 14px;
        }

        .btn-order {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            background: linear-gradient(135deg, #92400e, #b45309);
            color: white;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: opacity 0.2s;
        }

        .btn-order:hover { opacity: 0.9; }

        .empty-state {
            text-align: center;
            padding: 64px 24px;
            color: #78716c;
        }

        .empty-state span { font-size: 48px; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: 15px; }
    </style>

    <div class="page-header">
        <h1>☕ Produk Kopi Kami</h1>
        <p>Pilih produk kopi pilihan dan buat pesanan Anda.</p>
    </div>

    {{-- Produk akan ditampilkan di sini setelah modul produk selesai --}}
    <div class="empty-state">
        <span>☕</span>
        <p>Produk akan segera tersedia.<br>Hubungi admin untuk informasi lebih lanjut.</p>
    </div>
</x-layouts.user>

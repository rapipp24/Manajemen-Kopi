<x-layouts.user>
    <x-slot name="title">Katalog Produk</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header { margin-bottom: 24px; }
        .page-title  { font-size: 20px; font-weight: 700; color: #1c1917; letter-spacing: -0.03em; }
        .page-desc   { font-size: 13px; color: #78716c; margin-top: 3px; }

        /* ── Grid ────────────────────────────── */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
        }

        /* ── Product Card ────────────────────── */
        .product-card {
            background: #ffffff;
            border: 1px solid #ece8e3;
            border-radius: 12px;
            padding: 18px 20px;
            transition: border-color 0.18s, box-shadow 0.18s, transform 0.15s;
            position: relative;
        }
        .product-card:hover {
            border-color: #c8a882;
            box-shadow: 0 6px 18px rgba(0,0,0,0.07);
            transform: translateY(-1px);
        }

        .product-category {
            font-size: 10px; font-weight: 700; color: #b9a99a;
            text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 7px;
        }

        .product-name {
            font-size: 14.5px; font-weight: 700; color: #1c1917;
            line-height: 1.3; margin-bottom: 6px;
        }

        /* Weight pill */
        .product-weight {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 11.5px; font-weight: 600; color: #7a5c3e;
            background: #fdf3e7; border: 1px solid #f0d9b5;
            padding: 2px 9px; border-radius: 20px; margin-bottom: 14px;
        }

        .product-divider { height: 1px; background: #f5f0eb; margin-bottom: 14px; }

        .product-footer {
            display: flex; justify-content: space-between; align-items: center;
        }

        .product-price {
            font-size: 16px; font-weight: 800; color: #92400e;
            letter-spacing: -0.02em;
        }
        .product-price-label {
            font-size: 10px; color: #a8a29e; font-weight: 500; margin-bottom: 1px;
        }

        .product-stock {
            font-size: 11.5px; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
        }
        .stock-ok   { background: #f0fdf4; color: #16a34a; }
        .stock-low  { background: #fff7ed; color: #92400e; }
        .stock-none { background: #fef2f2; color: #dc2626; }

        /* ── Empty ───────────────────────────── */
        .empty-wrap { text-align: center; padding: 64px 20px; }
        .empty-emoji { font-size: 38px; margin-bottom: 12px; opacity: 0.3; }
        .empty-title { font-size: 14px; font-weight: 600; color: #78716c; margin-bottom: 4px; }
        .empty-desc  { font-size: 13px; color: #a8a29e; }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 600px) {
            .products-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
        }
        @media (max-width: 400px) {
            .products-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="page-header">
        <h1 class="page-title">Katalog Produk</h1>
        <p class="page-desc">Referensi produk kopi yang tersedia di gudang.</p>
    </div>

    @if($products->isEmpty())
        <div class="empty-wrap">
            <div class="empty-emoji">📦</div>
            <div class="empty-title">Belum ada produk tersedia</div>
            <div class="empty-desc">Hubungi admin untuk menambahkan produk.</div>
        </div>
    @else
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card">
                <div class="product-category">{{ $product->productCategory->name ?? 'Kopi' }}</div>
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-weight">
                    ⚖ {{ $product->weight }} Gram
                </div>
                <div class="product-divider"></div>
                <div class="product-footer">
                    <div>
                        <div class="product-price-label">Harga Jual</div>
                        <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                    @php
                        $stok = $product->current_stock;
                        $cls  = $stok <= 0 ? 'stock-none' : ($stok < 10 ? 'stock-low' : 'stock-ok');
                        $txt  = $stok <= 0 ? 'Habis' : number_format($stok, 0, ',', '.') . ' ' . ($product->unit->code ?? '');
                    @endphp
                    <span class="product-stock {{ $cls }}">{{ $txt }}</span>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</x-layouts.user>

<x-layouts.user>
    <x-slot name="title">Katalog Produk</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header { margin-bottom: 24px; }
        .page-title  { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; }
        .page-desc   { font-size: 13.5px; color: var(--muted); margin-top: 4px; }

        /* ── Grid ────────────────────────────── */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 16px;
        }

        /* ── Product Card ────────────────────── */
        .product-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.2s ease-in-out;
            position: relative;
            box-shadow: 0 2px 4px rgba(42, 23, 14, 0.01), 0 1px 2px rgba(42, 23, 14, 0.01);
        }
        .product-card:hover {
            border-color: var(--accent);
            box-shadow: 0 10px 25px -10px rgba(42, 23, 14, 0.05), 0 1px 3px rgba(42, 23, 14, 0.02);
            transform: translateY(-2px);
        }

        .product-category {
            font-size: 10px; font-weight: 700; color: var(--accent);
            text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px;
        }

        .product-name {
            font-size: 15px; font-weight: 700; color: var(--text);
            line-height: 1.4; margin-bottom: 8px;
        }

        /* Weight pill */
        .product-weight {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11px; font-weight: 600; color: #7a5c3e;
            background: #fbf5ee; border: 1px solid #f0dec5;
            padding: 3px 10px; border-radius: 6px; margin-bottom: 16px;
        }

        .product-divider { height: 1px; background: var(--border); margin-bottom: 16px; opacity: 0.5; }

        .product-footer {
            display: flex; justify-content: space-between; align-items: center;
        }

        .product-price {
            font-size: 16.5px; font-weight: 800; color: var(--text);
            letter-spacing: -0.02em;
        }
        .product-price-label {
            font-size: 10px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;
        }

        .product-stock {
            font-size: 11.5px; font-weight: 700;
            padding: 4px 12px; border-radius: 8px;
        }
        .stock-ok   { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .stock-low  { background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa; }
        .stock-none { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Empty ───────────────────────────── */
        .empty-wrap { text-align: center; padding: 64px 20px; background: #fff; border: 1px solid var(--border); border-radius: 12px; }
        .empty-emoji { font-size: 38px; margin-bottom: 12px; opacity: 0.3; }
        .empty-title { font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
        .empty-desc  { font-size: 13px; color: var(--muted); }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 600px) {
            .products-grid { grid-template-columns: 1fr; gap: 12px; }
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

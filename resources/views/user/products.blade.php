<x-layouts.user>
    <x-slot name="title">Katalog Produk</x-slot>

    <style>
        .page-header { margin-bottom: 24px; }
        .page-title { font-size: 20px; font-weight: 700; color: #1c1917; letter-spacing: -0.02em; }
        .page-desc { font-size: 13px; color: #78716c; margin-top: 4px; }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .product-card {
            background: #fff;
            border: 1px solid #e7e5e4;
            border-radius: 12px;
            padding: 18px 20px;
            transition: box-shadow 0.15s, border-color 0.15s;
        }

        .product-card:hover {
            border-color: #d6a96a;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .product-category {
            font-size: 10px;
            font-weight: 700;
            color: #a8a29e;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }

        .product-name {
            font-size: 15px;
            font-weight: 700;
            color: #1c1917;
            line-height: 1.3;
            margin-bottom: 4px;
        }

        .product-sku {
            font-size: 11px;
            color: #a8a29e;
            font-family: monospace;
            margin-bottom: 14px;
        }

        .product-divider {
            height: 1px;
            background: #f5f5f4;
            margin-bottom: 14px;
        }

        .product-stat-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .product-price {
            font-size: 17px;
            font-weight: 800;
            color: #92400e;
        }

        .product-stock {
            font-size: 12px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .stock-ok   { background: #f0fdf4; color: #166534; }
        .stock-low  { background: #fff7ed; color: #92400e; }
        .stock-none { background: #fef2f2; color: #991b1b; }
    </style>

    <div class="page-header">
        <h1 class="page-title">Katalog Produk</h1>
        <p class="page-desc">Daftar produk yang tersedia. Stok gudang diperbarui setiap saat.</p>
    </div>

    @if($products->isEmpty())
        <div style="text-align:center; padding: 60px 20px; color: #a8a29e;">
            <i data-lucide="package" style="width:48px;height:48px;margin-bottom:16px;opacity:0.3;display:block;margin:0 auto 16px;"></i>
            <p style="font-size:15px;font-weight:600;color:#78716c;">Belum ada produk tersedia</p>
            <p style="font-size:13px;margin-top:4px;">Hubungi admin untuk menambahkan produk.</p>
        </div>
    @else
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card">
                <div class="product-category">{{ $product->productCategory->name ?? 'Umum' }}</div>
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-sku">{{ $product->sku }}</div>
                <div style="font-size:12px;color:#78716c;margin-bottom:14px;display:flex;align-items:center;gap:4px;">
                    <i data-lucide="box" style="width:12px;height:12px;"></i> Kemasan: <strong>{{ $product->weight }} gr</strong>
                </div>
                <div class="product-divider"></div>
                <div class="product-stat-row">
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
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

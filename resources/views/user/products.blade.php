<x-layouts.user>
    <x-slot name="title">Katalog Produk</x-slot>

    <style>
        .page-header { margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-start; }
        .page-title { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 4px; letter-spacing: -0.02em; }
        .page-desc { color: #64748b; font-size: 14px; }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 20px;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .product-card:hover {
            border-color: #92400e;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .product-code {
            font-family: monospace;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 4px;
            width: fit-content;
        }

        .product-name {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .product-meta {
            font-size: 13px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .product-price {
            font-size: 18px;
            font-weight: 800;
            color: #92400e;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        .empty-state {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 80px 24px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            grid-column: 1 / -1;
        }
        .empty-icon {
            width: 64px;
            height: 64px;
            background: #f8fafc;
            color: #94a3b8;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .btn-action {
            background: #92400e;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-action:hover { background: #78350f; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Katalog Produk</h1>
            <p class="page-desc">Daftar produk tersedia yang dapat diajukan ke gudang.</p>
        </div>
        <a href="{{ route('sales.orders.create') }}" class="btn-action">
            <i data-lucide="plus-circle" style="width: 18px; height: 18px;"></i>
            Buat Pengajuan
        </a>
    </div>

    <div class="products-grid">
        @forelse($products as $product)
        <div class="product-card">
            <div class="product-code">{{ $product->code }}</div>
            <div>
                <h3 class="product-name">{{ $product->name }}</h3>
                <div class="product-meta">
                    <i data-lucide="tag" style="width: 14px; height: 14px;"></i>
                    {{ $product->productCategory->name ?? 'Tanpa Kategori' }} 
                    @if($product->variant) • {{ $product->variant }} @endif
                </div>
                @if($product->weight)
                <div class="product-meta" style="margin-top: 4px;">
                    <i data-lucide="scale" style="width: 14px; height: 14px;"></i>
                    <span style="font-size: 12px; color: #475569;">Isi/Berat: <b>{{ $product->weight }} gr</b></span>
                </div>
                @endif
                
                <div class="product-meta" style="margin-top: 10px; padding: 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; width: fit-content;">
                    <i data-lucide="package" style="width: 14px; height: 14px;"></i>
                    @php
                        $stock = (float) $product->current_stock;
                        $stockColor = $stock > 10 ? '#166534' : ($stock > 0 ? '#92400e' : '#991b1b');
                        $stockLabel = $stock > 0 ? 'Stok Gudang: ' . number_format($stock, 0) : 'Stok Habis';
                    @endphp
                    <span style="color: {{ $stockColor }}; font-size: 13px; font-weight: 800;">
                        {{ $stockLabel }} {{ $product->unit->code ?? '' }}
                    </span>
                </div>
            </div>
            <div class="product-price">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <i data-lucide="package-search" style="width: 32px; height: 32px;"></i>
            </div>
            <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Katalog Produk Belum Tersedia</h3>
            <p style="color: #64748b; font-size: 14px; max-width: 400px; line-height: 1.6; margin-bottom: 24px;">
                Saat ini belum ada daftar produk yang dapat ditampilkan. Silakan hubungi admin gudang untuk memperbarui ketersediaan stok produk.
            </p>
        </div>
        @endforelse
    </div>
</x-layouts.user>

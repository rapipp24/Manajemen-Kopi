<x-layouts.user>
    <x-slot name="title">Katalog Produk</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header { margin-bottom: 24px; }
        .page-title  { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; }
        .page-desc   { font-size: 13.5px; color: var(--muted); margin-top: 4px; }

        /* ── Search & Filter Controls ────────── */
        .catalog-controls {
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .search-box {
            position: relative;
            max-width: 480px;
            width: 100%;
        }

        .search-box input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 13.5px;
            color: var(--text);
            background: #ffffff;
            transition: all 0.15s ease-in-out;
        }

        .search-box input:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.15);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--muted);
            pointer-events: none;
        }

        #search-clear {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            border-radius: 4px;
        }
        #search-clear:hover {
            background: var(--brown-light);
            color: var(--text);
        }

        .filter-chips {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
            -webkit-overflow-scrolling: touch;
        }
        .filter-chips::-webkit-scrollbar {
            display: none;
        }

        .chip {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text);
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease-in-out;
        }

        .chip:hover {
            border-color: var(--accent);
            background: var(--cream);
        }

        .chip.active {
            background: var(--brown);
            border-color: var(--brown);
            color: #ffffff;
        }

        /* ── Grid ────────────────────────────── */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
        }

        /* ── Product Card ────────────────────── */
        .product-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-top: 2px solid rgba(197, 160, 89, 0.5); /* Subtle gold top accent line */
            border-radius: 8px;
            padding: 12px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 120px;
        }

        .product-card:hover {
            border-color: var(--accent);
            box-shadow: 0 6px 16px rgba(42, 23, 14, 0.05);
            transform: translateY(-2px);
        }

        .product-card.out-of-stock {
            opacity: 0.95;
        }

        .product-card-top {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .product-category {
            font-size: 9px;
            font-weight: 700;
            color: #8c7355;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 0;
        }

        .product-name {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.35;
            margin-top: 2px;
            letter-spacing: -0.01em;
        }

        .product-weight {
            font-size: 9px;
            font-weight: 600;
            color: var(--muted);
            background: var(--cream);
            border: 1px solid var(--border);
            padding: 1px 6px;
            border-radius: 4px;
            margin: 0;
        }

        .product-card-bottom {
            border-top: 1px solid rgba(234, 227, 210, 0.6);
            padding-top: 8px;
            margin-top: 8px;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 4px;
            flex-wrap: wrap;
        }

        .product-price {
            font-size: 13.5px;
            font-weight: 850;
            color: var(--brown);
            letter-spacing: -0.02em;
            line-height: 1;
            white-space: nowrap;
        }

        .product-stock {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 20px;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            white-space: nowrap;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .stock-ok   { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .stock-low  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        .stock-none { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        /* ── Empty ───────────────────────────── */
        .empty-wrap {
            text-align: center;
            padding: 32px 20px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
        }
        .empty-title { font-size: 13.5px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
        .empty-desc  { font-size: 12.5px; color: var(--muted); }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 768px) {
            .products-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .product-price { font-size: 12.5px; }
        }
        @media (max-width: 480px) {
            /* On very narrow cards, stack price above stock badge */
            .product-footer { flex-direction: column; align-items: flex-start; gap: 5px; }
        }
        @media (max-width: 430px) {
            .products-grid { grid-template-columns: 1fr; gap: 8px; }
            /* Single column — revert to row layout with space-between */
            .product-footer { flex-direction: row; align-items: flex-end; }
            .product-price { font-size: 13.5px; }
        }
    </style>

    <div class="page-header">
        <h1 class="page-title">Katalog Produk</h1>
        <p class="page-desc">Referensi produk kopi yang tersedia di gudang.</p>
    </div>

    @if($products->isEmpty() && $packages->isEmpty())
        <div class="empty-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; color: var(--muted); margin: 0 auto 12px; display: block;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
            <div class="empty-title">Belum ada produk atau paket tersedia</div>
            <div class="empty-desc">Hubungi admin untuk menambahkan produk atau paket.</div>
        </div>
    @else
        <!-- Search & Filter Controls -->
        <div class="catalog-controls">
            <!-- Search Box -->
            <div class="search-box">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" id="product-search" placeholder="Cari produk atau paket berdasarkan nama, kode, atau isi..." autocomplete="off">
                <button id="search-clear" style="display:none;" type="button">
                    <i data-lucide="x" style="width:14px;height:14px;"></i>
                </button>
            </div>

            <!-- Filter Chips -->
            <div class="filter-chips">
                <button type="button" class="chip active" data-type="all">
                    Semua
                </button>
                <button type="button" class="chip" data-type="product">
                    Produk
                </button>
                <button type="button" class="chip" data-type="package">
                    Paket / Pack
                </button>
            </div>
        </div>

        <!-- Empty State for Search -->
        <div id="empty-state-search" style="display: none; text-align: center; padding: 32px 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 10px; box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; color: var(--muted); margin: 0 auto 12px; display: block;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <div class="empty-title">Produk atau paket tidak ditemukan</div>
            <div class="empty-desc">Coba gunakan kata kunci lain atau pilih tab filter yang sesuai.</div>
        </div>

        <div class="products-grid">
            <!-- Products -->
            @foreach($products as $product)
            @php
                $stok = $product->current_stock;
                $isOutOfStock = $stok <= 0;
                $cls  = $isOutOfStock ? 'stock-none' : ($stok < 10 ? 'stock-low' : 'stock-ok');
                $txt  = $isOutOfStock ? 'Habis' : number_format($stok, 0, ',', '.') . ' ' . ($product->unit->code ?? '');
            @endphp
            <div class="product-card {{ $isOutOfStock ? 'out-of-stock' : '' }}"
                 data-name="{{ strtolower($product->name) }}"
                 data-type="product"
                 data-code=""
                 data-desc=""
                 data-weight="{{ $product->weight }}">
                
                <!-- Card Top Content -->
                <div class="product-card-top">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <span class="product-category">{{ $product->productCategory->name ?? 'Kopi' }}</span>
                        <span class="product-weight">{{ $product->weight }}g</span>
                    </div>
                    <div class="product-name">{{ $product->name }}</div>
                </div>

                <!-- Card Bottom Content -->
                <div class="product-card-bottom">
                    <div class="product-footer">
                        <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <span class="product-stock {{ $cls }}">{{ $txt }}</span>
                    </div>
                </div>

            </div>
            @endforeach

            <!-- Packages -->
            @foreach($packages as $pkg)
            @php
                $stok = $pkg->stock->qty ?? 0;
                $isOutOfStock = $stok <= 0;
                $cls  = $isOutOfStock ? 'stock-none' : ($stok < 5 ? 'stock-low' : 'stock-ok');
                $txt  = number_format($stok, 0, ',', '.') . ' pack';

                $itemsRingkas = $pkg->items->map(function($item) {
                    $unitName = $item->product->unit->name ?? 'pcs';
                    $qtyFormatted = $item->qty == (int)$item->qty ? (int)$item->qty : $item->qty;
                    return "{$qtyFormatted} {$unitName} {$item->product->name}";
                })->implode(', ');
            @endphp
            <div class="product-card {{ $isOutOfStock ? 'out-of-stock' : '' }}"
                 style="border-top: 2px solid var(--brown);"
                 data-name="{{ strtolower($pkg->name) }}"
                 data-type="package"
                 data-code="{{ strtolower($pkg->code) }}"
                 data-desc="{{ strtolower($itemsRingkas) }}"
                 data-weight="">
                
                <!-- Card Top Content -->
                <div class="product-card-top">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <span class="product-category" style="background: var(--brown); color: white; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: 700; text-transform: uppercase;">Paket</span>
                        <span style="font-size: 9px; font-weight: 600; color: var(--muted);"><code>{{ $pkg->code }}</code></span>
                    </div>
                    <div class="product-name">{{ $pkg->name }}</div>
                    <div style="font-size: 11px; color: var(--muted); margin-top: 4px; line-height: 1.4;">
                        Isi: {{ $itemsRingkas }}
                    </div>
                </div>

                <!-- Card Bottom Content -->
                <div class="product-card-bottom">
                    <div class="product-footer">
                        <div class="product-price">Rp {{ number_format($pkg->selling_price, 0, ',', '.') }}</div>
                        <span class="product-stock {{ $cls }}">{{ $txt }}</span>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('product-search');
            const clearBtn = document.getElementById('search-clear');
            const chips = document.querySelectorAll('.chip');
            const cards = document.querySelectorAll('.product-card');
            const emptyState = document.getElementById('empty-state-search');
            
            let activeType = 'all';
            let searchQuery = '';

            function filterProducts() {
                let visibleCount = 0;

                cards.forEach(card => {
                    const name = card.getAttribute('data-name') || '';
                    const type = card.getAttribute('data-type') || '';
                    const code = card.getAttribute('data-code') || '';
                    const desc = card.getAttribute('data-desc') || '';
                    const weight = card.getAttribute('data-weight') || '';

                    const matchesType = (activeType === 'all' || type === activeType);
                    const matchesSearch = (
                        searchQuery === '' ||
                        name.includes(searchQuery) ||
                        code.includes(searchQuery) ||
                        desc.includes(searchQuery) ||
                        weight.includes(searchQuery)
                    );

                    if (matchesType && matchesSearch) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }

            // Type filter click event
            chips.forEach(chip => {
                chip.addEventListener('click', function() {
                    chips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    activeType = this.getAttribute('data-type');
                    filterProducts();
                });
            });

            // Search input event
            searchInput.addEventListener('input', function() {
                searchQuery = this.value.trim().toLowerCase();
                if (searchQuery !== '') {
                    clearBtn.style.display = 'flex';
                } else {
                    clearBtn.style.display = 'none';
                }
                filterProducts();
            });

            // Search clear click event
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchQuery = '';
                this.style.display = 'none';
                filterProducts();
                searchInput.focus();
            });
        });
    </script>
</x-layouts.user>

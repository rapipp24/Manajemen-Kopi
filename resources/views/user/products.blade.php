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
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }

        /* ── Product Card ────────────────────── */
        .product-card {
            background: linear-gradient(180deg, #ffffff 0%, var(--cream) 100%);
            border: 1px solid var(--border);
            border-top: 3px solid var(--accent); /* Subtle premium gold top accent line */
            border-radius: 12px;
            padding: 18px 20px 20px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 0 2px 4px rgba(42, 23, 14, 0.02), 0 1px 1px rgba(42, 23, 14, 0.01);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            min-height: 220px; /* Force uniform card heights for perfect grid alignment */
        }

        .product-card:hover {
            border-color: var(--accent);
            box-shadow: 0 12px 24px -10px rgba(42, 23, 14, 0.12), 0 2px 4px rgba(42, 23, 14, 0.03);
            transform: translateY(-3px);
        }

        .product-card.out-of-stock {
            opacity: 0.9; /* Subtle treatment for out-of-stock items, readable and clean */
            background: linear-gradient(180deg, #fdfdfd 0%, #f7f6f2 100%);
        }

        .product-category {
            font-size: 10px;
            font-weight: 700;
            color: #8c7355; /* Muted warm brown */
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
            display: inline-block;
        }

        .product-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.4;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }

        /* Weight pill - simple and clean without icon */
        .product-weight {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
            color: var(--muted);
            background: #ffffff;
            border: 1px solid var(--border);
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .product-divider {
            height: 1px;
            background: var(--border);
            margin: 12px 0 14px;
            opacity: 0.4; /* Soft and thin */
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .product-price-label {
            font-size: 9.5px;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        .product-price {
            font-size: 16px;
            font-weight: 800;
            color: var(--brown);
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .product-stock {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 26px;
            font-size: 11px;
            font-weight: 700;
            padding: 0 10px;
            border-radius: 6px;
            white-space: nowrap;
            letter-spacing: 0.02em;
        }
        .stock-ok   { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .stock-low  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        .stock-none { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        /* ── Empty ───────────────────────────── */
        .empty-wrap {
            text-align: center;
            padding: 64px 20px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
        }
        .empty-emoji { font-size: 38px; margin-bottom: 12px; opacity: 0.3; }
        .empty-title { font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
        .empty-desc  { font-size: 13px; color: var(--muted); }

        #empty-state-search {
            display: none;
            text-align: center;
            padding: 64px 20px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
        }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 768px) {
            .products-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        }
        @media (max-width: 480px) {
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
        <!-- Search & Filter Controls -->
        <div class="catalog-controls">
            <!-- Search Box -->
            <div class="search-box">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" id="product-search" placeholder="Cari produk berdasarkan nama, jenis, atau berat..." autocomplete="off">
                <button id="search-clear" style="display:none;" type="button">
                    <i data-lucide="x" style="width:14px;height:14px;"></i>
                </button>
            </div>

            <!-- Filter Chips -->
            <div class="filter-chips">
                <button type="button" class="chip active" data-category-id="all">
                    Semua
                </button>
                @php
                    $activeCategories = $products->pluck('productCategory')->unique('id')->filter()->sortBy('name');
                @endphp
                @foreach($activeCategories as $cat)
                    <button type="button" class="chip" data-category-id="{{ $cat->id }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Empty State for Search -->
        <div id="empty-state-search">
            <div class="empty-emoji">🔍</div>
            <div class="empty-title">Produk tidak ditemukan</div>
            <div class="empty-desc">Coba gunakan kata kunci lain atau pilih kategori berbeda.</div>
        </div>

        <div class="products-grid">
            @foreach($products as $product)
            @php
                $stok = $product->current_stock;
                $isOutOfStock = $stok <= 0;
                $cls  = $isOutOfStock ? 'stock-none' : ($stok < 10 ? 'stock-low' : 'stock-ok');
                $txt  = $isOutOfStock ? 'Habis' : number_format($stok, 0, ',', '.') . ' ' . ($product->unit->code ?? '');
            @endphp
            <div class="product-card {{ $isOutOfStock ? 'out-of-stock' : '' }}"
                 data-name="{{ strtolower($product->name) }}"
                 data-category-id="{{ $product->product_category_id }}"
                 data-category-name="{{ strtolower($product->productCategory->name ?? '') }}"
                 data-weight="{{ $product->weight }}">
                
                <!-- Card Top Content -->
                <div class="product-card-top">
                    <div class="product-category">{{ $product->productCategory->name ?? 'Kopi' }}</div>
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-weight">
                        {{ $product->weight }} Gram
                    </div>
                </div>

                <!-- Card Bottom Content -->
                <div class="product-card-bottom">
                    <div class="product-divider"></div>
                    <div class="product-footer">
                        <div>
                            <div class="product-price-label">Harga</div>
                            <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
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
            
            let activeCategoryId = 'all';
            let searchQuery = '';

            function filterProducts() {
                let visibleCount = 0;

                cards.forEach(card => {
                    const name = card.getAttribute('data-name') || '';
                    const categoryId = card.getAttribute('data-category-id') || '';
                    const categoryName = card.getAttribute('data-category-name') || '';
                    const weight = card.getAttribute('data-weight') || '';

                    const matchesCategory = (activeCategoryId === 'all' || categoryId === activeCategoryId);
                    const matchesSearch = (
                        searchQuery === '' ||
                        name.includes(searchQuery) ||
                        categoryName.includes(searchQuery) ||
                        weight.includes(searchQuery)
                    );

                    if (matchesCategory && matchesSearch) {
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

            // Category filter click event
            chips.forEach(chip => {
                chip.addEventListener('click', function() {
                    chips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    activeCategoryId = this.getAttribute('data-category-id');
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

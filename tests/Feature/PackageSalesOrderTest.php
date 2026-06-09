<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\PackageStock;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesOrderPackageItem;
use App\Models\SalesStock;
use App\Models\SalesPackageStock;
use App\Models\StockMovement;
use App\Models\PackageStockMovement;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageSalesOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sales;
    private User $otherSales;
    private Product $product;
    private Package $package;
    private Package $inactivePackage;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Users
        $this->admin = User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@kopi.com',
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
            'is_active' => true,
            'approval_status' => User::APPROVAL_APPROVED
        ]);

        $this->sales = User::factory()->create([
            'name' => 'Sales Satu',
            'email' => 'sales1@kopi.com',
            'role' => User::ROLE_SALES,
            'email_verified_at' => now(),
            'is_active' => true,
            'approval_status' => User::APPROVAL_APPROVED
        ]);

        $this->otherSales = User::factory()->create([
            'name' => 'Sales Dua',
            'email' => 'sales2@kopi.com',
            'role' => User::ROLE_SALES,
            'email_verified_at' => now(),
            'is_active' => true,
            'approval_status' => User::APPROVAL_APPROVED
        ]);

        // Setup Unit
        $unit = Unit::create(['name' => 'Pcs', 'code' => 'PCS', 'type' => 'produk']);

        // Setup Product
        $this->product = Product::create([
            'code' => 'PRD01',
            'name' => 'Kopi Robusta',
            'variant' => 'Original',
            'weight' => 250,
            'unit_id' => $unit->id,
            'cost_price' => 10000,
            'price' => 15000,
            'is_active' => true
        ]);
        $this->product->current_stock = 50;
        $this->product->save();

        // Setup Customer
        $this->customer = Customer::create([
            'name' => 'Toko Harapan',
            'address' => 'Jl. Harapan',
            'phone' => '0812345678'
        ]);

        // Setup Packages
        $this->package = Package::create([
            'code' => 'PKT-001',
            'name' => 'Kopi Pack Spesial',
            'selling_price' => 50000,
            'is_active' => true,
        ]);
        PackageItem::create([
            'package_id' => $this->package->id,
            'product_id' => $this->product->id,
            'qty' => 3
        ]);
        // Set package warehouse stock
        PackageStock::create([
            'package_id' => $this->package->id,
            'qty' => 10
        ]);

        $this->inactivePackage = Package::create([
            'code' => 'PKT-002',
            'name' => 'Kopi Pack Inaktif',
            'selling_price' => 40000,
            'is_active' => false,
        ]);
        PackageStock::create([
            'package_id' => $this->inactivePackage->id,
            'qty' => 10
        ]);
    }

    /**
     * 1. Sales bisa membuat pengajuan paket saja.
     */
    public function test_sales_can_submit_order_with_packages_only()
    {
        $response = $this->actingAs($this->sales)
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'catatan' => 'Request Paket saja',
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 5
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.orders.index'));
        $this->assertEquals(1, SalesOrder::count());
        
        $order = SalesOrder::first();
        $this->assertEquals('menunggu', $order->status);
        $this->assertEquals(5 * 50000, $order->total);
        $this->assertEquals(0, $order->items()->count());
        $this->assertEquals(1, $order->packageItems()->count());
    }

    /**
     * 2. Sales bisa membuat pengajuan campuran produk satuan + paket.
     */
    public function test_sales_can_submit_mixed_order_products_and_packages()
    {
        $response = $this->actingAs($this->sales)
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'catatan' => 'Request Campuran',
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'qty' => 10
                    ]
                ],
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 4
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.orders.index'));
        $this->assertEquals(1, SalesOrder::count());
        
        $order = SalesOrder::first();
        $this->assertEquals('menunggu', $order->status);
        // Total = 10 * 15000 + 4 * 50000 = 150000 + 200000 = 350000
        $this->assertEquals(350000, $order->total);
        $this->assertEquals(1, $order->items()->count());
        $this->assertEquals(1, $order->packageItems()->count());
    }

    /**
     * 3. Sistem menolak pengajuan kosong.
     */
    public function test_system_rejects_empty_order()
    {
        $response = $this->actingAs($this->sales)
            ->from(route('sales.orders.create'))
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'catatan' => 'Kosong',
            ]);

        $response->assertRedirect(route('sales.orders.create'));
        $response->assertSessionHas('error', 'Gagal membuat pengajuan: Anda harus menambahkan minimal 1 produk satuan atau 1 paket.');
        $this->assertEquals(0, SalesOrder::count());
    }

    /**
     * 4. Sistem menolak package_id duplikat.
     */
    public function test_system_rejects_duplicate_package_ids()
    {
        $response = $this->actingAs($this->sales)
            ->from(route('sales.orders.create'))
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 1
                    ],
                    [
                        'package_id' => $this->package->id,
                        'qty' => 2
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.orders.create'));
        $response->assertSessionHas('error', 'Gagal membuat pengajuan: Tidak boleh ada paket duplikat.');
        $this->assertEquals(0, SalesOrder::count());
    }

    public function test_system_rejects_invalid_package_qty()
    {
        $response = $this->actingAs($this->sales)
            ->from(route('sales.orders.create'))
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 0
                    ]
                ]
            ]);
        $response->assertRedirect(route('sales.orders.create'));
        $response->assertSessionHasErrors(['package_items.0.qty']);

        $response = $this->actingAs($this->sales)
            ->from(route('sales.orders.create'))
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 2.5
                    ]
                ]
            ]);
        $response->assertRedirect(route('sales.orders.create'));
        $response->assertSessionHasErrors(['package_items.0.qty']);

        $this->assertEquals(0, SalesOrder::count());
    }

    /**
     * 6. Sistem menolak paket inactive/deleted.
     */
    public function test_system_rejects_inactive_packages()
    {
        $response = $this->actingAs($this->sales)
            ->from(route('sales.orders.create'))
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'package_items' => [
                    [
                        'package_id' => $this->inactivePackage->id,
                        'qty' => 1
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.orders.create'));
        $response->assertSessionHas('error', 'Gagal membuat pengajuan: Beberapa paket yang diajukan sedang tidak aktif.');
        $this->assertEquals(0, SalesOrder::count());
    }

    /**
     * 7. Sales submit pengajuan tidak mengubah stok.
     */
    public function test_sales_submit_order_does_not_affect_stocks()
    {
        $this->actingAs($this->sales)
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'items' => [
                    ['product_id' => $this->product->id, 'qty' => 10]
                ],
                'package_items' => [
                    ['package_id' => $this->package->id, 'qty' => 3]
                ]
            ]);

        // Stocks must be unchanged
        $this->assertEquals(50, $this->product->fresh()->current_stock);
        $this->assertEquals(10, PackageStock::where('package_id', $this->package->id)->first()->qty);
        $this->assertEquals(0, SalesStock::count());
        $this->assertEquals(0, SalesPackageStock::count());
        $this->assertEquals(0, StockMovement::count());
        $this->assertEquals(0, PackageStockMovement::count());
    }

    /**
     * 8. Admin approve pengajuan paket mengurangi package_stocks dan menambah sales_package_stocks, serta catat movements.
     */
    public function test_admin_can_approve_package_order_successfully()
    {
        $order = SalesOrder::create([
            'order_number' => 'REQ-0001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'menunggu',
            'total' => 200000,
        ]);

        SalesOrderPackageItem::create([
            'sales_order_id' => $order->id,
            'package_id' => $this->package->id,
            'qty' => 4,
            'harga' => 50000,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.sales-orders.update-status', $order), [
                'status' => 'diproses'
            ]);

        $response->assertRedirect(route('admin.sales-orders.show', $order));
        
        $this->assertEquals('diproses', $order->fresh()->status);

        // Warehouse stock reduced: 10 - 4 = 6
        $this->assertEquals(6, PackageStock::where('package_id', $this->package->id)->first()->qty);

        // Sales stock increased: 4
        $salesStock = SalesPackageStock::where('user_id', $this->sales->id)->where('package_id', $this->package->id)->first();
        $this->assertNotNull($salesStock);
        $this->assertEquals(4, $salesStock->qty);

        // Movements recorded: 2 movements
        $this->assertEquals(2, PackageStockMovement::count());

        $out = PackageStockMovement::where('movement_type', 'out')->first();
        $this->assertNull($out->user_id);
        $this->assertEquals(4, $out->qty);
        $this->assertEquals(10, $out->stock_before);
        $this->assertEquals(6, $out->stock_after);
        $this->assertEquals(SalesOrder::class, $out->reference_type);
        $this->assertEquals($order->id, $out->reference_id);

        $in = PackageStockMovement::where('movement_type', 'transfer_to_sales')->first();
        $this->assertEquals($this->sales->id, $in->user_id);
        $this->assertEquals(4, $in->qty);
        $this->assertEquals(0, $in->stock_before);
        $this->assertEquals(4, $in->stock_after);
        $this->assertEquals(SalesOrder::class, $in->reference_type);
        $this->assertEquals($order->id, $in->reference_id);
    }

    /**
     * 9. Jika stok paket kurang saat approve, approval gagal dan rollback.
     */
    public function test_admin_approval_fails_and_rolls_back_if_package_stock_insufficient()
    {
        $order = SalesOrder::create([
            'order_number' => 'REQ-0001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'menunggu',
            'total' => 600000,
        ]);

        SalesOrderPackageItem::create([
            'sales_order_id' => $order->id,
            'package_id' => $this->package->id,
            'qty' => 12, // 12 > 10 (available)
            'harga' => 50000,
            'subtotal' => 600000,
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.sales-orders.show', $order))
            ->patch(route('admin.sales-orders.update-status', $order), [
                'status' => 'diproses'
            ]);

        $response->assertRedirect(route('admin.sales-orders.show', $order));
        $response->assertSessionHas('error');

        $this->assertEquals('menunggu', $order->fresh()->status);
        $this->assertEquals(10, PackageStock::where('package_id', $this->package->id)->first()->qty);
        $this->assertNull(SalesPackageStock::where('user_id', $this->sales->id)->where('package_id', $this->package->id)->first());
        $this->assertEquals(0, PackageStockMovement::count());
    }

    /**
     * 10. Jika pengajuan campuran salah satu item kurang, seluruh approval rollback.
     */
    public function test_admin_approval_fails_and_rolls_back_if_any_mixed_item_stock_insufficient()
    {
        $order = SalesOrder::create([
            'order_number' => 'REQ-0001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'menunggu',
            'total' => 350000,
        ]);

        // Product item: 60 qty (needs 60 but stock is 50)
        SalesOrderItem::create([
            'sales_order_id' => $order->id,
            'product_id' => $this->product->id,
            'qty' => 60,
            'harga' => 15000,
            'subtotal' => 900000,
        ]);

        // Package item: 4 qty (stock is 10, sufficient)
        SalesOrderPackageItem::create([
            'sales_order_id' => $order->id,
            'package_id' => $this->package->id,
            'qty' => 4,
            'harga' => 50000,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.sales-orders.show', $order))
            ->patch(route('admin.sales-orders.update-status', $order), [
                'status' => 'diproses'
            ]);

        $response->assertRedirect(route('admin.sales-orders.show', $order));
        $response->assertSessionHas('error');

        // Rollback all
        $this->assertEquals('menunggu', $order->fresh()->status);
        $this->assertEquals(50, $this->product->fresh()->current_stock);
        $this->assertEquals(10, PackageStock::where('package_id', $this->package->id)->first()->qty);
        $this->assertNull(SalesStock::where('user_id', $this->sales->id)->where('product_id', $this->product->id)->first());
        $this->assertNull(SalesPackageStock::where('user_id', $this->sales->id)->where('package_id', $this->package->id)->first());
        $this->assertEquals(0, StockMovement::count());
        $this->assertEquals(0, PackageStockMovement::count());
    }

    /**
     * 11. Isolation: Item paket tidak mengubah products.current_stock & sales_stocks, dan vice versa.
     */
    public function test_isolation_between_products_and_packages_stocks()
    {
        $order = SalesOrder::create([
            'order_number' => 'REQ-0001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'menunggu',
            'total' => 200000,
        ]);

        SalesOrderPackageItem::create([
            'sales_order_id' => $order->id,
            'package_id' => $this->package->id,
            'qty' => 4,
            'harga' => 50000,
            'subtotal' => 200000,
        ]);

        // Approve package-only order
        $this->actingAs($this->admin)
            ->patch(route('admin.sales-orders.update-status', $order), ['status' => 'diproses']);

        // Check products.current_stock is still 50
        $this->assertEquals(50, $this->product->fresh()->current_stock);
        // Check sales_stocks is empty
        $this->assertEquals(0, SalesStock::count());

        // Now do a product-only order
        $order2 = SalesOrder::create([
            'order_number' => 'REQ-0002',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'menunggu',
            'total' => 150000,
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $order2->id,
            'product_id' => $this->product->id,
            'qty' => 10,
            'harga' => 15000,
            'subtotal' => 150000,
        ]);

        // Approve product-only order
        $this->actingAs($this->admin)
            ->patch(route('admin.sales-orders.update-status', $order2), ['status' => 'diproses']);

        // Check package_stocks is still 6 (from first order)
        $this->assertEquals(6, PackageStock::where('package_id', $this->package->id)->first()->qty);
        // Check sales_package_stocks is still 4
        $this->assertEquals(4, SalesPackageStock::where('user_id', $this->sales->id)->where('package_id', $this->package->id)->first()->qty);
    }

    /**
     * 12. Sales bisa melihat stok paket miliknya.
     */
    public function test_sales_can_see_their_own_package_stocks()
    {
        // Give package stock to sales 1
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        // Acting as sales 1, access delivery reports index
        $response = $this->actingAs($this->sales)
            ->get(route('sales.delivery-reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Kopi Pack Spesial');
        $response->assertSee('class="stok-card-qty" style="color: var(--accent);">5</span>', false);
    }

    /**
     * 13. Sales tidak bisa melihat stok paket sales lain.
     */
    public function test_sales_cannot_see_other_sales_package_stocks()
    {
        // Give package stock to sales 1
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        // Give package stock to sales 2
        SalesPackageStock::create([
            'user_id' => $this->otherSales->id,
            'package_id' => $this->package->id,
            'qty' => 2
        ]);

        // Acting as sales 2
        $response2 = $this->actingAs($this->otherSales)
            ->get(route('sales.delivery-reports.index'));

        $response2->assertStatus(200);
        $response2->assertSee('Kopi Pack Spesial');
        $response2->assertSee('class="stok-card-qty" style="color: var(--accent);">2</span>', false);
        $response2->assertDontSee('class="stok-card-qty" style="color: var(--accent);">5</span>', false);
    }

    /**
     * 14. Sales bisa melihat produk satuan dan paket aktif di katalog, sedangkan paket inaktif dan stok 0 tersembunyi.
     */
    public function test_sales_products_catalog_displays_products_and_packages_correctly()
    {
        // Setup package with 0 stock
        $zeroStockPackage = Package::create([
            'code' => 'PKT-003',
            'name' => 'Kopi Pack Kosong',
            'selling_price' => 30000,
            'is_active' => true,
        ]);
        PackageStock::create([
            'package_id' => $zeroStockPackage->id,
            'qty' => 0
        ]);

        $response = $this->actingAs($this->sales)
            ->get(route('sales.products'));

        $response->assertStatus(200);

        // 1. Sales can see products
        $response->assertSee('Kopi Robusta');

        // 2. Sales can see active packages with stock > 0
        $response->assertSee('Kopi Pack Spesial');
        $response->assertSee('PKT-001');
        $response->assertSee('10 pack');

        // 3. Inactive package is not displayed
        $response->assertDontSee('Kopi Pack Inaktif');

        // 4. Zero stock package is not displayed
        $response->assertDontSee('Kopi Pack Kosong');

        // 5. Page does not change any stock
        $this->assertEquals(50, $this->product->fresh()->current_stock);
        $this->assertEquals(10, PackageStock::where('package_id', $this->package->id)->first()->qty);
    }
}

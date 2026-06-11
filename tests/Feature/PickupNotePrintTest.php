<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesOrderPackageItem;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PickupNotePrintTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sales;
    private Product $product;
    private Package $package;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'approval_status' => User::APPROVAL_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->sales = User::factory()->create([
            'role' => User::ROLE_SALES,
            'is_active' => true,
            'approval_status' => User::APPROVAL_APPROVED,
            'email_verified_at' => now(),
        ]);

        $unit = Unit::create(['name' => 'Pcs', 'code' => 'PCS', 'type' => 'produk']);

        $this->product = Product::create([
            'code' => 'PRD-SATUAN',
            'name' => 'Kopi Robusta',
            'variant' => 'Original',
            'weight' => 250,
            'unit_id' => $unit->id,
            'cost_price' => 10000,
            'price' => 15000,
            'is_active' => true
        ]);

        $this->customer = Customer::create([
            'name' => 'Toko Makmur',
            'address' => 'Jl. Makmur',
            'phone' => '081234567890'
        ]);

        $this->package = Package::create([
            'code' => 'PKT-CAMPUR',
            'name' => 'Paket Campuran',
            'selling_price' => 50000,
            'is_active' => true
        ]);

        PackageItem::create([
            'package_id' => $this->package->id,
            'product_id' => $this->product->id,
            'qty' => 3
        ]);
    }

    /**
     * Helper to create a sales order
     */
    private function createSalesOrder(string $status = 'menunggu'): SalesOrder
    {
        $so = SalesOrder::create([
            'order_number' => 'SO-' . date('Ymd') . '-001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => $status,
            'catatan' => 'Test pengajuan',
            'total' => 115000
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $so->id,
            'product_id' => $this->product->id,
            'qty' => 1,
            'harga' => 15000,
            'subtotal' => 15000
        ]);

        SalesOrderPackageItem::create([
            'sales_order_id' => $so->id,
            'package_id' => $this->package->id,
            'qty' => 2,
            'harga' => 50000,
            'subtotal' => 100000
        ]);

        return $so;
    }

    /**
     * 1. Admin bisa membuka halaman nota pengambilan jika sales order approved.
     */
    public function test_admin_can_access_pickup_note_for_approved_sales_order()
    {
        $so = $this->createSalesOrder('diproses'); // 'diproses' means approved/disetujui

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sales-orders.pickup-note', $so));

        $response->assertOk();
    }

    /**
     * 2. Tombol Cetak Nota Pengambilan muncul di detail sales order approved.
     */
    public function test_print_button_shows_for_approved_sales_order()
    {
        $so = $this->createSalesOrder('diproses');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sales-orders.show', $so));

        $response->assertOk();
        $response->assertSee('Cetak Nota Pengambilan');
        $response->assertSee(route('admin.sales-orders.pickup-note', $so));
    }

    /**
     * 3. Tombol tidak muncul untuk sales order pending/menunggu.
     */
    public function test_print_button_does_not_show_for_pending_sales_order()
    {
        $so = $this->createSalesOrder('menunggu');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sales-orders.show', $so));

        $response->assertOk();
        $response->assertDontSee('Cetak Nota Pengambilan');
        $response->assertDontSee(route('admin.sales-orders.pickup-note', $so));
    }

    /**
     * 4. Halaman nota menampilkan item produk satuan sesuai pengajuan.
     */
    public function test_pickup_note_displays_product_items()
    {
        $so = $this->createSalesOrder('diproses');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sales-orders.pickup-note', $so));

        $response->assertSee($this->product->name);
        $response->assertSee($this->product->code);
    }

    /**
     * 5. Halaman nota menampilkan item paket sesuai pengajuan jika ada.
     */
    public function test_pickup_note_displays_package_items()
    {
        $so = $this->createSalesOrder('diproses');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sales-orders.pickup-note', $so));

        $response->assertSee('[PAKET] ' . $this->package->name);
        $response->assertSee($this->package->code);
    }

    /**
     * 6. Halaman nota tidak menampilkan harga/subtotal/HPP/tagihan.
     */
    public function test_pickup_note_does_not_display_financial_data()
    {
        $so = $this->createSalesOrder('diproses');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sales-orders.pickup-note', $so));

        // Total order was 115000, price was 15000 & 50000. These should not be seen in the response.
        $response->assertDontSee('115.000');
        $response->assertDontSee('15.000');
        $response->assertDontSee('50.000');
        $response->assertDontSee('Rp');
        $response->assertDontSee('HPP');
        $response->assertDontSee('Subtotal');
        $response->assertDontSee('Tagihan');
    }

    /**
     * 7. Sales/user non-admin tidak bisa akses route print admin.
     */
    public function test_non_admin_cannot_access_pickup_note_route()
    {
        $so = $this->createSalesOrder('diproses');

        $response = $this->actingAs($this->sales)
            ->get(route('admin.sales-orders.pickup-note', $so));

        $response->assertStatus(403);
    }
}

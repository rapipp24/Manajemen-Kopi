<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\PackageStock;
use App\Models\PackageAssembly;
use App\Models\DeliveryReport;
use App\Models\DeliveryReportItem;
use App\Models\DeliveryReportPackageItem;
use App\Models\SalesStock;
use App\Models\SalesPackageStock;
use App\Models\StockMovement;
use App\Models\PackageStockMovement;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageDeliveryReportTest extends TestCase
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
    }

    /**
     * 1. test_sales_can_submit_delivery_report_with_packages_only
     */
    public function test_sales_can_submit_delivery_report_with_packages_only()
    {
        // Give package stock to sales
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        // Post delivery report
        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 2
                    ]
                ]
            ]);

        $response->assertSessionHasNoErrors();

        // Assert report is created
        $report = DeliveryReport::first();
        $this->assertNotNull($report);
        $this->assertEquals(100000.00, $report->total_amount); // 2 * 50000

        // Assert package item is saved
        $pkgItem = DeliveryReportPackageItem::first();
        $this->assertNotNull($pkgItem);
        $this->assertEquals($this->package->id, $pkgItem->package_id);
        $this->assertEquals(2, $pkgItem->qty);
        $this->assertEquals(50000.00, $pkgItem->price);

        // Assert stock is decremented
        $salesStock = SalesPackageStock::where('user_id', $this->sales->id)->where('package_id', $this->package->id)->first();
        $this->assertEquals(3, $salesStock->qty);

        // Assert stock movement is recorded
        $movement = PackageStockMovement::first();
        $this->assertNotNull($movement);
        $this->assertEquals('sale', $movement->movement_type);
        $this->assertEquals(2, $movement->qty);
        $this->assertEquals(5, $movement->stock_before);
        $this->assertEquals(3, $movement->stock_after);

        // Assert no product item is created
        $this->assertEquals(0, DeliveryReportItem::count());
    }

    /**
     * 2. test_sales_can_submit_mixed_delivery_report
     */
    public function test_sales_can_submit_mixed_delivery_report()
    {
        // Give stocks to sales
        SalesStock::create([
            'user_id' => $this->sales->id,
            'product_id' => $this->product->id,
            'qty' => 10
        ]);
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        // Post delivery report
        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'qty' => 3
                    ]
                ],
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 2
                    ]
                ]
            ]);

        $response->assertSessionHasNoErrors();

        $report = DeliveryReport::first();
        $this->assertNotNull($report);
        // Total: 3 * 15000 (product price) + 2 * 50000 (package price) = 45000 + 100000 = 145000
        $this->assertEquals(145000.00, $report->total_amount);

        // Assert stocks are decremented
        $pStock = SalesStock::where('user_id', $this->sales->id)->where('product_id', $this->product->id)->first();
        $this->assertEquals(7, $pStock->qty);

        $pkgStock = SalesPackageStock::where('user_id', $this->sales->id)->where('package_id', $this->package->id)->first();
        $this->assertEquals(3, $pkgStock->qty);
    }

    /**
     * 3. test_delivery_report_fails_if_package_stock_insufficient
     */
    public function test_delivery_report_fails_if_package_stock_insufficient()
    {
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 1
        ]);

        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 2 // exceeds stock
                    ]
                ]
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, DeliveryReport::count());
        
        $pkgStock = SalesPackageStock::where('user_id', $this->sales->id)->where('package_id', $this->package->id)->first();
        $this->assertEquals(1, $pkgStock->qty); // unchanged
    }

    /**
     * 4. test_delivery_report_rejects_duplicate_package_items
     */
    public function test_delivery_report_rejects_duplicate_package_items()
    {
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 1
                    ],
                    [
                        'package_id' => $this->package->id, // duplicate
                        'qty' => 2
                    ]
                ]
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, DeliveryReport::count());
    }

    /**
     * 5. test_delivery_report_requires_at_least_one_product_or_package
     */
    public function test_delivery_report_requires_at_least_one_product_or_package()
    {
        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'items' => [],
                'package_items' => []
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, DeliveryReport::count());
    }

    /**
     * 6. test_report_margin_includes_package_hpp_snapshot
     */
    public function test_report_margin_includes_package_hpp_snapshot()
    {
        // Setup package assembly to set latest HPP (e.g. 30000)
        PackageAssembly::create([
            'assembly_number' => 'ASM-001',
            'package_id' => $this->package->id,
            'qty' => 5,
            'hpp_per_package_snapshot' => 30000.00,
            'created_by' => $this->admin->id
        ]);

        // Give stock
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        // Create delivery report
        $report = DeliveryReport::create([
            'report_number' => 'DEL-0001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => date('Y-m-d'),
            'status' => 'submitted',
            'total_amount' => 50000.00, // 1 pack
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales->id
        ]);

        DeliveryReportPackageItem::create([
            'delivery_report_id' => $report->id,
            'package_id' => $this->package->id,
            'qty' => 1,
            'price' => 50000.00,
            'subtotal' => 50000.00,
            'package_name_snapshot' => $this->package->name,
            'package_code_snapshot' => $this->package->code,
            'package_hpp_snapshot' => 30000.00 // from latest assembly
        ]);

        // Access Admin Report page and check calculation
        $response = $this->actingAs($this->admin)
            ->get(route('admin.reports', [
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d')
            ]));

        $response->assertStatus(200);
        
        // Revenue should include package: 50000
        // HPP should include package: 30000
        // Margin: 20000
        $response->assertSee('Rp 50.000'); // totalNilaiPenjualan
        $response->assertSee('Rp 30.000'); // totalHppProduk
        $response->assertSee('Rp 20.000'); // labaMargin
    }

    /**
     * 7. test_return_page_does_not_offer_package_items
     */
    public function test_return_page_offers_package_items()
    {
        // Give stocks
        SalesStock::create([
            'user_id' => $this->sales->id,
            'product_id' => $this->product->id,
            'qty' => 10
        ]);
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        // Create delivery report with both product and package
        $report = DeliveryReport::create([
            'report_number' => 'DEL-0002',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => date('Y-m-d'),
            'status' => 'submitted',
            'total_amount' => 65000.00, // 1 product (15000) + 1 package (50000)
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 1,
            'price' => 15000.00,
            'subtotal' => 15000.00
        ]);

        DeliveryReportPackageItem::create([
            'delivery_report_id' => $report->id,
            'package_id' => $this->package->id,
            'qty' => 1,
            'price' => 50000.00,
            'subtotal' => 50000.00,
            'package_name_snapshot' => $this->package->name,
            'package_code_snapshot' => $this->package->code,
            'package_hpp_snapshot' => 30000.00
        ]);

        // Go to returns create page for this report
        $response = $this->actingAs($this->sales)
            ->get(route('sales.returns.create', ['delivery_report_id' => $report->id]));

        $response->assertStatus(200);

        // Verify product item is listed
        $response->assertSee($this->product->name);

        // Verify package item is listed (and verify warning text)
        $response->assertSee('Return paket hanya dapat dilakukan untuk paket utuh/full pack. Return sebagian isi paket belum tersedia.');
        $response->assertSee($this->package->name);
    }

    /**
     * test_delivery_report_ignores_empty_product_row_when_package_is_valid
     */
    public function test_delivery_report_ignores_empty_product_row_when_package_is_valid()
    {
        // Give package stock to sales
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'items' => [
                    [
                        'product_id' => '',
                        'qty' => '',
                        'price' => ''
                    ]
                ],
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 2
                    ]
                ]
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(1, DeliveryReport::count());
        $this->assertEquals(0, DeliveryReportItem::count());
        $this->assertEquals(1, DeliveryReportPackageItem::count());
    }

    /**
     * test_delivery_report_ignores_empty_package_row_when_product_is_valid
     */
    public function test_delivery_report_ignores_empty_package_row_when_product_is_valid()
    {
        // Give product stock to sales
        SalesStock::create([
            'user_id' => $this->sales->id,
            'product_id' => $this->product->id,
            'qty' => 5
        ]);

        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'qty' => 2
                    ]
                ],
                'package_items' => [
                    [
                        'package_id' => '',
                        'qty' => ''
                    ]
                ]
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(1, DeliveryReport::count());
        $this->assertEquals(1, DeliveryReportItem::count());
        $this->assertEquals(0, DeliveryReportPackageItem::count());
    }

    /**
     * test_delivery_report_requires_at_least_one_valid_product_or_package
     */
    public function test_delivery_report_requires_at_least_one_valid_product_or_package()
    {
        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'items' => [
                    [
                        'product_id' => '',
                        'qty' => ''
                    ]
                ],
                'package_items' => [
                    [
                        'package_id' => '',
                        'qty' => ''
                    ]
                ]
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, DeliveryReport::count());
    }
}

<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\PackageStock;
use App\Models\SalesPackageStock;
use App\Models\DeliveryReport;
use App\Models\DeliveryReportItem;
use App\Models\DeliveryReportPackageItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\SalesReturnPackageItem;
use App\Models\PackageStockMovement;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageReturnTest extends TestCase
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
        $this->product->current_stock = 100;
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
    }

    /**
     * Helper to create a delivery report with package
     */
    private function createDeliveryReportWithPackage(int $qty = 2): array
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-PKG-001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => $this->package->selling_price * $qty,
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales->id
        ]);

        $drPkgItem = DeliveryReportPackageItem::create([
            'delivery_report_id' => $report->id,
            'package_id' => $this->package->id,
            'qty' => $qty,
            'price' => $this->package->selling_price,
            'subtotal' => $this->package->selling_price * $qty,
            'package_name_snapshot' => $this->package->name,
            'package_code_snapshot' => $this->package->code,
            'package_hpp_snapshot' => 30000,
        ]);

        return [$report, $drPkgItem];
    }

    /**
     * 1. test_sales_can_submit_package_return_full_pack_only
     */
    public function test_sales_can_submit_package_return_full_pack_only()
    {
        [$report, $drPkgItem] = $this->createDeliveryReportWithPackage(2);

        $stockBefore = PackageStock::where('package_id', $this->package->id)->first()?->qty ?? 0;

        $response = $this->actingAs($this->sales)
            ->post(route('sales.returns.store'), [
                'delivery_report_id' => $report->id,
                'return_date' => now()->format('Y-m-d'),
                'return_type' => 'potong_tagihan',
                'package_items' => [
                    [
                        'delivery_report_package_item_id' => $drPkgItem->id,
                        'qty' => 1,
                        'condition' => 'layak_jual',
                        'reason' => 'Toko batal ambil 1 pack'
                    ]
                ]
            ]);

        $response->assertRedirect();
        
        // Assert status is pending/menunggu
        $this->assertDatabaseHas('sales_returns', [
            'delivery_report_id' => $report->id,
            'status' => 'menunggu',
        ]);

        $this->assertDatabaseHas('sales_return_package_items', [
            'delivery_report_package_item_id' => $drPkgItem->id,
            'qty' => 1,
            'condition' => 'layak_jual',
            'replacement_note' => 'Toko batal ambil 1 pack'
        ]);

        // Stocks and billing must not change yet
        $stockAfter = PackageStock::where('package_id', $this->package->id)->first()?->qty ?? 0;
        $this->assertEquals($stockBefore, $stockAfter);
        $this->assertEquals('belum_bayar', $report->fresh()->payment_status);
        $this->assertEquals(0, PackageStockMovement::count());
    }

    /**
     * 2. test_package_return_rejects_fractional_qty
     */
    public function test_package_return_rejects_fractional_qty()
    {
        [$report, $drPkgItem] = $this->createDeliveryReportWithPackage(2);

        $response = $this->actingAs($this->sales)
            ->post(route('sales.returns.store'), [
                'delivery_report_id' => $report->id,
                'return_date' => now()->format('Y-m-d'),
                'return_type' => 'potong_tagihan',
                'package_items' => [
                    [
                        'delivery_report_package_item_id' => $drPkgItem->id,
                        'qty' => 0.5, // decimal/fractional
                        'condition' => 'layak_jual',
                        'reason' => 'Setengah pack'
                    ]
                ]
            ]);

        $response->assertSessionHasErrors();
    }

    /**
     * 3. test_package_return_cannot_exceed_delivered_qty
     */
    public function test_package_return_cannot_exceed_delivered_qty()
    {
        [$report, $drPkgItem] = $this->createDeliveryReportWithPackage(2);

        $response = $this->actingAs($this->sales)
            ->from(route('sales.returns.create', ['delivery_report_id' => $report->id]))
            ->post(route('sales.returns.store'), [
                'delivery_report_id' => $report->id,
                'return_date' => now()->format('Y-m-d'),
                'return_type' => 'potong_tagihan',
                'package_items' => [
                    [
                        'delivery_report_package_item_id' => $drPkgItem->id,
                        'qty' => 3, // exceeds delivered qty of 2
                        'condition' => 'layak_jual',
                        'reason' => 'Kelebihan return'
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.returns.create', ['delivery_report_id' => $report->id]));
        $response->assertSessionHas('error');
    }

    /**
     * 4. test_package_return_cannot_exceed_remaining_returnable_qty
     */
    public function test_package_return_cannot_exceed_remaining_returnable_qty()
    {
        [$report, $drPkgItem] = $this->createDeliveryReportWithPackage(2);

        // Submit first return of 1 pack
        $return1 = SalesReturn::create([
            'return_number' => 'RET-PKG-01',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'menunggu',
            'return_type' => 'potong_tagihan'
        ]);

        SalesReturnPackageItem::create([
            'sales_return_id' => $return1->id,
            'delivery_report_package_item_id' => $drPkgItem->id,
            'package_id' => $this->package->id,
            'qty' => 1,
            'price' => $this->package->selling_price,
            'subtotal' => $this->package->selling_price,
            'package_name_snapshot' => $this->package->name,
            'package_code_snapshot' => $this->package->code,
            'condition' => 'layak_jual'
        ]);

        // Submit second return of 2 packs. Remaining is only 1, so 2 should be rejected.
        $response = $this->actingAs($this->sales)
            ->from(route('sales.returns.create', ['delivery_report_id' => $report->id]))
            ->post(route('sales.returns.store'), [
                'delivery_report_id' => $report->id,
                'return_date' => now()->format('Y-m-d'),
                'return_type' => 'potong_tagihan',
                'package_items' => [
                    [
                        'delivery_report_package_item_id' => $drPkgItem->id,
                        'qty' => 2, // Exceeds remaining returnable qty (2 - 1 = 1)
                        'condition' => 'layak_jual',
                        'reason' => 'Spam return'
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.returns.create', ['delivery_report_id' => $report->id]));
        $response->assertSessionHas('error');
    }

    /**
     * 5. test_admin_approve_potong_tagihan_package_return_reduces_bill
     */
    public function test_admin_approve_potong_tagihan_package_return_reduces_bill()
    {
        [$report, $drPkgItem] = $this->createDeliveryReportWithPackage(2); // total tagihan = 100,000

        // Set DP to 50,000. Sisa tagihan = 50,000. payment_status = dp
        $report->update([
            'down_payment_amount' => 50000,
            'payment_status' => 'dp'
        ]);

        $return = SalesReturn::create([
            'return_number' => 'RET-PKG-02',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'menunggu',
            'return_type' => 'potong_tagihan'
        ]);

        SalesReturnPackageItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_package_item_id' => $drPkgItem->id,
            'package_id' => $this->package->id,
            'qty' => 1, // 1 * 50,000 = 50,000
            'price' => $this->package->selling_price,
            'subtotal' => $this->package->selling_price,
            'package_name_snapshot' => $this->package->name,
            'package_code_snapshot' => $this->package->code,
            'condition' => 'layak_jual'
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'layak_jual', // header condition
                'return_type' => 'potong_tagihan'   // header return type
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));

        $report->refresh();
        // Tagihan efektif = 100,000 - 50,000 = 50,000
        // Uang masuk = 50,000
        // Sisa tagihan = 0. Lunas!
        $this->assertEquals(0, $report->effective_remaining_amount);
        $this->assertEquals('lunas', $report->payment_status);
    }

    /**
     * 6. test_admin_approve_tukar_barang_package_return_does_not_reduce_bill
     */
    public function test_admin_approve_tukar_barang_package_return_does_not_reduce_bill()
    {
        [$report, $drPkgItem] = $this->createDeliveryReportWithPackage(2); // total tagihan = 100,000

        $return = SalesReturn::create([
            'return_number' => 'RET-PKG-03',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'menunggu',
            'return_type' => 'tukar_barang'
        ]);

        SalesReturnPackageItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_package_item_id' => $drPkgItem->id,
            'package_id' => $this->package->id,
            'qty' => 1,
            'price' => $this->package->selling_price,
            'subtotal' => $this->package->selling_price,
            'package_name_snapshot' => $this->package->name,
            'package_code_snapshot' => $this->package->code,
            'condition' => 'layak_jual'
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'layak_jual',
                'return_type' => 'tukar_barang'
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));

        $report->refresh();
        // Tukar barang should not reduce bill
        $this->assertEquals(100000, $report->effective_remaining_amount);
        $this->assertEquals('belum_bayar', $report->payment_status);
    }

    /**
     * 7. test_admin_approve_layak_jual_package_return_adds_warehouse_package_stock
     */
    public function test_admin_approve_layak_jual_package_return_adds_warehouse_package_stock()
    {
        [$report, $drPkgItem] = $this->createDeliveryReportWithPackage(2);

        $return = SalesReturn::create([
            'return_number' => 'RET-PKG-04',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'menunggu',
            'return_type' => 'potong_tagihan'
        ]);

        SalesReturnPackageItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_package_item_id' => $drPkgItem->id,
            'package_id' => $this->package->id,
            'qty' => 1,
            'price' => $this->package->selling_price,
            'subtotal' => $this->package->selling_price,
            'package_name_snapshot' => $this->package->name,
            'package_code_snapshot' => $this->package->code,
            'condition' => 'layak_jual' // package item condition
        ]);

        PackageStock::create([
            'package_id' => $this->package->id,
            'qty' => 5.00
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'layak_jual',
                'return_type' => 'potong_tagihan'
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));

        // Warehouse package stock should increase by 1
        $packageStock = PackageStock::where('package_id', $this->package->id)->first();
        $this->assertEquals(6.00, $packageStock->qty);

        // Check stock movement is recorded
        $movement = PackageStockMovement::first();
        $this->assertNotNull($movement);
        $this->assertEquals('return', $movement->movement_type);
        $this->assertEquals(1, $movement->qty);
        $this->assertNull($movement->user_id); // null = warehouse
        $this->assertEquals(5.00, $movement->stock_before);
        $this->assertEquals(6.00, $movement->stock_after);
        $this->assertEquals(SalesReturn::class, $movement->reference_type);
        $this->assertEquals($return->id, $movement->reference_id);
        $this->assertStringContainsString("Return paket dari toko", $movement->note);
    }

    /**
     * 8. test_admin_approve_not_sellable_package_return_does_not_add_warehouse_stock
     */
    public function test_admin_approve_not_sellable_package_return_does_not_add_warehouse_stock()
    {
        [$report, $drPkgItem] = $this->createDeliveryReportWithPackage(2);

        $return = SalesReturn::create([
            'return_number' => 'RET-PKG-05',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'menunggu',
            'return_type' => 'potong_tagihan'
        ]);

        SalesReturnPackageItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_package_item_id' => $drPkgItem->id,
            'package_id' => $this->package->id,
            'qty' => 1,
            'price' => $this->package->selling_price,
            'subtotal' => $this->package->selling_price,
            'package_name_snapshot' => $this->package->name,
            'package_code_snapshot' => $this->package->code,
            'condition' => 'tidak_layak_jual' // package item condition
        ]);

        PackageStock::create([
            'package_id' => $this->package->id,
            'qty' => 5.00
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'perlu_proses_ulang', // header condition
                'return_type' => 'potong_tagihan'
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));

        // Warehouse package stock should NOT increase
        $packageStock = PackageStock::where('package_id', $this->package->id)->first();
        $this->assertEquals(5.00, $packageStock->qty);

        // No package stock movement recorded
        $this->assertEquals(0, PackageStockMovement::count());

        // Billing reduced anyway
        $this->assertEquals(50000.00, $report->fresh()->effective_remaining_amount);
    }

    /**
     * 9. test_admin_approve_reprocess_package_return_does_not_add_warehouse_stock
     */
    public function test_admin_approve_reprocess_package_return_does_not_add_warehouse_stock()
    {
        [$report, $drPkgItem] = $this->createDeliveryReportWithPackage(2);

        $return = SalesReturn::create([
            'return_number' => 'RET-PKG-06',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'menunggu',
            'return_type' => 'potong_tagihan'
        ]);

        SalesReturnPackageItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_package_item_id' => $drPkgItem->id,
            'package_id' => $this->package->id,
            'qty' => 1,
            'price' => $this->package->selling_price,
            'subtotal' => $this->package->selling_price,
            'package_name_snapshot' => $this->package->name,
            'package_code_snapshot' => $this->package->code,
            'condition' => 'perlu_proses_ulang' // package item condition
        ]);

        PackageStock::create([
            'package_id' => $this->package->id,
            'qty' => 5.00
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'perlu_proses_ulang',
                'return_type' => 'potong_tagihan'
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));

        // Warehouse package stock should NOT increase
        $packageStock = PackageStock::where('package_id', $this->package->id)->first();
        $this->assertEquals(5.00, $packageStock->qty);

        // No package stock movement recorded
        $this->assertEquals(0, PackageStockMovement::count());
    }

    /**
     * 10. test_existing_product_return_still_works
     */
    public function test_existing_product_return_still_works()
    {
        // Setup a normal delivery report with a product
        $report = DeliveryReport::create([
            'report_number' => 'DR-PRD-001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => $this->product->price * 2,
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 2,
            'price' => $this->product->price,
            'subtotal' => $this->product->price * 2,
        ]);

        $return = SalesReturn::create([
            'return_number' => 'RET-PRD-01',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'menunggu',
            'return_type' => 'potong_tagihan'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 1,
            'price_snapshot' => $this->product->price,
            'subtotal_return' => $this->product->price
        ]);

        $stockBefore = $this->product->current_stock; // 100

        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'layak_jual',
                'return_type' => 'potong_tagihan'
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));

        $this->product->refresh();
        // Warehouse product stock increases by 1
        $this->assertEquals($stockBefore + 1, $this->product->current_stock);

        // Check stock movement is recorded
        $movement = StockMovement::first();
        $this->assertNotNull($movement);
        $this->assertEquals('in', $movement->movement_type);
        $this->assertEquals(1, $movement->qty);
        $this->assertNull($movement->user_id);

        $report->refresh();
        $this->assertEquals($this->product->price, $report->effective_remaining_amount);
    }
}

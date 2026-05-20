<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\DeliveryReport;
use App\Models\DeliveryReportItem;
use App\Models\Product;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesReturnTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $sales1;
    protected $sales2;
    protected $product;
    protected $unit;
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->sales1 = User::factory()->create(['role' => 'sales']);
        $this->sales2 = User::factory()->create(['role' => 'sales']);

        // Buat master data
        $this->unit = Unit::create(['name' => 'Pcs', 'code' => 'PCS', 'type' => 'produk']);
        $this->product = Product::create([
            'code' => 'PRD01',
            'name' => 'Kopi Robusta',
            'variant' => 'Original',
            'weight' => 250,
            'unit_id' => $this->unit->id,
            'cost_price' => 10000,
            'price' => 15000,
            'current_stock' => 100,
            'is_active' => true
        ]);
        $this->customer = Customer::create([
            'name' => 'Toko Sedap',
            'address' => 'Jl. Kopi No. 1',
            'phone' => '08123456789'
        ]);
    }

    /**
     * Test 1: Sales hanya bisa membuat return dari Delivery Report miliknya sendiri.
     */
    public function test_sales_cannot_create_return_from_other_sales_delivery_report()
    {
        // Delivery Report milik Sales 1
        $report = DeliveryReport::create([
            'report_number' => 'DR-001',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 30000,
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 2,
            'price' => 15000,
            'subtotal' => 30000
        ]);

        // Coba ajukan sebagai Sales 2
        $response = $this->actingAs($this->sales2)
            ->post(route('sales.returns.store'), [
                'delivery_report_id' => $report->id,
                'return_date' => now()->format('Y-m-d'),
                'items' => [
                    [
                        'delivery_report_item_id' => $drItem->id,
                        'qty_return' => 1,
                        'reason' => 'Rusak'
                    ]
                ]
            ]);

        $response->assertStatus(404); // Kunci keamanan: fail or 404 karena firstOrFail
    }

    /**
     * Test 2: Qty return tidak boleh melebihi qty terkirim.
     */
    public function test_qty_return_cannot_exceed_available_qty()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-001',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 150000,
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 10, // Terkirim 10
            'price' => 15000,
            'subtotal' => 150000
        ]);

        // Coba ajukan return 11 pcs (lebih besar dari 10)
        $response = $this->actingAs($this->sales1)
            ->from(route('sales.returns.create', ['delivery_report_id' => $report->id]))
            ->post(route('sales.returns.store'), [
                'delivery_report_id' => $report->id,
                'return_date' => now()->format('Y-m-d'),
                'items' => [
                    [
                        'delivery_report_item_id' => $drItem->id,
                        'qty_return' => 11,
                        'reason' => 'Rusak'
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.returns.create', ['delivery_report_id' => $report->id]));
        $response->assertSessionHas('error');
    }

    /**
     * Test 3: Submit return oleh sales tidak mengubah stok gudang / stock movements.
     */
    public function test_submitting_return_does_not_affect_warehouse_stock()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-001',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 30000,
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 2,
            'price' => 15000,
            'subtotal' => 30000
        ]);

        $stockBefore = $this->product->current_stock; // 100

        // Submit return
        $response = $this->actingAs($this->sales1)
            ->post(route('sales.returns.store'), [
                'delivery_report_id' => $report->id,
                'return_date' => now()->format('Y-m-d'),
                'items' => [
                    [
                        'delivery_report_item_id' => $drItem->id,
                        'qty_return' => 1,
                        'reason' => 'Rusak'
                    ]
                ]
            ]);

        $this->product->refresh();
        $this->assertEquals($stockBefore, $this->product->current_stock); // Stok tetap 100
        $this->assertEquals(0, StockMovement::count()); // Tidak ada movement
    }

    /**
     * Test 4: Admin menerima return menambah stok gudang dan membuat stock movement IN.
     */
    public function test_admin_accept_return_updates_stock_and_creates_movement()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-001',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 30000,
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 2,
            'price' => 15000,
            'subtotal' => 30000
        ]);

        // Buat return header & item
        $return = SalesReturn::create([
            'return_number' => 'RET-01',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 2,
            'price_snapshot' => 15000,
            'subtotal_return' => 30000
        ]);

        $stockBefore = $this->product->current_stock; // 100

        // Admin terima return
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return));

        $response->assertRedirect(route('admin.returns.show', $return));
        
        $this->product->refresh();
        $this->assertEquals($stockBefore + 2, $this->product->current_stock); // Stok bertambah jadi 102

        // Cek movement
        $movement = StockMovement::first();
        $this->assertNotNull($movement);
        $this->assertEquals('IN', $movement->movement_type);
        $this->assertEquals(2, $movement->qty);
        $this->assertNull($movement->user_id); // null = stok gudang
        $this->assertEquals($stockBefore, $movement->stock_before);
        $this->assertEquals($stockBefore + 2, $movement->stock_after);
    }

    /**
     * Test 5: Admin menolak return mengubah status jadi ditolak dan tidak mengubah stok.
     */
    public function test_admin_reject_return_does_not_affect_stock()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-001',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 30000,
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 2,
            'price' => 15000,
            'subtotal' => 30000
        ]);

        // Buat return header & item
        $return = SalesReturn::create([
            'return_number' => 'RET-01',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 2,
            'price_snapshot' => 15000,
            'subtotal_return' => 30000
        ]);

        $stockBefore = $this->product->current_stock; // 100

        // Admin tolak return
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.reject', $return), [
                'rejection_reason' => 'Barang tidak sesuai'
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));
        
        $this->product->refresh();
        $this->assertEquals($stockBefore, $this->product->current_stock); // Stok tetap 100
        $this->assertEquals(0, StockMovement::count()); // Tidak ada movement

        $return->refresh();
        $this->assertEquals('ditolak', $return->status);
        $this->assertEquals('Barang tidak sesuai', $return->rejection_reason);
    }
}

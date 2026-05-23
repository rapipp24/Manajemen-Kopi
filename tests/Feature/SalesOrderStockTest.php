<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesStock;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesOrderStockTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $sales;
    protected $product;
    protected $unit;
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true
        ]);
        $this->sales = User::factory()->create([
            'role' => 'sales',
            'email_verified_at' => now(),
            'is_active' => true
        ]);

        $this->unit = Unit::create(['name' => 'Pcs', 'code' => 'PCS', 'type' => 'produk']);
        $this->product = Product::create([
            'code' => 'PRD01',
            'name' => 'Kopi Robusta',
            'variant' => 'Original',
            'weight' => 250,
            'unit_id' => $this->unit->id,
            'cost_price' => 10000,
            'price' => 15000,
            'is_active' => true
        ]);
        
        // Atasi proteksi mass-assignment untuk current_stock
        $this->product->current_stock = 50;
        $this->product->save();

        $this->customer = Customer::create([
            'name' => 'Toko Sedap',
            'address' => 'Jl. Kopi No. 1',
            'phone' => '08123456789'
        ]);
    }

    /**
     * Test 1: Sales submit dengan qty melebihi stok gudang ditolak.
     */
    public function test_sales_cannot_submit_qty_exceeding_warehouse_stock()
    {
        $response = $this->actingAs($this->sales)
            ->from(route('sales.orders.create'))
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'catatan' => 'Test',
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'qty' => 60 // 60 > 50 (current_stock)
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.orders.create'));
        $response->assertSessionHas('error');
        $this->assertEquals(50, $this->product->fresh()->current_stock);
        $this->assertEquals(0, SalesOrder::count());
        $this->assertEquals(0, StockMovement::count());
    }

    /**
     * Test 2: Produk sama ditambahkan beberapa kali total qty melebihi stok gudang ditolak.
     */
    public function test_sales_cannot_submit_accumulated_qty_exceeding_warehouse_stock()
    {
        $response = $this->actingAs($this->sales)
            ->from(route('sales.orders.create'))
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'catatan' => 'Test',
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'qty' => 30
                    ],
                    [
                        'product_id' => $this->product->id,
                        'qty' => 30 // Total 60 > 50
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.orders.create'));
        $response->assertSessionHas('error');
        $this->assertEquals(50, $this->product->fresh()->current_stock);
        $this->assertEquals(0, SalesOrder::count());
        $this->assertEquals(0, StockMovement::count());
    }

    /**
     * Test 3: Backend tetap menolak jika frontend dibypass (manipulasi qty > stok).
     */
    public function test_backend_always_blocks_qty_exceeding_stock_when_frontend_is_bypassed()
    {
        $response = $this->actingAs($this->sales)
            ->from(route('sales.orders.create'))
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'items' => [
                    ['product_id' => $this->product->id, 'qty' => 100]
                ]
            ]);

        $response->assertRedirect(route('sales.orders.create'));
        $response->assertSessionHas('error');
        $this->assertEquals(0, SalesOrder::count());
    }

    /**
     * Test 4: Sales submit valid berhasil dibuat dengan status menunggu, stok tidak berubah, no movement.
     */
    public function test_sales_can_submit_valid_qty_without_changing_stock()
    {
        $response = $this->actingAs($this->sales)
            ->post(route('sales.orders.store'), [
                'customer_id' => $this->customer->id,
                'catatan' => 'Test',
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'qty' => 20
                    ]
                ]
            ]);

        $response->assertRedirect(route('sales.orders.index'));
        $this->assertEquals(1, SalesOrder::count());
        
        $order = SalesOrder::first();
        $this->assertEquals('menunggu', $order->status);
        $this->assertEquals(50, $this->product->fresh()->current_stock);
        $this->assertEquals(0, StockMovement::count());
    }

    /**
     * Test 5: Admin approve stok cukup mengurangi gudang, menambah sales, mencatat movements, status diproses.
     */
    public function test_admin_can_approve_valid_order_successfully()
    {
        $order = SalesOrder::create([
            'order_number' => 'REQ-20260524-001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'menunggu',
            'total' => 300000,
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $order->id,
            'product_id' => $this->product->id,
            'qty' => 20,
            'harga' => 15000,
            'subtotal' => 300000,
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.sales-orders.show', $order))
            ->patch(route('admin.sales-orders.update-status', $order), [
                'status' => 'diproses'
            ]);

        $response->assertRedirect(route('admin.sales-orders.show', $order));
        
        $this->assertEquals('diproses', $order->fresh()->status);
        $this->assertEquals(30, $this->product->fresh()->current_stock); // 50 - 20
        
        $salesStock = SalesStock::where('user_id', $this->sales->id)->where('product_id', $this->product->id)->first();
        $this->assertNotNull($salesStock);
        $this->assertEquals(20, $salesStock->qty);

        // Movements: OUT gudang (user_id = null), IN sales (user_id = sales_id)
        $this->assertEquals(2, StockMovement::count());
        $out = StockMovement::where('movement_type', 'out')->first();
        $this->assertNull($out->user_id);
        $this->assertEquals(20, $out->qty);

        $in = StockMovement::where('movement_type', 'in')->first();
        $this->assertEquals($this->sales->id, $in->user_id);
        $this->assertEquals(20, $in->qty);
    }

    /**
     * Test 6: Admin approve gagal & rollback jika stok tiba-tiba tidak cukup.
     */
    public function test_admin_approval_fails_and_rolls_back_if_stock_becomes_insufficient()
    {
        $order = SalesOrder::create([
            'order_number' => 'REQ-20260524-001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'menunggu',
            'total' => 300000,
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $order->id,
            'product_id' => $this->product->id,
            'qty' => 20,
            'harga' => 15000,
            'subtotal' => 300000,
        ]);

        // Simulasikan stok dikurangi transaksi lain di latar belakang menjadi 10 pcs
        $this->product->current_stock = 10;
        $this->product->save();

        $response = $this->actingAs($this->admin)
            ->from(route('admin.sales-orders.show', $order))
            ->patch(route('admin.sales-orders.update-status', $order), [
                'status' => 'diproses'
            ]);

        $response->assertRedirect(route('admin.sales-orders.show', $order));
        $response->assertSessionHas('error');
        $this->assertEquals('menunggu', $order->fresh()->status);
        $this->assertEquals(10, $this->product->fresh()->current_stock);
        $this->assertEquals(0, StockMovement::count());
        $this->assertNull(SalesStock::where('user_id', $this->sales->id)->where('product_id', $this->product->id)->first());
    }

    /**
     * Test 7: Reject pengajuan menunggu sukses mengubah status dibatalkan, stok utuh, no movement.
     */
    public function test_admin_can_reject_order_with_waiting_status()
    {
        $order = SalesOrder::create([
            'order_number' => 'REQ-20260524-001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'menunggu',
            'total' => 300000,
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.sales-orders.show', $order))
            ->patch(route('admin.sales-orders.update-status', $order), [
                'status' => 'dibatalkan'
            ]);

        $response->assertRedirect(route('admin.sales-orders.show', $order));
        $this->assertEquals('dibatalkan', $order->fresh()->status);
        $this->assertEquals(50, $this->product->fresh()->current_stock);
        $this->assertEquals(0, StockMovement::count());
    }

    /**
     * Test 8: Reject pengajuan yang sudah diproses ditolak.
     */
    public function test_admin_cannot_reject_already_processed_order()
    {
        $order = SalesOrder::create([
            'order_number' => 'REQ-20260524-001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'diproses',
            'total' => 300000,
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.sales-orders.show', $order))
            ->patch(route('admin.sales-orders.update-status', $order), [
                'status' => 'dibatalkan'
            ]);

        $response->assertRedirect(route('admin.sales-orders.show', $order));
        $response->assertSessionHas('error');
        $this->assertEquals('diproses', $order->fresh()->status);
        $this->assertEquals(50, $this->product->fresh()->current_stock);
        $this->assertEquals(0, StockMovement::count());
    }

    /**
     * Test 9: Double approve ditolak.
     */
    public function test_admin_cannot_approve_already_processed_order()
    {
        $order = SalesOrder::create([
            'order_number' => 'REQ-20260524-001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'status' => 'diproses',
            'total' => 300000,
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.sales-orders.show', $order))
            ->patch(route('admin.sales-orders.update-status', $order), [
                'status' => 'diproses'
            ]);

        $response->assertRedirect(route('admin.sales-orders.show', $order));
        $response->assertSessionHas('error');
        $this->assertEquals('diproses', $order->fresh()->status);
        $this->assertEquals(50, $this->product->fresh()->current_stock);
    }
}

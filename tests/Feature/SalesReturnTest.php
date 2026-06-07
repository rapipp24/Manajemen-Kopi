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
            'is_active' => true
        ]);
        $this->product->current_stock = 100;
        $this->product->save();
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
                'return_type' => 'potong_tagihan',
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
                'return_type' => 'potong_tagihan',
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
                'return_type' => 'potong_tagihan',
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

        // Admin terima return dengan layak_jual
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'layak_jual',
                'return_type' => 'potong_tagihan',
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));
        
        $this->product->refresh();
        $this->assertEquals($stockBefore + 2, $this->product->current_stock); // Stok bertambah jadi 102

        // Cek movement
        $movement = StockMovement::first();
        $this->assertNotNull($movement);
        $this->assertEquals('in', $movement->movement_type);
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

    /**
     * Test 6: Sidebar admin menampilkan badge tanda merah jika ada pengajuan return dengan status 'menunggu'.
     */
    public function test_admin_sidebar_displays_pending_return_count_badge()
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

        // Sebelum ada return menunggu
        $response = $this->actingAs($this->admin)->get(route('admin.returns.index'));
        $response->assertStatus(200);
        $response->assertDontSee('background: #ef4444');

        // Buat return dengan status 'menunggu'
        $return = SalesReturn::create([
            'return_number' => 'RET-01',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        // Sesudah ada return menunggu
        $response = $this->actingAs($this->admin)->get(route('admin.returns.index'));
        $response->assertStatus(200);
        $response->assertSee('background: #ef4444');
        $response->assertSee('Verifikasi Return');
    }

    /**
     * Test 7: Admin menerima return dengan kondisi perlu_proses_ulang tidak menambah stok gudang / stock movements.
     */
    public function test_admin_accept_return_with_rework_does_not_update_stock_or_movement()
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

        // Admin terima return dengan perlu_proses_ulang
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'perlu_proses_ulang',
                'return_type' => 'potong_tagihan',
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));
        
        $this->product->refresh();
        $this->assertEquals($stockBefore, $this->product->current_stock); // Stok TIDAK bertambah (tetap 100)
        $this->assertEquals(0, StockMovement::count()); // Tidak ada stock movement yang dibuat

        $return->refresh();
        $this->assertEquals('diterima', $return->status);
        $this->assertEquals('perlu_proses_ulang', $return->return_condition);
    }

    /**
     * Test 8: Admin menerima return tanpa memasukkan return_condition menghasilkan validation error.
     */
    public function test_admin_accept_return_validation()
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

        $return = SalesReturn::create([
            'return_number' => 'RET-01',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => '', // kosong
                'return_type' => '', // kosong
            ]);

        $response->assertSessionHasErrors(['return_condition', 'return_type']);
        $this->assertEquals('menunggu', $return->fresh()->status);
    }

    /**
     * Test 9: Admin dapat menyelesaikan bayar lebih secara manual dengan catatan.
     */
    public function test_admin_resolves_overpayment()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-001',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 30000,
            'payment_status' => 'lunas',
            'down_payment_amount' => 30000, // sudah bayar lunas Rp 30.000
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 2,
            'price' => 15000,
            'subtotal' => 30000
        ]);

        // Buat return diterima senilai Rp 15.000
        $return = SalesReturn::create([
            'return_number' => 'RET-01',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'diterima',
            'return_condition' => 'layak_jual'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 1, // return 1 pcs
            'price_snapshot' => 15000,
            'subtotal_return' => 15000
        ]);

        // Tagihan efektif = 30000 - 15000 = 15000
        // Uang masuk = 30000
        // Sisa tagihan setelah return = 15000 - 30000 = -15000 (Bayar lebih 15000)
        
        $this->assertTrue($report->fresh()->is_overpaid);
        $this->assertEquals(15000, $report->fresh()->overpayment_amount);
        $this->assertNull($report->fresh()->overpayment_resolved_at);

        // Admin menandai diselesaikan
        $response = $this->actingAs($this->admin)
            ->post(route('admin.delivery-reports.resolve-overpayment', $report), [
                'overpayment_resolution_note' => 'Kelebihan bayar 15rb sudah dikembalikan tunai ke toko.',
            ]);

        $response->assertSessionHas('success');
        $report->refresh();
        $this->assertNotNull($report->overpayment_resolved_at);
        $this->assertEquals($this->admin->id, $report->overpayment_resolved_by);
        $this->assertEquals('Kelebihan bayar 15rb sudah dikembalikan tunai ke toko.', $report->overpayment_resolution_note);
    }

    /**
     * Test 10: Return diterima membuat DP menjadi lunas
     */
    public function test_return_received_makes_dp_lunas()
    {
        // Delivery total 100000
        $report = DeliveryReport::create([
            'report_number' => 'DR-101',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'dp',
            'down_payment_amount' => 70000, // Setoran disetujui 70000
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 10,
            'price' => 10000,
            'subtotal' => 100000
        ]);

        // Return diterima 30000
        $return = SalesReturn::create([
            'return_number' => 'RET-101',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 3, // 3 * 10000 = 30000
            'price_snapshot' => 10000,
            'subtotal_return' => 30000
        ]);

        // Admin approve return
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'perlu_proses_ulang',
                'return_type' => 'potong_tagihan',
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));
        
        $report->refresh();
        $this->assertEquals(0, $report->effective_remaining_amount);
        $this->assertEquals('lunas', $report->payment_status);
    }

    /**
     * Test 11: Return pending tidak mengubah status
     */
    public function test_return_pending_does_not_change_status()
    {
        // Delivery total 100000, setoran disetujui 70000, status dp
        $report = DeliveryReport::create([
            'report_number' => 'DR-102',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'dp',
            'down_payment_amount' => 70000,
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 10,
            'price' => 10000,
            'subtotal' => 100000
        ]);

        // Return pending 30000 (status menunggu)
        $return = SalesReturn::create([
            'return_number' => 'RET-102',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 3,
            'price_snapshot' => 10000,
            'subtotal_return' => 30000
        ]);

        $report->refresh();
        $this->assertEquals('dp', $report->payment_status);
        $this->assertEquals(30000, $report->effective_remaining_amount);
    }

    /**
     * Test 12: Return ditolak tidak mengubah status
     */
    public function test_return_rejected_does_not_change_status()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-103',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'dp',
            'down_payment_amount' => 70000,
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 10,
            'price' => 10000,
            'subtotal' => 100000
        ]);

        $return = SalesReturn::create([
            'return_number' => 'RET-103',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 3,
            'price_snapshot' => 10000,
            'subtotal_return' => 30000
        ]);

        // Admin reject return
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.reject', $return), [
                'rejection_reason' => 'Ditolak alasan jelas',
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));

        $report->refresh();
        $this->assertEquals('dp', $report->payment_status);
        $this->assertEquals(30000, $report->effective_remaining_amount);
    }

    /**
     * Test 13: Setoran baru tidak boleh melebihi sisa efektif
     */
    public function test_deposit_cannot_exceed_effective_remaining_amount()
    {
        // Delivery total 100000
        $report = DeliveryReport::create([
            'report_number' => 'DR-104',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'dp',
            'down_payment_amount' => 60000, // Setoran disetujui 60000
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 10,
            'price' => 10000,
            'subtotal' => 100000
        ]);

        // Return diterima 30000
        $return = SalesReturn::create([
            'return_number' => 'RET-104',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'diterima',
            'return_condition' => 'perlu_proses_ulang'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 3,
            'price_snapshot' => 10000,
            'subtotal_return' => 30000
        ]);

        // Sisa efektif = 100000 - 30000 - 60000 = 10000
        $this->assertEquals(10000, $report->fresh()->effective_remaining_amount);

        // Sales coba setor 20000 -> Harus ditolak
        $response = $this->actingAs($this->sales1)
            ->post(route('sales.deposits.store'), [
                'delivery_report_id' => $report->id,
                'amount' => 20000,
                'payment_date' => now()->format('Y-m-d'),
                'payment_method' => 'Tunai',
                'note' => 'Coba setor lebih'
            ]);

        $response->assertSessionHas('error');
        $response->assertSessionHas('error', 'Nominal setoran melebihi sisa tagihan setelah memperhitungkan return yang diterima.');
    }

    /**
     * Test 14: Setoran pas sisa efektif
     */
    public function test_deposit_exactly_effective_remaining_amount()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-105',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'dp',
            'down_payment_amount' => 60000,
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 10,
            'price' => 10000,
            'subtotal' => 100000
        ]);

        // Return diterima 30000
        $return = SalesReturn::create([
            'return_number' => 'RET-105',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'diterima',
            'return_condition' => 'perlu_proses_ulang'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 3,
            'price_snapshot' => 10000,
            'subtotal_return' => 30000
        ]);

        // Sisa efektif = 10000
        $this->assertEquals(10000, $report->fresh()->effective_remaining_amount);

        // Sales setor 10000
        $response = $this->actingAs($this->sales1)
            ->post(route('sales.deposits.store'), [
                'delivery_report_id' => $report->id,
                'amount' => 10000,
                'payment_date' => now()->format('Y-m-d'),
                'payment_method' => 'Tunai',
                'note' => 'Setor pas'
            ]);

        $response->assertRedirect(route('sales.deposits.index'));

        // Admin approve setoran tersebut
        $deposit = \App\Models\SalesDeposit::where('delivery_report_id', $report->id)
            ->where('status', 'menunggu_verifikasi')
            ->firstOrFail();

        $responseApprove = $this->actingAs($this->admin)
            ->post(route('admin.sales-deposits.approve', $deposit));

        $responseApprove->assertSessionHas('success');

        $report->refresh();
        $this->assertEquals('lunas', $report->payment_status);
        $this->assertEquals(70000, $report->down_payment_amount); // 60000 + 10000
        $this->assertEquals(0, $report->effective_remaining_amount);
    }

    /**
     * Test 15: Stok tidak berubah akibat sync status
     */
    public function test_sync_payment_status_does_not_affect_stock_data()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-106',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'dp',
            'down_payment_amount' => 60000,
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 10,
            'price' => 10000,
            'subtotal' => 100000
        ]);

        // Simpan snapshot data stok
        $productStockBefore = $this->product->current_stock;
        
        // Cek model SalesStock jika ada
        $salesStockBefore = null;
        if (class_exists(\App\Models\SalesStock::class)) {
            $salesStockBefore = \App\Models\SalesStock::where('sales_id', $this->sales1->id)
                ->where('product_id', $this->product->id)
                ->first();
        }

        $stockMovementsCountBefore = StockMovement::count();
        $drItemsCountBefore = DeliveryReportItem::count();

        // Panggil syncPaymentStatus
        $report->syncPaymentStatus();

        // Assert tidak ada perubahan stok produk gudang
        $this->product->refresh();
        $this->assertEquals($productStockBefore, $this->product->current_stock);

        // Assert tidak ada perubahan stok sales
        if ($salesStockBefore) {
            $salesStockAfter = \App\Models\SalesStock::where('sales_id', $this->sales1->id)
                ->where('product_id', $this->product->id)
                ->first();
            $this->assertEquals($salesStockBefore->qty, $salesStockAfter->qty);
        }

        // Assert tidak ada movement baru
        $this->assertEquals($stockMovementsCountBefore, StockMovement::count());

        // Assert item reports tidak berubah
        $this->assertEquals($drItemsCountBefore, DeliveryReportItem::count());
    }

    /**
     * Test 16: Return Tukar Barang Layak Jual
     * - Assert: Stok gudang bertambah, tagihan tetap utuh, sisa tagihan tetap,
     *   payment_status tidak berubah (tetap), penjualan bersih & HPP tidak terpotong.
     */
    public function test_tukar_barang_layak_jual()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-TUKAR-1',
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

        $return = SalesReturn::create([
            'return_number' => 'RET-TUKAR-1',
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

        // Admin approve return sebagai Tukar Barang & Layak Jual
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'layak_jual',
                'return_type' => 'tukar_barang',
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));
        
        $this->product->refresh();
        $this->assertEquals($stockBefore + 2, $this->product->current_stock); // Stok bertambah

        $report->refresh();
        $this->assertEquals(0, $report->total_return_diterima); // Total return diterima tetap 0
        $this->assertEquals(30000, $report->tagihan_efektif); // Tagihan efektif tetap utuh
        $this->assertEquals(30000, $report->effective_remaining_amount); // Sisa tetap
        $this->assertEquals('belum_bayar', $report->payment_status); // Status pembayaran tidak berubah
    }

    /**
     * Test 17: Return Tukar Barang Perlu Proses Ulang
     * - Assert: Stok gudang tidak bertambah, tagihan & status pembayaran tetap.
     */
    public function test_tukar_barang_proses_ulang()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-TUKAR-2',
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

        $return = SalesReturn::create([
            'return_number' => 'RET-TUKAR-2',
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

        // Admin approve return sebagai Tukar Barang & Perlu Proses Ulang
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'perlu_proses_ulang',
                'return_type' => 'tukar_barang',
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));
        
        $this->product->refresh();
        $this->assertEquals($stockBefore, $this->product->current_stock); // Stok tidak bertambah

        $report->refresh();
        $this->assertEquals(0, $report->total_return_diterima); // Total return diterima tetap 0
        $this->assertEquals(30000, $report->tagihan_efektif); // Tagihan tetap
        $this->assertEquals('belum_bayar', $report->payment_status); // Status tetap
    }

    /**
     * Test 18: Return Potong Tagihan Layak Jual
     * - Assert: Stok gudang bertambah, tagihan berkurang, status pembayaran ter-update.
     */
    public function test_potong_tagihan_layak_jual()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-POTONG-1',
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

        $return = SalesReturn::create([
            'return_number' => 'RET-POTONG-1',
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

        // Admin approve return sebagai Potong Tagihan & Layak Jual
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'layak_jual',
                'return_type' => 'potong_tagihan',
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));
        
        $this->product->refresh();
        $this->assertEquals($stockBefore + 2, $this->product->current_stock); // Stok bertambah

        $report->refresh();
        $this->assertEquals(30000, $report->total_return_diterima); // Return diterima bertambah
        $this->assertEquals(0, $report->tagihan_efektif); // Tagihan berkurang jadi 0
        $this->assertEquals('lunas', $report->payment_status); // Lunas
    }

    /**
     * Test 19: Return Potong Tagihan Perlu Proses Ulang
     * - Assert: Stok gudang tidak bertambah, tagihan berkurang, status pembayaran ter-update.
     */
    public function test_potong_tagihan_proses_ulang()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-POTONG-2',
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

        $return = SalesReturn::create([
            'return_number' => 'RET-POTONG-2',
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

        // Admin approve return sebagai Potong Tagihan & Perlu Proses Ulang
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'perlu_proses_ulang',
                'return_type' => 'potong_tagihan',
            ]);

        $response->assertRedirect(route('admin.returns.show', $return));
        
        $this->product->refresh();
        $this->assertEquals($stockBefore, $this->product->current_stock); // Stok tidak bertambah

        $report->refresh();
        $this->assertEquals(30000, $report->total_return_diterima); // Return diterima bertambah
        $this->assertEquals(0, $report->tagihan_efektif); // Tagihan berkurang jadi 0
        $this->assertEquals('lunas', $report->payment_status); // Lunas
    }

    /**
     * Test 20: Return Tukar Barang tidak membuat Overpayment
     * - Skenario: Delivery 100k, Setoran disetujui 100k, Return tukar_barang 30k diterima.
     * - Assert: payment_status tetap lunas, overpayment_amount tetap 0, tagihan_efektif tetap 100000.
     */
    public function test_tukar_barang_does_not_create_overpayment()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-OVER-TUKAR',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'lunas',
            'down_payment_amount' => 100000,
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 10,
            'price' => 10000,
            'subtotal' => 100000
        ]);

        $return = SalesReturn::create([
            'return_number' => 'RET-OVER-TUKAR',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 3,
            'price_snapshot' => 10000,
            'subtotal_return' => 30000
        ]);

        // Admin approve return sebagai Tukar Barang
        $response = $this->actingAs($this->admin)
            ->post(route('admin.returns.receive', $return), [
                'return_condition' => 'layak_jual',
                'return_type' => 'tukar_barang',
            ]);

        $report->refresh();
        $this->assertEquals('lunas', $report->payment_status);
        $this->assertEquals(0.0, $report->overpayment_amount);
        $this->assertEquals(100000, $report->tagihan_efektif);
        $this->assertFalse($report->is_overpaid);
    }

    /**
     * Test 21: Return Tukar Barang tidak masuk Total Return pengurang Penjualan Bersih
     */
    public function test_tukar_barang_does_not_affect_financial_report()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-FIN-TUKAR',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales1->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 10,
            'price' => 10000,
            'subtotal' => 100000
        ]);

        $return = SalesReturn::create([
            'return_number' => 'RET-FIN-TUKAR',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'diterima',
            'return_type' => 'tukar_barang',
            'return_condition' => 'layak_jual'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 3,
            'price_snapshot' => 10000,
            'subtotal_return' => 30000
        ]);

        // Gunakan logic ReportController query
        $returnsCount = \App\Models\SalesReturnItem::join('sales_returns', 'sales_return_items.sales_return_id', '=', 'sales_returns.id')
            ->where('sales_returns.status', 'diterima')
            ->where('sales_returns.return_type', 'potong_tagihan')
            ->where('sales_returns.delivery_report_id', $report->id)
            ->sum('sales_return_items.subtotal_return');

        $this->assertEquals(0, $returnsCount); // Harus 0 karena bertipe tukar_barang
    }

    /**
     * Test 22: Data Lama Default Potong Tagihan
     */
    public function test_old_returns_default_to_potong_tagihan()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-OLD',
            'sales_id' => $this->sales1->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 30000,
            'payment_status' => 'belum_bayar',
            'created_by' => $this->sales1->id
        ]);

        $return = SalesReturn::create([
            'return_number' => 'RET-OLD',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        $return->refresh();

        $this->assertEquals('potong_tagihan', $return->return_type); // Default database migration
        $this->assertTrue($return->isPotongTagihan());
        $this->assertFalse($return->isTukarBarang());
    }

    /**
     * Test 23: Sales Validation
     */
    public function test_sales_validation_requires_return_type()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-VAL',
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

        // Coba ajukan return tanpa return_type
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

        $response->assertSessionHasErrors('return_type');
    }

    /**
     * Test 24: Return Tukar Barang tidak mengubah Total Return Diterima di detail Delivery Report
     * Karena return_type = tukar_barang tidak boleh tampil sebagai pengurang tagihan di detail delivery report.
     */
    public function test_tukar_barang_does_not_change_total_return_diterima_in_delivery_report_detail()
    {
        $report = DeliveryReport::create([
            'report_number' => 'DR-DET-TUKAR',
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

        $return = SalesReturn::create([
            'return_number' => 'RET-DET-TUKAR',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales1->id,
            'return_date' => now(),
            'status' => 'diterima',
            'return_type' => 'tukar_barang',
            'return_condition' => 'layak_jual'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 2,
            'price_snapshot' => 15000,
            'subtotal_return' => 30000
        ]);

        $this->assertEquals(0.0, $report->total_return_diterima);
        $this->assertEquals(30000.0, $report->tagihan_efektif);
    }
}


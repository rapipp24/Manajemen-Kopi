<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\DeliveryReport;
use App\Models\DeliveryReportItem;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\RawMaterialReceipt;
use App\Models\RawMaterialReceiptItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\SalesDeposit;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Unit;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $sales;
    protected $product;
    protected $unit;
    protected $customer;
    protected $supplier;
    protected $rawMaterial;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup users
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->sales = User::factory()->create(['role' => 'sales']);

        // Setup master data
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

        $this->supplier = Supplier::create([
            'name' => 'Supplier Utama',
            'contact_name' => 'Budi',
            'phone' => '08111111111',
            'address' => 'Jl. Bahan Baku'
        ]);

        $unitBahan = Unit::create(['name' => 'Kg', 'code' => 'KG', 'type' => 'bahan_baku']);
        $this->rawMaterial = RawMaterial::create([
            'code' => 'RAW01',
            'name' => 'Biji Kopi Hijau',
            'unit_id' => $unitBahan->id,
            'minimum_stock' => 5,
            'current_stock' => 20,
            'is_active' => true
        ]);
    }

    /**
     * Test: Laporan aman saat database kosong.
     */
    public function test_report_page_loads_with_empty_data()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports'));

        $response->assertStatus(200);
        $response->assertViewHas('totalCashIn', 0.0);
        $response->assertViewHas('totalCashOut', 0.0);
        $response->assertViewHas('profitTercatat', 0.0);
        $response->assertViewHas('totalSisaPiutang', 0.0);
    }

    /**
     * Test: Uang masuk dari penjualan admin langsung dan setoran disetujui terhitung dengan benar.
     */
    public function test_cash_inflow_calculations()
    {
        // 1. Buat Penjualan Admin Langsung + Pembayaran
        $sale = Sale::create([
            'invoice_number' => 'INV-001',
            'customer_name' => 'Retail Customer',
            'sale_date' => now(),
            'payment_status' => 'lunas',
            'payment_method' => 'cash',
            'total_amount' => 50000,
            'created_by' => $this->admin->id
        ]);

        SalePayment::create([
            'sale_id' => $sale->id,
            'amount' => 50000,
            'payment_date' => now(),
            'payment_method' => 'cash',
            'created_by' => $this->admin->id
        ]);

        // 2. Buat Delivery Report & Setoran Sales Lapangan
        $report = DeliveryReport::create([
            'report_number' => 'DEL-001',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'dp',
            'down_payment_amount' => 40000,
            'created_by' => $this->sales->id
        ]);

        // Setoran disetujui (harus terhitung)
        SalesDeposit::create([
            'deposit_number' => 'DEP-001',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'amount' => 40000,
            'payment_date' => now(),
            'payment_method' => 'transfer',
            'status' => 'disetujui'
        ]);

        // Setoran menunggu verifikasi (tidak boleh terhitung)
        SalesDeposit::create([
            'deposit_number' => 'DEP-002',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'amount' => 30000,
            'payment_date' => now(),
            'payment_method' => 'cash',
            'status' => 'menunggu_verifikasi'
        ]);

        // Setoran ditolak (tidak boleh terhitung)
        SalesDeposit::create([
            'deposit_number' => 'DEP-003',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'amount' => 15000,
            'payment_date' => now(),
            'payment_method' => 'cash',
            'status' => 'ditolak'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports'));

        $response->assertStatus(200);
        // Total Uang Masuk = 50.000 (admin) + 40.000 (setoran disetujui) = 90.000
        $response->assertViewHas('totalAdminPayments', 50000.0);
        $response->assertViewHas('totalSalesDeposits', 40000.0);
        $response->assertViewHas('totalCashIn', 90000.0);
    }

    /**
     * Test: Uang keluar dihitung dari pembelian bahan baku riil.
     */
    public function test_cash_outflow_calculations()
    {
        // Penerimaan bahan baku dengan biaya tercatat
        RawMaterialReceipt::create([
            'receipt_number' => 'RCP-001',
            'supplier_id' => $this->supplier->id,
            'receipt_date' => now(),
            'total_amount' => 150000,
            'created_by' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports'));

        $response->assertStatus(200);
        $response->assertViewHas('totalRawMaterialPurchases', 150000.0);
        $response->assertViewHas('totalCashOut', 150000.0);
    }

    /**
     * Test: Return status diterima memotong tagihan efektif, sedangkan menunggu/ditolak tidak.
     */
    public function test_receivables_and_return_calculations()
    {
        // 1. Delivery Report
        $report = DeliveryReport::create([
            'report_number' => 'DEL-002',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 100000,
            'payment_status' => 'dp',
            'down_payment_amount' => 30000,
            'created_by' => $this->sales->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 5,
            'price' => 20000,
            'subtotal' => 100000
        ]);

        // 2. Return diterima (harus memotong tagihan)
        $returnDiterima = SalesReturn::create([
            'return_number' => 'RET-001',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'diterima'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $returnDiterima->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 1,
            'price_snapshot' => 20000,
            'subtotal_return' => 20000
        ]);

        // 3. Return menunggu (tidak boleh memotong tagihan)
        $returnMenunggu = SalesReturn::create([
            'return_number' => 'RET-002',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'menunggu'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $returnMenunggu->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 1,
            'price_snapshot' => 20000,
            'subtotal_return' => 20000
        ]);

        // 4. Return ditolak (tidak boleh memotong tagihan)
        $returnDitolak = SalesReturn::create([
            'return_number' => 'RET-003',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'ditolak'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $returnDitolak->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 1,
            'price_snapshot' => 20000,
            'subtotal_return' => 20000
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports'));

        $response->assertStatus(200);
        // Tagihan Awal = 100.000
        // Return Diterima = 20.000
        // Tagihan Efektif = 80.000
        // Total Dibayar = 30.000
        // Sisa Piutang = 50.000
        $response->assertViewHas('totalDeliveryAmount', 100000.0);
        $response->assertViewHas('totalReturnDiterima', 20000.0);
        $response->assertViewHas('totalTagihanEfektif', 80000.0);
        $response->assertViewHas('totalPaidToko', 30000.0);
        $response->assertViewHas('totalSisaPiutang', 50000.0);
        $response->assertViewHas('totalKelebihanBayar', 0.0);
    }

    /**
     * Test: Kelebihan bayar terdeteksi saat tagihan efektif < total dibayar.
     */
    public function test_overpayment_calculations()
    {
        // Delivery Report lunas
        $report = DeliveryReport::create([
            'report_number' => 'DEL-003',
            'sales_id' => $this->sales->id,
            'customer_id' => $this->customer->id,
            'delivery_date' => now(),
            'total_amount' => 50000,
            'payment_status' => 'lunas',
            'down_payment_amount' => 50000,
            'created_by' => $this->sales->id
        ]);

        $drItem = DeliveryReportItem::create([
            'delivery_report_id' => $report->id,
            'product_id' => $this->product->id,
            'qty' => 2,
            'price' => 25000,
            'subtotal' => 50000
        ]);

        // Retur setelah lunas (diterima)
        $return = SalesReturn::create([
            'return_number' => 'RET-004',
            'delivery_report_id' => $report->id,
            'sales_id' => $this->sales->id,
            'return_date' => now(),
            'status' => 'diterima'
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $return->id,
            'delivery_report_item_id' => $drItem->id,
            'product_id' => $this->product->id,
            'qty_return' => 1,
            'price_snapshot' => 25000,
            'subtotal_return' => 25000
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports'));

        $response->assertStatus(200);
        // Tagihan Awal = 50.000
        // Return Diterima = 25.000
        // Tagihan Efektif = 25.000
        // Total Dibayar = 50.000
        // Sisa Piutang = -25.000 (dianggap Kelebihan Bayar: 25.000)
        $response->assertViewHas('totalTagihanEfektif', 25000.0);
        $response->assertViewHas('totalPaidToko', 50000.0);
        $response->assertViewHas('totalSisaPiutang', 0.0);
        $response->assertViewHas('totalKelebihanBayar', 25000.0);
    }
}

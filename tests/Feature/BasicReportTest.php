<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\ProductionBatch;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\Package;
use App\Models\SalesOrderItem;
use App\Models\SalesOrderPackageItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class BasicReportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $salesUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Buat User Admin
        $this->admin = User::create([
            'name' => 'Admin Manajemen Kopi',
            'email' => 'admin@kopi.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        // 2. Buat User Sales
        $this->salesUser = User::create([
            'name' => 'Sales Lapangan',
            'email' => 'sales@kopi.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_SALES,
        ]);
    }

    public function test_sales_cannot_access_basic_reports_page(): void
    {
        $response = $this
            ->actingAs($this->salesUser)
            ->get(route('admin.basic-reports.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_basic_reports_with_empty_data(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.index'));

        $response->assertOk();
        $response->assertSee('Laporan Operasional');
        $response->assertSee('Belum ada data pada periode ini.');
    }

    public function test_backend_date_validation_returns_error(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->from(route('admin.basic-reports.index'))
            ->get(route('admin.basic-reports.index', [
                'start_date' => '2026-05-20',
                'end_date' => '2026-05-10', // tanggal awal lebih besar
            ]));

        $response->assertRedirect(route('admin.basic-reports.index'));
        $response->assertSessionHas('error', 'Tanggal Awal tidak boleh lebih besar dari Tanggal Akhir.');
    }

    public function test_admin_can_download_excel_csv(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.export-excel', [
                'type' => 'raw_material',
                'start_date' => '2026-05-01',
                'end_date' => '2026-05-31',
            ]));

        $response->assertOk();
        $response->assertHeader('Content-Disposition', 'attachment; filename="laporan-dasar-raw-material-01-05-2026-sampai-31-05-2026.csv"');
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('LAPORAN PEMBELIAN BAHAN BAKU', $content);
    }

    public function test_admin_can_view_printable_pdf(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.export-pdf', [
                'type' => 'stock',
            ]));

        $response->assertOk();
        $response->assertSee('Laporan Stok Aktual (Real-Time)');
        $response->assertSee('Staf Administrasi');
    }

    public function test_raw_material_stock_status_rules(): void
    {
        // Setup Unit
        $unit = Unit::create(['name' => 'Kilogram', 'code' => 'Kg', 'type' => 'bahan_baku']);

        // 1. Bahan Baku Stok 0 -> HABIS
        $raw1 = RawMaterial::create([
            'code' => 'RM001',
            'name' => 'Kopi Arabika Mentah',
            'unit_id' => $unit->id,
            'minimum_stock' => 10,
            'current_stock' => 0,
            'is_active' => true
        ]);

        // 2. Bahan Baku > 0 dan <= minimum -> HAMPIR HABIS
        $raw2 = RawMaterial::create([
            'code' => 'RM002',
            'name' => 'Gula Pasir',
            'unit_id' => $unit->id,
            'minimum_stock' => 10,
            'current_stock' => 5,
            'is_active' => true
        ]);

        // 3. Bahan Baku > minimum -> AMAN
        $raw3 = RawMaterial::create([
            'code' => 'RM003',
            'name' => 'Susu Cair',
            'unit_id' => $unit->id,
            'minimum_stock' => 10,
            'current_stock' => 20,
            'is_active' => true
        ]);

        // Test HTML
        $responseHtml = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.index', ['type' => 'stock']));

        $responseHtml->assertOk();
        $responseHtml->assertSee('Habis');
        $responseHtml->assertSee('Hampir Habis');
        $responseHtml->assertSee('Aman');

        // Test PDF
        $responsePdf = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.export-pdf', ['type' => 'stock']));

        $responsePdf->assertOk();
        $responsePdf->assertSee('HABIS');
        $responsePdf->assertSee('HAMPIR HABIS');
        $responsePdf->assertSee('AMAN');

        // Test CSV/Excel
        $responseCsv = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.export-excel', ['type' => 'stock']));

        $responseCsv->assertOk();
        $csvContent = $responseCsv->streamedContent();
        $this->assertStringContainsString('HABIS', $csvContent);
        $this->assertStringContainsString('HAMPIR HABIS', $csvContent);
        $this->assertStringContainsString('AMAN', $csvContent);
    }

    public function test_sales_order_report_includes_packages(): void
    {
        // Setup Unit, Product, Customer
        $unit = Unit::create(['name' => 'Pcs', 'code' => 'PCS', 'type' => 'produk']);
        $product = Product::create([
            'code' => 'PRD01',
            'name' => 'Kopi Robusta',
            'variant' => 'Original',
            'weight' => 250,
            'unit_id' => $unit->id,
            'cost_price' => 10000,
            'price' => 15000,
            'is_active' => true
        ]);
        $customer = Customer::create([
            'name' => 'Toko Harapan',
            'address' => 'Jl. Harapan',
            'phone' => '0812345678'
        ]);

        // Setup Package
        $package = Package::create([
            'code' => 'PKT-001',
            'name' => 'Kopi Pack Spesial',
            'selling_price' => 50000,
            'is_active' => true,
        ]);

        // Setup SalesOrder
        $order = SalesOrder::create([
            'order_number' => 'SO-0001',
            'sales_id' => $this->salesUser->id,
            'customer_id' => $customer->id,
            'status' => 'menunggu',
            'catatan' => 'Test',
            'total' => 65000,
        ]);

        // Add Product Item
        SalesOrderItem::create([
            'sales_order_id' => $order->id,
            'product_id' => $product->id,
            'qty' => 1,
            'harga' => 15000,
            'subtotal' => 15000,
        ]);

        // Add Package Item
        SalesOrderPackageItem::create([
            'sales_order_id' => $order->id,
            'package_id' => $package->id,
            'qty' => 1,
            'harga' => 50000,
            'subtotal' => 50000,
        ]);

        // Test HTML view
        $responseHtml = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.index', ['type' => 'order']));
        
        $responseHtml->assertOk();
        $responseHtml->assertSee('Kopi Robusta');
        $responseHtml->assertSee('[PAKET] Kopi Pack Spesial');

        // Test PDF/print
        $responsePdf = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.export-pdf', ['type' => 'order']));

        $responsePdf->assertOk();
        $responsePdf->assertSee('Kopi Robusta');
        $responsePdf->assertSee('[PAKET] Kopi Pack Spesial');

        // Test CSV/export
        $responseCsv = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.export-excel', [
                'type' => 'order',
                'start_date' => now()->startOfDay()->format('Y-m-d'),
                'end_date' => now()->endOfDay()->format('Y-m-d'),
            ]));

        $responseCsv->assertOk();
        $csvContent = $responseCsv->streamedContent();
        $this->assertStringContainsString('Kopi Robusta: 1 pcs', $csvContent);
        $this->assertStringContainsString('[PAKET] Kopi Pack Spesial: 1 pack', $csvContent);
    }

    public function test_basic_reports_refined_to_operational_reports_with_totals(): void
    {
        // Setup Unit and Product
        $unit = Unit::create(['name' => 'Pcs', 'code' => 'PCS', 'type' => 'produk']);
        $product = Product::create([
            'code' => 'PRD01',
            'name' => 'Kopi Robusta',
            'variant' => 'Original',
            'weight' => 250,
            'unit_id' => $unit->id,
            'cost_price' => 10000,
            'price' => 15000,
            'is_active' => true
        ]);
        $customer = Customer::create([
            'name' => 'Toko Harapan',
            'address' => 'Jl. Harapan',
            'phone' => '0812345678'
        ]);

        $order = SalesOrder::create([
            'order_number' => 'SO-0001',
            'sales_id' => $this->salesUser->id,
            'customer_id' => $customer->id,
            'status' => 'diproses',
            'catatan' => 'Test',
            'total' => 15000,
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $order->id,
            'product_id' => $product->id,
            'qty' => 1,
            'harga' => 15000,
            'subtotal' => 15000,
        ]);

        // Test HTML view displays Laporan Operasional and not Laporan Dasar, and does not show Absensi Bulanan
        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.index', ['type' => 'order']));
        
        $response->assertOk();
        $response->assertSee('Laporan Operasional');
        $response->assertDontSee('Absensi Bulanan');
        $response->assertSee('TOTAL NILAI PENGAJUAN');
        $response->assertSee('Rp 15.000');
        $response->assertSee('Disetujui'); // Since status is diproses

        // Test CSV displays total row
        $responseCsv = $this
            ->actingAs($this->admin)
            ->get(route('admin.basic-reports.export-excel', [
                'type' => 'order',
                'start_date' => now()->startOfDay()->format('Y-m-d'),
                'end_date' => now()->endOfDay()->format('Y-m-d'),
            ]));

        $responseCsv->assertOk();
        $csvContent = $responseCsv->streamedContent();
        $this->assertStringContainsString('"TOTAL NILAI PENGAJUAN";;;;15000', $csvContent);
    }
}

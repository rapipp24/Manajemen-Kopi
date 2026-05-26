<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\ProductionBatch;
use App\Models\SalePayment;
use App\Models\SalesDeposit;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Sale;
use App\Models\DeliveryReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AdminDashboardTest extends TestCase
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

    public function test_admin_can_access_dashboard_with_empty_data(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Ringkasan Hari Ini');
        $response->assertSee('Rp 0');
        $response->assertSee('0 batch');
    }

    public function test_sales_cannot_access_admin_dashboard(): void
    {
        $response = $this
            ->actingAs($this->salesUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_dashboard_displays_correct_count_and_sum(): void
    {
        // 1. Buat Unit & Satuan
        $unit = Unit::create([
            'name' => 'Gram',
            'code' => 'gr',
        ]);

        // 2. Buat Bahan Baku (Stok Kritis dan Stok Aman) via DB::table bypass fillable & constraint
        DB::table('raw_materials')->insert([
            [
                'code' => 'RM001',
                'name' => 'Kopi Arabika',
                'unit_id' => $unit->id,
                'current_stock' => 50,
                'minimum_stock' => 100, // Kritis
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'RM002',
                'name' => 'Kopi Robusta',
                'unit_id' => $unit->id,
                'current_stock' => 200,
                'minimum_stock' => 100, // Aman
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // 3. Buat Barang Jadi (Stok Global) via DB::table bypass fillable & constraint
        DB::table('products')->insert([
            [
                'code' => 'P001',
                'name' => 'Robusta Powder 250g',
                'unit_id' => $unit->id,
                'current_stock' => 125,
                'price' => 25000,
                'cost_price' => 18000,
                'weight' => 250,
                'category' => 'Powder',
                'variant' => 'Regular',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'P002',
                'name' => 'Arabika Powder 250g',
                'unit_id' => $unit->id,
                'current_stock' => 75,
                'price' => 30000,
                'cost_price' => 22000,
                'weight' => 250,
                'category' => 'Powder',
                'variant' => 'Regular',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // 4. Buat Customer & SalesOrder
        $customer = Customer::create([
            'name' => 'Toko Sedap',
            'phone' => '0812345',
            'address' => 'Bandung',
        ]);

        SalesOrder::create([
            'order_number' => 'SO-001',
            'sales_id' => $this->salesUser->id,
            'customer_id' => $customer->id,
            'status' => 'menunggu',
            'total' => 250000,
            'created_at' => Carbon::now(),
        ]);

        // 5. Buat ProductionBatch hari ini
        ProductionBatch::create([
            'batch_number' => 'PB-001',
            'production_date' => Carbon::today()->format('Y-m-d'),
            'product_type' => 'Powder',
            'total_material_used' => 1000,
            'total_output' => 40,
            'shrinkage' => 0,
            'created_by' => $this->admin->id,
        ]);

        // 6. Buat Kas Masuk (Direct SalePayment + SalesDeposit)
        $sale = Sale::create([
            'invoice_number' => 'SL-001',
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'sale_date' => Carbon::today()->format('Y-m-d'),
            'payment_status' => 'lunas',
            'payment_method' => 'cash',
            'total_amount' => 150000,
            'created_by' => $this->admin->id,
        ]);

        // SalePayment Hari ini
        SalePayment::create([
            'sale_id' => $sale->id,
            'amount' => 100000,
            'payment_date' => Carbon::today()->format('Y-m-d'),
            'payment_method' => 'cash',
            'created_by' => $this->admin->id,
        ]);

        // Delivery Report untuk Sales Deposit
        $deliveryReport = DeliveryReport::create([
            'report_number' => 'DR-001',
            'sales_id' => $this->salesUser->id,
            'customer_id' => $customer->id,
            'delivery_date' => Carbon::today()->format('Y-m-d'),
            'total_amount' => 150000,
            'payment_status' => 'belum_lunas',
            'status' => 'dikirim',
            'created_by' => $this->salesUser->id,
        ]);

        // SalesDeposit Disetujui Hari Ini
        SalesDeposit::create([
            'deposit_number' => 'DEP-001',
            'delivery_report_id' => $deliveryReport->id,
            'sales_id' => $this->salesUser->id,
            'amount' => 50000,
            'payment_date' => Carbon::today()->format('Y-m-d'),
            'payment_method' => 'transfer',
            'status' => 'disetujui',
            'verified_by' => $this->admin->id,
            'verified_at' => Carbon::now(),
        ]);

        // SalesDeposit Pending Hari Ini (Harus diabaikan)
        SalesDeposit::create([
            'deposit_number' => 'DEP-002',
            'delivery_report_id' => $deliveryReport->id,
            'sales_id' => $this->salesUser->id,
            'amount' => 999999, // Besar tapi pending
            'payment_date' => Carbon::today()->format('Y-m-d'),
            'payment_method' => 'transfer',
            'status' => 'pending',
        ]);

        // Kirim request ke dashboard dengan filter hari ini / bulan ini
        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.dashboard', [
                'start_date' => Carbon::today()->format('Y-m-d'),
                'end_date' => Carbon::today()->format('Y-m-d'),
            ]));

        $response->assertOk();

        // Verifikasi widgets dan data pendukung
        $response->assertSee('Kopi Arabika'); // Bahan kritis muncul di peringatan
        $response->assertSee('Bahan Kritis');
        $response->assertSee('Total Volume Fisik');
        $response->assertSee('200');
        $response->assertSee('Pcs');
        $response->assertSee('Rp 150.000'); // Uang masuk disetujui: 100.000 (payment) + 50.000 (deposit disetujui)
        $response->assertDontSee('Rp 999.999'); // Uang masuk pending diabaikan
        $response->assertSee('40'); // Output produksi value
        $response->assertSee('pcs'); // Output produksi unit
        $response->assertSee('1 batch selesai hari ini'); // Batch produksi
    }

    public function test_dashboard_is_strictly_read_only(): void
    {
        // Membuka dashboard tidak boleh merubah data apa pun di DB
        $unit = Unit::create([
            'name' => 'Gram',
            'code' => 'gr',
        ]);

        DB::table('products')->insert([
            'code' => 'P999',
            'name' => 'Kopi Powder',
            'unit_id' => $unit->id,
            'current_stock' => 10,
            'price' => 20000,
            'cost_price' => 15000,
            'weight' => 250,
            'category' => 'Powder',
            'variant' => 'Regular',
            'is_active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->assertEquals(10, DB::table('products')->where('code', 'P999')->value('current_stock'));

        $this->actingAs($this->admin)->get(route('admin.dashboard'));

        // Pastikan stok tetap sama
        $this->assertEquals(10, DB::table('products')->where('code', 'P999')->value('current_stock'));
    }
}

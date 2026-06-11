<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\DeliveryReport;
use App\Models\DeliveryReportItem;
use App\Models\DeliveryReportPackageItem;
use App\Models\SalesStock;
use App\Models\SalesPackageStock;
use App\Models\SalesDeposit;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCashValidationTest extends TestCase
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
     * Test 1: Cash membuat pending deposit.
     */
    public function test_cash_creates_pending_deposit()
    {
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
                'cash_amount' => 100000, // 2 * 50000 = 100000
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
        $this->assertEquals(100000.00, $report->total_amount);
        $this->assertEquals('belum_bayar', $report->payment_status);
        $this->assertEquals(0, $report->down_payment_amount);

        // Check auto deposit created
        $deposit = SalesDeposit::first();
        $this->assertNotNull($deposit);
        $this->assertEquals($report->id, $deposit->delivery_report_id);
        $this->assertEquals(100000.00, $deposit->amount);
        $this->assertEquals('Tunai', $deposit->payment_method);
        $this->assertEquals('menunggu_verifikasi', $deposit->status);
    }

    /**
     * Test 2: Cash nominal harus sama dengan total.
     */
    public function test_cash_nominal_must_equal_total()
    {
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        // Post delivery report with wrong cash_amount
        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'cash_amount' => 95000, // Total is 100000
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 2
                    ]
                ]
            ]);

        // Should return with error in session
        $response->assertSessionHas('error');
        $this->assertEquals(0, DeliveryReport::count());
        $this->assertEquals(0, SalesDeposit::count());
    }

    /**
     * Test 3: Tempo tidak membuat deposit otomatis.
     */
    public function test_tempo_does_not_create_auto_deposit()
    {
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        // Post delivery report with tempo payment_term_days
        $response = $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'payment_term_days' => 15,
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
        $this->assertEquals(100000.00, $report->total_amount);
        $this->assertEquals('belum_bayar', $report->payment_status);
        $this->assertEquals(15, $report->payment_term_days);

        // Check NO deposit created
        $this->assertEquals(0, SalesDeposit::count());
    }

    /**
     * Test 4: Approve deposit membuat laporan lunas.
     */
    public function test_approve_deposit_makes_report_lunas()
    {
        SalesPackageStock::create([
            'user_id' => $this->sales->id,
            'package_id' => $this->package->id,
            'qty' => 5
        ]);

        // Post delivery report
        $this->actingAs($this->sales)
            ->post(route('sales.delivery-reports.store'), [
                'customer_id' => $this->customer->id,
                'delivery_date' => date('Y-m-d'),
                'cash_amount' => 100000,
                'package_items' => [
                    [
                        'package_id' => $this->package->id,
                        'qty' => 2
                    ]
                ]
            ]);

        $report = DeliveryReport::first();
        $deposit = SalesDeposit::first();

        // Admin approves deposit
        $response = $this->actingAs($this->admin)
            ->post(route('admin.sales-deposits.approve', $deposit));

        $response->assertRedirect();
        
        $report->refresh();
        $deposit->refresh();

        $this->assertEquals('disetujui', $deposit->status);
        $this->assertEquals('lunas', $report->payment_status);
        $this->assertEquals(100000.00, $report->down_payment_amount);
    }
}

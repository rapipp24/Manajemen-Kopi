<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\PackageItem;
use App\Models\PackageStock;
use App\Models\PackageAssembly;
use App\Models\PackageAssemblyItem;
use App\Models\PackageStockMovement;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\SalesStock;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageAssemblyTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sales;
    private Product $productA;
    private Product $productB;
    private Package $activePackage;
    private Package $inactivePackage;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Users
        $this->admin = User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@kopi.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        $this->sales = User::create([
            'name' => 'Sales Lapangan',
            'email' => 'sales@kopi.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_SALES,
        ]);

        // Setup Master Data
        $unit = Unit::create(['name' => 'Pcs', 'code' => 'PCS', 'type' => 'produk']);

        $this->productA = Product::create([
            'code' => 'PRD01',
            'name' => 'Kopi Robusta 250g',
            'variant' => 'Original',
            'weight' => 250,
            'unit_id' => $unit->id,
            'cost_price' => 10000, // HPP A
            'price' => 25000,
            'is_active' => true
        ]);
        $this->productA->current_stock = 100;
        $this->productA->save();

        $this->productB = Product::create([
            'code' => 'PRD02',
            'name' => 'Kopi Arabika 100g',
            'variant' => 'Original',
            'weight' => 100,
            'unit_id' => $unit->id,
            'cost_price' => 4000, // HPP B
            'price' => 12000,
            'is_active' => true
        ]);
        $this->productB->current_stock = 100;
        $this->productB->save();

        // Create Active Package: Kopi Mix (2x ProductA + 5x ProductB)
        // HPP per package = 2 * 10000 + 5 * 4000 = 20000 + 20000 = 40000
        $this->activePackage = Package::create([
            'code' => 'PKT-0001',
            'name' => 'Kopi Elang Emas 1kg Isi 7',
            'selling_price' => 95000,
            'is_active' => true,
            'description' => 'Paket kopi mix premium'
        ]);

        PackageItem::create([
            'package_id' => $this->activePackage->id,
            'product_id' => $this->productA->id,
            'qty' => 2,
        ]);

        PackageItem::create([
            'package_id' => $this->activePackage->id,
            'product_id' => $this->productB->id,
            'qty' => 5,
        ]);

        // Create Inactive Package
        $this->inactivePackage = Package::create([
            'code' => 'PKT-0002',
            'name' => 'Paket Non-Aktif',
            'selling_price' => 50000,
            'is_active' => false,
            'description' => 'Paket mati'
        ]);

        PackageItem::create([
            'package_id' => $this->inactivePackage->id,
            'product_id' => $this->productA->id,
            'qty' => 1,
        ]);
    }

    /**
     * Test: Admin can visit assembly index and create page.
     */
    public function test_admin_can_visit_assembly_index_and_create_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.package-assemblies.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(route('admin.package-assemblies.create'));
        $response->assertStatus(200);
    }

    /**
     * Test: Sales cannot access assembly pages.
     */
    public function test_sales_cannot_access_assembly_pages()
    {
        $response = $this->actingAs($this->sales)->get(route('admin.package-assemblies.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->sales)->post(route('admin.package-assemblies.store'), []);
        $response->assertStatus(403);
    }

    /**
     * Test: Admin can create assembly with sufficient stock.
     */
    public function test_admin_can_create_package_assembly_with_sufficient_stock()
    {
        // Target perakitan: 10 pack
        // Butuh ProductA: 2 * 10 = 20 pcs
        // Butuh ProductB: 5 * 10 = 50 pcs
        // HPP per pack: 2 * 10000 + 5 * 4000 = 40000
        $payload = [
            'package_id' => $this->activePackage->id,
            'qty' => 10,
            'note' => 'Perakitan batch pertama',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.package-assemblies.store'), $payload);

        $response->assertSessionHasNoErrors();

        // Memastikan redirect ke halaman show detail assembly
        $assembly = PackageAssembly::first();
        $this->assertNotNull($assembly);
        $response->assertRedirect(route('admin.package-assemblies.show', $assembly->id));

        // 1. Cek stok produk penyusun di gudang berkurang
        $this->productA->refresh();
        $this->productB->refresh();
        $this->assertEquals(80.00, $this->productA->current_stock); // 100 - 20
        $this->assertEquals(50.00, $this->productB->current_stock); // 100 - 50

        // 2. Cek stok paket gudang bertambah
        $packageStock = PackageStock::where('package_id', $this->activePackage->id)->first();
        $this->assertNotNull($packageStock);
        $this->assertEquals(10.00, $packageStock->qty);

        // 3. Cek riwayat perakitan
        $this->assertDatabaseHas('package_assemblies', [
            'id' => $assembly->id,
            'package_id' => $this->activePackage->id,
            'qty' => 10.00,
            'hpp_per_package_snapshot' => 40000.00,
            'note' => 'Perakitan batch pertama',
            'created_by' => $this->admin->id,
        ]);

        // 4. Cek detail item perakitan (cost_price_snapshot)
        $this->assertDatabaseHas('package_assembly_items', [
            'package_assembly_id' => $assembly->id,
            'product_id' => $this->productA->id,
            'qty_per_package' => 2.00,
            'total_qty_used' => 20.00,
            'cost_price_snapshot' => 10000.00,
        ]);
        $this->assertDatabaseHas('package_assembly_items', [
            'package_assembly_id' => $assembly->id,
            'product_id' => $this->productB->id,
            'qty_per_package' => 5.00,
            'total_qty_used' => 50.00,
            'cost_price_snapshot' => 4000.00,
        ]);

        // 5. Cek StockMovement produk penyusun keluar dari gudang
        $this->assertDatabaseHas('stock_movements', [
            'item_type' => 'product',
            'item_id' => $this->productA->id,
            'movement_type' => 'out',
            'reference_type' => PackageAssembly::class,
            'reference_id' => $assembly->id,
            'qty' => 20.00,
            'user_id' => null,
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'item_type' => 'product',
            'item_id' => $this->productB->id,
            'movement_type' => 'out',
            'reference_type' => PackageAssembly::class,
            'reference_id' => $assembly->id,
            'qty' => 50.00,
            'user_id' => null,
        ]);

        // 6. Cek PackageStockMovement paket masuk gudang
        $this->assertDatabaseHas('package_stock_movements', [
            'package_id' => $this->activePackage->id,
            'user_id' => null,
            'movement_type' => 'in',
            'qty' => 10.00,
            'stock_before' => 0.00,
            'stock_after' => 10.00,
            'reference_type' => PackageAssembly::class,
            'reference_id' => $assembly->id,
        ]);
    }

    /**
     * Test: Assembly fails and rolls back if stock of any component is insufficient.
     */
    public function test_assembly_fails_and_rolls_back_if_stock_is_insufficient()
    {
        // Target: 60 pack
        // Butuh ProductA: 2 * 60 = 120 pcs (Stok cuma 100, GAGAL!)
        // Butuh ProductB: 5 * 60 = 300 pcs (Stok cuma 100, GAGAL!)
        $payload = [
            'package_id' => $this->activePackage->id,
            'qty' => 60,
            'note' => 'Mencoba merakit melebihi stok',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.package-assemblies.store'), $payload);

        $response->assertSessionHas('error');
        
        // Memastikan tidak ada data transaksi yang disimpan (rollback)
        $this->assertEquals(0, PackageAssembly::count());
        $this->assertEquals(0, PackageAssemblyItem::count());

        // Memastikan stok produk tidak berubah
        $this->productA->refresh();
        $this->productB->refresh();
        $this->assertEquals(100.00, $this->productA->current_stock);
        $this->assertEquals(100.00, $this->productB->current_stock);

        // Memastikan stok paket tetap kosong
        $packageStock = PackageStock::where('package_id', $this->activePackage->id)->first();
        $this->assertTrue(!$packageStock || $packageStock->qty == 0);

        // Memastikan tidak ada movements yang dicatat
        $this->assertEquals(0, StockMovement::count());
        $this->assertEquals(0, PackageStockMovement::count());
    }

    /**
     * Test: Cannot create assembly for inactive package.
     */
    public function test_cannot_create_assembly_for_inactive_package()
    {
        $payload = [
            'package_id' => $this->inactivePackage->id,
            'qty' => 5,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.package-assemblies.store'), $payload);

        $response->assertSessionHas('error');
        $this->assertEquals(0, PackageAssembly::count());
    }

    /**
     * Test: Cannot create assembly for soft-deleted package.
     */
    public function test_cannot_create_assembly_for_deleted_package()
    {
        $this->activePackage->delete(); // Soft delete

        $payload = [
            'package_id' => $this->activePackage->id,
            'qty' => 5,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.package-assemblies.store'), $payload);

        // Karena soft deleted, exists validation rule 'exists:packages,id' akan menolaknya
        $response->assertSessionHasErrors(['package_id']);
        $this->assertEquals(0, PackageAssembly::count());
    }

    /**
     * Test: Validation rules (invalid qty).
     */
    public function test_validation_rules_invalid_qty()
    {
        $payload = [
            'package_id' => $this->activePackage->id,
            'qty' => -5, // Qty negatif, tidak boleh!
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.package-assemblies.store'), $payload);

        $response->assertSessionHasErrors(['qty']);
        $this->assertEquals(0, PackageAssembly::count());
    }

    /**
     * Test: No side effects on sales stocks and other modules.
     */
    public function test_no_side_effects_on_sales_stocks_and_other_modules()
    {
        // Buat stok sales awal
        SalesStock::create([
            'user_id' => $this->sales->id,
            'product_id' => $this->productA->id,
            'qty' => 5.00,
        ]);

        $payload = [
            'package_id' => $this->activePackage->id,
            'qty' => 5,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.package-assemblies.store'), $payload);

        $response->assertSessionHasNoErrors();

        // Pastikan sales_stocks tidak berubah sama sekali
        $salesStock = SalesStock::where('user_id', $this->sales->id)->where('product_id', $this->productA->id)->first();
        $this->assertEquals(5.00, $salesStock->qty);
    }

    /**
     * Test: Cannot create assembly for package with no components.
     */
    public function test_cannot_create_assembly_for_package_with_no_components()
    {
        $packageWithNoComponents = Package::create([
            'code' => 'PKT-0003',
            'name' => 'Paket Kosong',
            'selling_price' => 30000,
            'is_active' => true,
        ]);

        $payload = [
            'package_id' => $packageWithNoComponents->id,
            'qty' => 5,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.package-assemblies.store'), $payload);

        $response->assertSessionHas('error');
        $this->assertEquals(0, PackageAssembly::count());
    }
}

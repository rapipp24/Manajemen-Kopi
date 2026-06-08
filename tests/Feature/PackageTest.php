<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\PackageItem;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sales;
    private Product $productA;
    private Product $productB;

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
            'name' => 'Kopi Robusta',
            'variant' => 'Original',
            'weight' => 250,
            'unit_id' => $unit->id,
            'cost_price' => 10000,
            'price' => 25000,
            'current_stock' => 100, // Stok awal gudang
            'is_active' => true
        ]);

        $this->productB = Product::create([
            'code' => 'PRD02',
            'name' => 'Kopi Arabika',
            'variant' => 'Original',
            'weight' => 250,
            'unit_id' => $unit->id,
            'cost_price' => 12000,
            'price' => 30000,
            'current_stock' => 50, // Stok awal gudang
            'is_active' => true
        ]);
    }

    /**
     * Test: Admin bisa membuat paket dengan minimal 1 komponen produk.
     */
    public function test_admin_can_create_package_with_at_least_one_component()
    {
        $payload = [
            'code' => 'PKT01',
            'name' => 'Paket Hemat',
            'selling_price' => 90000,
            'is_active' => 1,
            'description' => 'Diskon khusus',
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'qty' => 4,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), $payload);

        $response->assertRedirect(route('admin.packages.index'));
        $response->assertSessionHas('success', 'Paket berhasil ditambahkan!');

        $this->assertDatabaseHas('packages', [
            'code' => 'PKT01',
            'name' => 'Paket Hemat',
            'selling_price' => 90000,
            'is_active' => true,
        ]);

        $package = Package::where('code', 'PKT01')->first();
        $this->assertCount(1, $package->items);
        $this->assertEquals($this->productA->id, $package->items->first()->product_id);
        $this->assertEquals(4, $package->items->first()->qty);
    }

    /**
     * Test: Sistem menolak paket tanpa komponen.
     */
    public function test_system_rejects_package_without_components()
    {
        $payload = [
            'code' => 'PKT01',
            'name' => 'Paket Tanpa Isi',
            'selling_price' => 50000,
            'items' => [] // Tanpa item
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), $payload);

        $response->assertSessionHasErrors('items');
        $this->assertDatabaseMissing('packages', ['code' => 'PKT01']);
    }

    /**
     * Test: Sistem menolak komponen dengan qty <= 0.
     */
    public function test_system_rejects_component_with_invalid_qty()
    {
        $payload = [
            'code' => 'PKT01',
            'name' => 'Paket Invalid Qty',
            'selling_price' => 50000,
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'qty' => 0, // qty tidak boleh 0
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), $payload);

        $response->assertSessionHasErrors('items.0.qty');
        $this->assertDatabaseMissing('packages', ['code' => 'PKT01']);
    }

    /**
     * Test: Sistem menolak produk duplikat dalam paket yang sama.
     */
    public function test_system_rejects_duplicate_product_components()
    {
        $payload = [
            'code' => 'PKT01',
            'name' => 'Paket Duplikat',
            'selling_price' => 80000,
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'qty' => 2,
                ],
                [
                    'product_id' => $this->productA->id, // Duplikat productA
                    'qty' => 2,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), $payload);

        $response->assertSessionHasErrors('items');
        $this->assertDatabaseMissing('packages', ['code' => 'PKT01']);
    }

    /**
     * Test: Sistem menolak code paket yang duplikat.
     */
    public function test_system_rejects_duplicate_package_code()
    {
        // Buat paket pertama
        Package::create([
            'code' => 'PKT_DUP',
            'name' => 'Paket 1',
            'selling_price' => 50000,
            'is_active' => true,
        ]);

        $payload = [
            'code' => 'PKT_DUP', // Duplikat code
            'name' => 'Paket 2',
            'selling_price' => 70000,
            'items' => [
                [
                    'product_id' => $this->productB->id,
                    'qty' => 2,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), $payload);

        $response->assertSessionHasErrors('code');
    }

    /**
     * Test: Admin bisa edit paket dan komponennya.
     */
    public function test_admin_can_edit_package_and_its_components()
    {
        $package = Package::create([
            'code' => 'PKT_EDIT',
            'name' => 'Paket Awal',
            'selling_price' => 50000,
            'is_active' => true,
        ]);

        PackageItem::create([
            'package_id' => $package->id,
            'product_id' => $this->productA->id,
            'qty' => 2,
        ]);

        $payload = [
            'code' => 'PKT_EDITED',
            'name' => 'Paket Baru',
            'selling_price' => 75000,
            'is_active' => 1,
            'items' => [
                [
                    'product_id' => $this->productB->id, // Ganti produk
                    'qty' => 3, // Ganti qty
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.packages.update', $package->id), $payload);

        $response->assertRedirect(route('admin.packages.index'));

        $package->refresh();
        $this->assertEquals('PKT_EDITED', $package->code);
        $this->assertEquals('Paket Baru', $package->name);
        $this->assertEquals(75000.0, $package->selling_price);

        $this->assertCount(1, $package->items);
        $this->assertEquals($this->productB->id, $package->items->first()->product_id);
        $this->assertEquals(3, $package->items->first()->qty);
    }

    /**
     * Test: Admin bisa aktif/nonaktifkan paket.
     */
    public function test_admin_can_toggle_package_is_active_status()
    {
        $package = Package::create([
            'code' => 'PKT_STATUS',
            'name' => 'Paket Status',
            'selling_price' => 50000,
            'is_active' => true,
        ]);

        PackageItem::create([
            'package_id' => $package->id,
            'product_id' => $this->productA->id,
            'qty' => 1,
        ]);

        // Ganti status menjadi non-aktif (tanpa input is_active di form)
        $payload = [
            'code' => 'PKT_STATUS',
            'name' => 'Paket Status',
            'selling_price' => 50000,
            // is_active dikosongkan (tidak tercentang)
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'qty' => 1,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.packages.update', $package->id), $payload);

        $package->refresh();
        $this->assertFalse($package->is_active);
    }

    /**
     * Test: Package soft delete.
     */
    public function test_package_supports_soft_deletes()
    {
        $package = Package::create([
            'code' => 'PKT_DEL',
            'name' => 'Paket Dihapus',
            'selling_price' => 50000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.packages.destroy', $package->id));

        $response->assertRedirect(route('admin.packages.index'));
        
        $this->assertSoftDeleted('packages', [
            'id' => $package->id,
        ]);
    }

    /**
     * Test: Tidak ada perubahan stok saat create/edit/delete paket.
     */
    public function test_no_stock_changes_occur_on_package_crud()
    {
        $stockBeforeA = $this->productA->current_stock; // 100
        $stockBeforeB = $this->productB->current_stock; // 50

        // 1. Create
        $package = Package::create([
            'code' => 'PKT_STK',
            'name' => 'Paket Uji Stok',
            'selling_price' => 50000,
            'is_active' => true,
        ]);

        PackageItem::create([
            'package_id' => $package->id,
            'product_id' => $this->productA->id,
            'qty' => 5,
        ]);

        // 2. Edit
        $package->update([
            'name' => 'Paket Uji Stok Diedit'
        ]);

        // 3. Delete
        $package->delete();

        // Cek stok master produk tidak boleh berubah sama sekali
        $this->productA->refresh();
        $this->productB->refresh();

        $this->assertEquals($stockBeforeA, $this->productA->current_stock);
        $this->assertEquals($stockBeforeB, $this->productB->current_stock);
    }

    /**
     * Test: Tidak ada perubahan laporan keuangan.
     */
    public function test_no_financial_report_changes_occur_on_package_crud()
    {
        // Ambil data laporan keuangan sebelum paket ditambahkan
        $responseBefore = $this->actingAs($this->admin)->get(route('admin.reports'));
        $responseBefore->assertStatus(200);

        $cashInBefore = $responseBefore->viewData('totalCashIn');
        $cashOutBefore = $responseBefore->viewData('totalCashOut');
        $labaMarginBefore = $responseBefore->viewData('labaMargin');

        // Buat paket baru
        $package = Package::create([
            'code' => 'PKT_REP',
            'name' => 'Paket Uji Laporan',
            'selling_price' => 95000,
            'is_active' => true,
        ]);

        PackageItem::create([
            'package_id' => $package->id,
            'product_id' => $this->productA->id,
            'qty' => 4,
        ]);

        // Ambil data laporan keuangan setelah paket ditambahkan
        $responseAfter = $this->actingAs($this->admin)->get(route('admin.reports'));
        $responseAfter->assertStatus(200);

        // Nilai-nilai laporan keuangan tidak boleh bergeser
        $this->assertEquals($cashInBefore, $responseAfter->viewData('totalCashIn'));
        $this->assertEquals($cashOutBefore, $responseAfter->viewData('totalCashOut'));
        $this->assertEquals($labaMarginBefore, $responseAfter->viewData('labaMargin'));
    }

    /**
     * Test: create package tanpa code manual tetap menghasilkan kode otomatis.
     */
    public function test_create_package_without_manual_code_generates_automatically()
    {
        $payload = [
            'code' => '', // kosongkan kode
            'name' => 'Paket Otomatis 1',
            'selling_price' => 95000,
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'qty' => 1,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), $payload);

        $response->assertRedirect(route('admin.packages.index'));

        // Harapannya digenerate otomatis menjadi PKT-0001
        $this->assertDatabaseHas('packages', [
            'code' => 'PKT-0001',
            'name' => 'Paket Otomatis 1',
            'selling_price' => 95000,
        ]);
    }

    /**
     * Test: kode otomatis unique.
     */
    public function test_automatic_code_generation_is_unique()
    {
        // Buat paket PKT-0001 secara manual
        Package::create([
            'code' => 'PKT-0001',
            'name' => 'Paket Manual',
            'selling_price' => 50000,
            'is_active' => true,
        ]);

        $payload = [
            'code' => '', // kosongkan kode
            'name' => 'Paket Otomatis 2',
            'selling_price' => 95000,
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'qty' => 1,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), $payload);

        $response->assertRedirect(route('admin.packages.index'));

        // Harus digenerate otomatis menjadi PKT-0002 karena PKT-0001 sudah ada
        $this->assertDatabaseHas('packages', [
            'code' => 'PKT-0002',
            'name' => 'Paket Otomatis 2',
            'selling_price' => 95000,
        ]);
    }

    /**
     * Test: selling_price dengan format "95.000" tersimpan sebagai 95000.
     */
    public function test_selling_price_with_thousand_separator_saves_correctly()
    {
        $payload = [
            'code' => 'PKT-TEST-1',
            'name' => 'Paket Format Ribuan',
            'selling_price' => '95.000', // format ribuan
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'qty' => 1,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), $payload);

        $response->assertRedirect(route('admin.packages.index'));

        $this->assertDatabaseHas('packages', [
            'code' => 'PKT-TEST-1',
            'selling_price' => 95000.0,
        ]);
    }

    /**
     * Test: selling_price dengan format "Rp 95.000" tersimpan sebagai 95000.
     */
    public function test_selling_price_with_rp_prefix_saves_correctly()
    {
        $payload = [
            'code' => 'PKT-TEST-2',
            'name' => 'Paket Format Rp',
            'selling_price' => 'Rp 120.000', // format prefix Rp
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'qty' => 1,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.packages.store'), $payload);

        $response->assertRedirect(route('admin.packages.index'));

        $this->assertDatabaseHas('packages', [
            'code' => 'PKT-TEST-2',
            'selling_price' => 120000.0,
        ]);
    }

    /**
     * Test: edit package dengan format Rupiah tetap tersimpan benar.
     */
    public function test_edit_package_with_rupiah_format_saves_correctly()
    {
        $package = Package::create([
            'code' => 'PKT-EDIT-RUP',
            'name' => 'Paket Edit Rp',
            'selling_price' => 50000,
            'is_active' => true,
        ]);

        PackageItem::create([
            'package_id' => $package->id,
            'product_id' => $this->productA->id,
            'qty' => 1,
        ]);

        $payload = [
            'code' => 'PKT-EDIT-RUP',
            'name' => 'Paket Edit Rp Baru',
            'selling_price' => 'Rp 85.500', // format Rp edit
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'qty' => 1,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.packages.update', $package->id), $payload);

        $response->assertRedirect(route('admin.packages.index'));

        $package->refresh();
        $this->assertEquals(85500.0, $package->selling_price);
    }
}

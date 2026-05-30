<?php

namespace Tests\Feature;

use App\Models\PackingItem;
use App\Models\PackingTransaction;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Feature test untuk halaman Detail Packing.
 * Memastikan halaman tidak error 500 meskipun relasi null/dihapus.
 */
class PackingShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: buat admin yang bisa login.
     */
    protected function makeAdmin(): User
    {
        return User::factory()->create([
            'role'            => User::ROLE_ADMIN,
            'is_active'       => true,
            'approval_status' => User::APPROVAL_APPROVED,
        ]);
    }

    /**
     * Helper: buat Unit dummy.
     */
    protected function makeUnit(): Unit
    {
        return Unit::create([
            'name' => 'Pcs',
            'code' => 'pcs-' . uniqid(),
            'type' => 'produk',
        ]);
    }

    /**
     * Helper: buat ProductCategory dummy.
     */
    protected function makeCategory(): ProductCategory
    {
        return ProductCategory::create([
            'name' => 'Robusta',
            'is_active' => true,
        ]);
    }

    /**
     * Helper: buat Product dummy.
     */
    protected function makeProduct(ProductCategory $category, Unit $unit): Product
    {
        return Product::create([
            'code'          => 'PRD-' . uniqid(),
            'name'          => 'Kopi Robusta Premium',
            'product_category_id' => $category->id,
            'variant'       => '250g',
            'weight'        => 250,
            'unit_id'       => $unit->id,
            'cost_price'    => 10000,
            'price'         => 15000,
            'is_active'     => true,
        ]);
    }

    /**
     * Helper: buat PackingTransaction dengan 1 item.
     */
    protected function makePacking(User $admin, int $productId): PackingTransaction
    {
        $packing = PackingTransaction::create([
            'packing_number' => 'PKG-TEST-' . uniqid(),
            'packing_date'   => now()->toDateString(),
            'curah_type'     => 'Robusta',
            'note'           => 'Test Packing Note',
            'created_by'     => $admin->id,
        ]);

        PackingItem::create([
            'packing_transaction_id' => $packing->id,
            'product_id'             => $productId,
            'qty_pack'               => 10,
            'weight_per_pack'        => 250,
            'total_weight'           => 2.500, // kg
        ]);

        return $packing;
    }

    // ── Test: Normal Case ─────────────────────────────────────────────────

    /**
     * Halaman detail terbuka normal saat semua relasi ada.
     */
    public function test_admin_can_view_packing_detail_with_all_relations_present(): void
    {
        $admin    = $this->makeAdmin();
        $unit     = $this->makeUnit();
        $category = $this->makeCategory();
        $product  = $this->makeProduct($category, $unit);
        $packing  = $this->makePacking($admin, $product->id);

        $response = $this->actingAs($admin)
            ->get(route('admin.packings.show', $packing->id));

        $response->assertStatus(200);
        $response->assertSee('Kopi Robusta Premium');
        $response->assertSee('250g');
        $response->assertSee('10 pcs');
    }

    // ── Test: product soft-deleted ────────────────────────────────────────

    /**
     * Halaman detail tetap tampil dengan nama produk walaupun product di-soft-delete.
     * withTrashed() di relasi PackingItem::product() harus memastikan ini berfungsi.
     */
    public function test_admin_can_view_packing_detail_when_product_is_soft_deleted(): void
    {
        $admin    = $this->makeAdmin();
        $unit     = $this->makeUnit();
        $category = $this->makeCategory();
        $product  = $this->makeProduct($category, $unit);
        $packing  = $this->makePacking($admin, $product->id);

        // Soft-delete produk setelah transaksi packing dibuat
        $product->delete();
        $this->assertSoftDeleted('products', ['id' => $product->id]);

        $response = $this->actingAs($admin)
            ->get(route('admin.packings.show', $packing->id));

        // Harus 200, bukan 500
        $response->assertStatus(200);

        // Nama produk tetap muncul (karena withTrashed)
        $response->assertSee('Kopi Robusta Premium');
        $response->assertSee('250g');
        
        // Indikator "(dihapus)" harus muncul
        $response->assertSee('(dihapus)');
    }

    // ── Test: product null (hard deleted / null di production) ──────────

    /**
     * Halaman detail tidak error 500 saat product null (hard-deleted).
     */
    public function test_admin_can_view_packing_detail_when_product_is_null(): void
    {
        $admin = $this->makeAdmin();
        
        // Buat objek PackingTransaction di memory tanpa menyimpan ke DB
        $packing = new PackingTransaction([
            'packing_number' => 'PKG-TEST-NULL',
            'packing_date'   => now()->toDateString(),
            'curah_type'     => 'Robusta',
            'note'           => 'Test null product',
            'created_by'     => $admin->id,
        ]);
        $packing->id = 12345; // dummy id
        $packing->setRelation('creator', $admin);

        // Buat objek PackingItem di memory dengan relasi product = null
        $item = new PackingItem([
            'qty_pack'        => 15,
            'weight_per_pack' => 250,
            'total_weight'    => 3.750,
        ]);
        $item->product_id = 99999; // dummy non-existent id
        $item->setRelation('product', null);

        $packing->setRelation('items', collect([$item]));

        // Test render Blade view secara langsung dengan user admin terautentikasi
        $view = $this->actingAs($admin)
            ->view('admin.packings.show', ['packing' => $packing]);

        // Harus render dengan sukses dan menampilkan fallback text
        $view->assertSee('Produk tidak tersedia');
        $view->assertSee('PKG-TEST-NULL');
    }

    // ── Test: creator null ────────────────────────────────────────────────

    /**
     * Halaman detail tetap tampil dengan creator yang ada.
     */
    public function test_admin_can_view_packing_detail_with_creator_present(): void
    {
        $admin    = $this->makeAdmin();
        $unit     = $this->makeUnit();
        $category = $this->makeCategory();
        $product  = $this->makeProduct($category, $unit);

        // Buat user creator yang valid
        $creator = User::factory()->create([
            'name'            => 'Admin Creator',
            'role'            => User::ROLE_ADMIN,
            'is_active'       => true,
            'approval_status' => User::APPROVAL_APPROVED,
        ]);

        $packing = PackingTransaction::create([
            'packing_number' => 'PKG-TEST-CREATOR',
            'packing_date'   => now()->toDateString(),
            'curah_type'     => 'Robusta',
            'note'           => 'Test creator present',
            'created_by'     => $creator->id,
        ]);

        PackingItem::create([
            'packing_transaction_id' => $packing->id,
            'product_id'             => $product->id,
            'qty_pack'               => 5,
            'weight_per_pack'        => 250,
            'total_weight'           => 1.250,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.packings.show', $packing->id));

        $response->assertStatus(200);
        // Nama creator tampil dengan benar
        $response->assertSee('Admin Creator');
    }
}

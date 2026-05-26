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
        $response->assertSee('Laporan Dasar');
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
}

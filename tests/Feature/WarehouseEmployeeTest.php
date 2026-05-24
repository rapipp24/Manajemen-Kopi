<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WarehouseEmployee;
use App\Models\EmployeeAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseEmployeeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $salesUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->salesUser = User::factory()->create([
            'role' => User::ROLE_SALES,
        ]);
    }

    public function test_admin_can_access_warehouse_employees_index(): void
    {
        $employee = WarehouseEmployee::create([
            'name' => 'Budi Gudang',
            'phone' => '08123456789',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.warehouse-employees.index'));

        $response->assertOk();
        $response->assertSee('Budi Gudang');
        $response->assertSee('08123456789');
    }

    public function test_non_admin_cannot_access_warehouse_employees_index(): void
    {
        $response = $this
            ->actingAs($this->salesUser)
            ->get(route('admin.warehouse-employees.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_store_warehouse_employee(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.warehouse-employees.store'), [
                'name' => '   Ahmad Fauzi   ', // test trim
                'phone' => '087711223344',
                'note' => 'Penanggung jawab shift pagi',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.warehouse-employees.index'));
        $response->assertSessionHas('success', 'Karyawan gudang berhasil ditambahkan.');

        $this->assertDatabaseHas('warehouse_employees', [
            'name' => 'Ahmad Fauzi',
            'phone' => '087711223344',
            'note' => 'Penanggung jawab shift pagi',
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_update_warehouse_employee(): void
    {
        $employee = WarehouseEmployee::create([
            'name' => 'Ahmad Fauzi',
            'phone' => '087711223344',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->put(route('admin.warehouse-employees.update', $employee->id), [
                'name' => 'Ahmad Fauzi Updated',
                'phone' => '087711223344',
                'note' => 'Updated note',
                'is_active' => '0', // deactivate
            ]);

        $response->assertRedirect(route('admin.warehouse-employees.index'));
        $response->assertSessionHas('success', 'Data karyawan gudang berhasil diperbarui.');

        $this->assertDatabaseHas('warehouse_employees', [
            'id' => $employee->id,
            'name' => 'Ahmad Fauzi Updated',
            'is_active' => 0,
        ]);
    }

    public function test_delete_employee_without_attendance_deletes_permanently(): void
    {
        $employee = WarehouseEmployee::create([
            'name' => 'Ahmad Fauzi',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('admin.warehouse-employees.destroy', $employee->id));

        $response->assertRedirect(route('admin.warehouse-employees.index'));
        $response->assertSessionHas('success', 'Karyawan gudang berhasil dihapus.');

        $this->assertDatabaseMissing('warehouse_employees', [
            'id' => $employee->id,
        ]);
    }

    public function test_delete_employee_with_attendance_deactivates_instead_of_deleting(): void
    {
        $employee = WarehouseEmployee::create([
            'name' => 'Ahmad Fauzi',
            'is_active' => true,
        ]);

        EmployeeAttendance::create([
            'warehouse_employee_id' => $employee->id,
            'attendance_date' => '2026-05-24',
            'status' => 'hadir',
            'created_by' => $this->admin->id,
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('admin.warehouse-employees.destroy', $employee->id));

        $response->assertRedirect(route('admin.warehouse-employees.index'));
        $response->assertSessionHas('success', 'Karyawan gudang dinonaktifkan karena sudah memiliki riwayat absensi.');

        // Karyawan harus tetap ada di DB tetapi status aktifnya berubah menjadi false
        $this->assertDatabaseHas('warehouse_employees', [
            'id' => $employee->id,
            'is_active' => 0,
        ]);
    }
}

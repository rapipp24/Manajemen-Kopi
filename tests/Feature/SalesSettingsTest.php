<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesSettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guest tidak bisa mengakses halaman pengaturan akun sales.
     */
    public function test_guest_cannot_access_sales_settings(): void
    {
        $response = $this->get(route('sales.settings'));
        $response->assertRedirect('/login');

        $responsePatch = $this->patch(route('sales.settings.update'), [
            'name' => 'New Name',
        ]);
        $responsePatch->assertRedirect('/login');
    }

    /**
     * Sales user yang aktif, verified, dan approved bisa mengakses pengaturan akun.
     */
    public function test_active_sales_can_access_settings(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SALES,
            'approval_status' => User::APPROVAL_APPROVED,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('sales.settings'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Akun');
        $response->assertSee($user->email);
    }

    /**
     * Sales user bisa mengubah nama, telepon, dan alamatnya sendiri.
     */
    public function test_sales_can_update_own_profile_fields(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SALES,
            'approval_status' => User::APPROVAL_APPROVED,
            'is_active' => true,
            'email_verified_at' => now(),
            'name' => 'Original Name',
            'phone' => '0812345',
            'address' => 'Original Address',
        ]);

        $response = $this->actingAs($user)->patch(route('sales.settings.update'), [
            'name' => 'Updated Name',
            'phone' => '0877777777',
            'address' => 'Updated Address',
        ]);

        $response->assertRedirect(route('sales.settings'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('0877777777', $user->phone);
        $this->assertEquals('Updated Address', $user->address);
    }

    /**
     * Validasi jika input nama kurang dari 3 karakter.
     */
    public function test_validation_fails_when_name_is_too_short(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SALES,
            'approval_status' => User::APPROVAL_APPROVED,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->patch(route('sales.settings.update'), [
            'name' => 'Ab', // Kurang dari 3 karakter
            'phone' => '08123',
            'address' => 'Address',
        ]);

        $response->assertSessionHasErrors('name');
        $user->refresh();
        $this->assertNotEquals('Ab', $user->name);
    }

    /**
     * Sales user tidak bisa mengubah email, password, role, is_active, approval_status, dll.
     */
    public function test_sales_cannot_update_sensitive_fields(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SALES,
            'approval_status' => User::APPROVAL_APPROVED,
            'is_active' => true,
            'email_verified_at' => now(),
            'email' => 'sales@kopi.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->patch(route('sales.settings.update'), [
            'name' => 'New Name',
            'email' => 'hacked@kopi.com',
            'role' => 'admin',
            'is_active' => false,
            'approval_status' => 'pending',
            'password' => 'newpassword123',
        ]);

        $response->assertRedirect(route('sales.settings'));

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('sales@kopi.com', $user->email);
        $this->assertEquals(User::ROLE_SALES, $user->role);
        $this->assertTrue($user->is_active);
        $this->assertEquals(User::APPROVAL_APPROVED, $user->approval_status);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password123', $user->password));
    }
}

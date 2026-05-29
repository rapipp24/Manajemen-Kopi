<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Security test suite untuk authentication dan authorization.
 * Memastikan:
 * 1. Email verification wajib sebelum login.
 * 2. Approval admin wajib untuk sales.
 * 3. is_active=false mencegah login.
 * 4. Admin tidak bisa dibuat dari registrasi publik.
 * 5. Sales pending tidak bisa akses portal.
 * 6. Sales bisa akses portal hanya jika approved + active.
 * 7. Admin bisa approve/reject sales.
 */
class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    // ── Registrasi ─────────────────────────────────────────────────────────

    /** Registrasi publik selalu membuat role=sales, bukan admin */
    public function test_public_registration_always_creates_sales_role(): void
    {
        $this->post('/register', [
            'name'                  => 'Calon Sales',
            'email'                 => 'sales@test.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        $user = User::where('email', 'sales@test.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(User::ROLE_SALES, $user->role, 'Role harus sales dari registrasi publik');
        $this->assertNotEquals(User::ROLE_ADMIN, $user->role, 'Role tidak boleh admin dari registrasi publik');
    }

    /** Registrasi publik tidak bisa inject role=admin */
    public function test_cannot_inject_admin_role_via_registration(): void
    {
        $this->post('/register', [
            'name'                  => 'Calon Admin Ilegal',
            'email'                 => 'illegal@test.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
            'role'                  => 'admin', // injection attempt
        ]);

        $user = User::where('email', 'illegal@test.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(User::ROLE_SALES, $user->role, 'Role injection harus diabaikan');
    }

    /** User baru dari registrasi publik langsung pending */
    public function test_registered_user_starts_as_pending(): void
    {
        $this->post('/register', [
            'name'                  => 'Pending Sales',
            'email'                 => 'pending@test.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        $user = User::where('email', 'pending@test.com')->first();
        $this->assertEquals(User::APPROVAL_PENDING, $user->approval_status);
        $this->assertFalse($user->is_active);
    }

    /** Registrasi tidak auto-login — user harus redirect ke login */
    public function test_registration_does_not_auto_login(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@test.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    // ── Login: Email Verification ─────────────────────────────────────────

    /** User dengan email tidak terverifikasi tidak bisa login */
    public function test_unverified_user_cannot_login(): void
    {
        $user = User::factory()->unverified()->create([
            'approval_status' => User::APPROVAL_APPROVED,
            'is_active'       => true,
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('verifikasi', strtolower($response->getSession()->get('errors')->first('email')));
    }

    // ── Login: Approval Status ────────────────────────────────────────────

    /** Sales pending tidak bisa login */
    public function test_pending_sales_cannot_login(): void
    {
        $user = User::factory()->pendingApproval()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('menunggu', strtolower($response->getSession()->get('errors')->first('email')));
    }

    /** Sales rejected tidak bisa login */
    public function test_rejected_sales_cannot_login(): void
    {
        $user = User::factory()->rejected()->create(['email_verified_at' => now()]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('ditolak', strtolower($response->getSession()->get('errors')->first('email')));
    }

    /** User yang dinonaktifkan tidak bisa login */
    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->inactive()->create([
            'approval_status' => User::APPROVAL_APPROVED,
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('dinonaktifkan', strtolower($response->getSession()->get('errors')->first('email')));
    }

    // ── Login: Generic Error (tidak bocorkan info) ───────────────────────

    /** Login dengan email tidak ada memberikan pesan generik */
    public function test_login_with_unknown_email_gives_generic_error(): void
    {
        $response = $this->post('/login', [
            'email'    => 'nonexistent@test.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        // Pesan harus generik — tidak boleh reveal apakah email ada atau tidak
        $errorMsg = $response->getSession()->get('errors')->first('email');
        $this->assertStringNotContainsString('tidak ditemukan', strtolower($errorMsg));
    }

    // ── Portal Access ─────────────────────────────────────────────────────

    /** Sales approved bisa akses portal sales */
    public function test_approved_sales_can_access_sales_portal(): void
    {
        $user = User::factory()->asSales()->create();

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);
    }

    /** Admin bisa akses portal admin */
    public function test_admin_can_access_admin_portal(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    // ── Admin Approval Actions ─────────────────────────────────────────────

    /** Admin bisa approve sales pending */
    public function test_admin_can_approve_pending_sales(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $sales = User::factory()->pendingApproval()->create();

        $response = $this->actingAs($admin)->post("/admin/users/{$sales->id}/approve");

        $response->assertRedirect();
        $sales->refresh();
        $this->assertEquals(User::APPROVAL_APPROVED, $sales->approval_status);
        $this->assertTrue($sales->is_active);
        $this->assertNotNull($sales->approved_at);
        $this->assertEquals($admin->id, $sales->approved_by);
    }

    /** Admin bisa reject sales pending dengan alasan */
    public function test_admin_can_reject_pending_sales(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $sales = User::factory()->pendingApproval()->create();

        $response = $this->actingAs($admin)->post("/admin/users/{$sales->id}/reject", [
            'rejection_reason' => 'Email tidak valid',
        ]);

        $response->assertRedirect();
        $sales->refresh();
        $this->assertEquals(User::APPROVAL_REJECTED, $sales->approval_status);
        $this->assertFalse($sales->is_active);
        $this->assertEquals('Email tidak valid', $sales->rejection_reason);
    }

    /** Non-admin tidak bisa approve user (403 dari AdminMiddleware) */
    public function test_non_admin_cannot_approve_user(): void
    {
        $sales1 = User::factory()->asSales()->create();
        $sales2 = User::factory()->pendingApproval()->create();

        $response = $this->actingAs($sales1)->post("/admin/users/{$sales2->id}/approve");

        // Admin middleware mengembalikan 403 untuk user yang bukan admin
        $response->assertStatus(403);
        $sales2->refresh();
        $this->assertEquals(User::APPROVAL_PENDING, $sales2->approval_status);
    }

    /** Tidak bisa approve user yang bukan sales */
    public function test_cannot_approve_non_sales_user(): void
    {
        $admin  = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $admin2 = User::factory()->create([
            'role'            => User::ROLE_ADMIN,
            'approval_status' => User::APPROVAL_PENDING,
        ]);

        $response = $this->actingAs($admin)->post("/admin/users/{$admin2->id}/approve");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}

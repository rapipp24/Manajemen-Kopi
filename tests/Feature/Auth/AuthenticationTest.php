<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Admin yang sudah approved dan aktif bisa login,
     * dan diarahkan ke portal admin.
     */
    public function test_admin_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'role'            => User::ROLE_ADMIN,
            'is_active'       => true,
            'approval_status' => User::APPROVAL_APPROVED,
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    /**
     * Sales yang sudah approved dan aktif bisa login,
     * dan diarahkan ke portal sales.
     */
    public function test_sales_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->asSales()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/products');
    }

    /**
     * User dengan password salah tidak bisa login.
     */
    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    /**
     * Sales dengan status pending TIDAK bisa login.
     */
    public function test_pending_sales_cannot_login(): void
    {
        $user = User::factory()->pendingApproval()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Sales yang ditolak TIDAK bisa login.
     */
    public function test_rejected_sales_cannot_login(): void
    {
        $user = User::factory()->rejected()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * User non-aktif TIDAK bisa login.
     */
    public function test_inactive_users_cannot_login(): void
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
    }

    /**
     * User dengan email belum terverifikasi tidak bisa login.
     */
    public function test_unverified_users_cannot_login(): void
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
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}

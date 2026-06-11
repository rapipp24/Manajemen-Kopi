<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelpTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $salesUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Buat User Admin
        $this->admin = User::create([
            'name' => 'Admin Kopi',
            'email' => 'admin@kopi.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        // 2. Buat User Sales
        $this->salesUser = User::create([
            'name' => 'Sales Kopi',
            'email' => 'sales@kopi.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_SALES,
        ]);
    }

    public function test_admin_can_access_help_page(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.help.index'));

        $response->assertOk();
        $response->assertSee('Pusat Bantuan');
        $response->assertSee('Panduan Penggunaan');
        $response->assertSee('Ringkasan');
        $response->assertSee('Mulai dari Mana?');
    }

    public function test_sales_cannot_access_help_page(): void
    {
        $response = $this
            ->actingAs($this->salesUser)
            ->get(route('admin.help.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_help_page(): void
    {
        $response = $this->get(route('admin.help.index'));

        $response->assertRedirect(route('login'));
    }
}

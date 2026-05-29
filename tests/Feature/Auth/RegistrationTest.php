<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * Registrasi publik sekarang TIDAK langsung login user.
     * User diarahkan ke /login dengan flash message status.
     * User berstatus pending + is_active false.
     * Password harus kuat: minimal 8 karakter, huruf besar, huruf kecil, angka.
     */
    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test Sales',
            'email'                 => 'test@example.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        // Tidak langsung login — harus redirect ke login page
        $this->assertGuest();
        $response->assertRedirect('/login');

        // User tercatat di database dengan status yang benar
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(User::ROLE_SALES, $user->role);
        $this->assertEquals(User::APPROVAL_PENDING, $user->approval_status);
        $this->assertFalse($user->is_active);
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test Sales',
            'email'                 => 'not-an-email',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_prevents_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/register', [
            'name'                  => 'Test Sales',
            'email'                 => 'existing@example.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // ── Validasi Email ─────────────────────────────────────────────────────

    /**
     * Email seperti jjadjaw@asdjawdj (domain tidak valid secara RFC)
     * harus ditolak dan user tidak boleh dibuat.
     */
    public function test_register_rejects_invalid_email_format_and_does_not_create_user(): void
    {
        $invalidEmails = [
            'jjadjaw@asdjawdj',   // domain tidak ada TLD yang valid
            'abc@',               // tidak ada domain
            'abc',                // tidak ada @ sama sekali
            'abc@example',        // tidak ada TLD
        ];

        foreach ($invalidEmails as $email) {
            $response = $this->post('/register', [
                'name'                  => 'Budi Santoso',
                'email'                 => $email,
                'password'              => 'Kopi1234',
                'password_confirmation' => 'Kopi1234',
            ]);

            $response->assertSessionHasErrors('email',
                "Email '{$email}' seharusnya ditolak tetapi lolos validasi."
            );

            $this->assertNull(
                User::where('email', $email)->first(),
                "User dengan email '{$email}' tidak seharusnya terbuat di database."
            );
        }
    }

    // ── Validasi Nama ──────────────────────────────────────────────────────

    /** Nama terlalu pendek (kurang dari 3 karakter) harus ditolak */
    public function test_register_rejects_short_name(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'aa',
            'email'                 => 'budi@example.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertNull(User::where('email', 'budi@example.com')->first());
    }

    /** Nama hanya angka (123456) harus ditolak */
    public function test_register_rejects_numeric_only_name(): void
    {
        $response = $this->post('/register', [
            'name'                  => '123456',
            'email'                 => 'budi@example.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertNull(User::where('email', 'budi@example.com')->first());
    }

    /** Nama hanya simbol (@@@@) harus ditolak */
    public function test_register_rejects_symbol_only_name(): void
    {
        $response = $this->post('/register', [
            'name'                  => '@@@@',
            'email'                 => 'budi@example.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertNull(User::where('email', 'budi@example.com')->first());
    }

    // ── Validasi Password ──────────────────────────────────────────────────

    /** Password tanpa angka (Passwordku) harus ditolak */
    public function test_register_rejects_weak_password_without_numbers(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Budi Santoso',
            'email'                 => 'budi@example.com',
            'password'              => 'Passwordku',
            'password_confirmation' => 'Passwordku',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertNull(User::where('email', 'budi@example.com')->first());
    }

    /** Password tanpa huruf besar (kopi1234) harus ditolak */
    public function test_register_rejects_weak_password_without_mixed_case(): void
    {
        $weakPasswords = [
            'kopi1234',    // semua huruf kecil
            'KOPI1234',    // semua huruf besar
            'abcdefgh',    // tidak ada angka dan tidak ada huruf besar
            '12345678',    // semua angka
            'password',    // tidak ada angka
        ];

        foreach ($weakPasswords as $pass) {
            $response = $this->post('/register', [
                'name'                  => 'Budi Santoso',
                'email'                 => 'budi@example.com',
                'password'              => $pass,
                'password_confirmation' => $pass,
            ]);

            $response->assertSessionHasErrors('password',
                "Password '{$pass}' seharusnya ditolak tetapi lolos validasi."
            );
        }
    }

    /** Konfirmasi password tidak cocok harus ditolak */
    public function test_register_rejects_password_confirmation_mismatch(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Budi Santoso',
            'email'                 => 'budi@example.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi5678',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertNull(User::where('email', 'budi@example.com')->first());
    }

    // ── Flow Valid ─────────────────────────────────────────────────────────

    /**
     * Registrasi sales valid harus membuat user dengan:
     * - role = sales
     * - is_active = false
     * - approval_status = pending
     * - tidak auto-login
     */
    public function test_valid_sales_registration_still_creates_pending_inactive_sales_user(): void
    {
        $validNames = [
            'Budi Santoso',
            'Ahmad Fauzi',
            'Siti Nur-Aisyah',
        ];

        foreach ($validNames as $i => $name) {
            $email = "sales{$i}@example.com";

            $response = $this->post('/register', [
                'name'                  => $name,
                'email'                 => $email,
                'password'              => 'Kopi1234',
                'password_confirmation' => 'Kopi1234',
            ]);

            $this->assertGuest();
            $response->assertRedirect('/login');

            $user = User::where('email', $email)->first();
            $this->assertNotNull($user, "User '{$name}' seharusnya terbuat.");
            $this->assertEquals(User::ROLE_SALES, $user->role);
            $this->assertEquals(User::APPROVAL_PENDING, $user->approval_status);
            $this->assertFalse($user->is_active);
        }
    }

    /**
     * Event Registered (yang memicu verification email) hanya dikirim
     * setelah semua validasi lolos dan user berhasil dibuat.
     */
    public function test_verification_email_is_sent_only_for_valid_registration(): void
    {
        // Kasus 1: Email invalid → event TIDAK boleh dikirim
        Event::fake([Registered::class]);

        $this->post('/register', [
            'name'                  => 'Budi Santoso',
            'email'                 => 'jjadjaw@asdjawdj',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        Event::assertNotDispatched(Registered::class);

        // Kasus 2: Registrasi valid → event HARUS dikirim
        $this->post('/register', [
            'name'                  => 'Budi Santoso',
            'email'                 => 'budi@example.com',
            'password'              => 'Kopi1234',
            'password_confirmation' => 'Kopi1234',
        ]);

        Event::assertDispatched(Registered::class);
    }
}

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    /**
     * Test 1: User belum login bisa klik signed verification URL valid.
     * - email_verified_at terisi
     * - approval_status tetap pending
     * - is_active tetap 0
     * - response redirect aman
     */
    public function test_unauthenticated_user_can_verify_email_via_signed_url(): void
    {
        $user = User::factory()->unverified()->create([
            'approval_status' => 'pending',
            'is_active' => false,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Klik link tanpa login (no actingAs)
        $response = $this->get($verificationUrl);

        // Harus redirect ke halaman sukses
        $response->assertRedirect(route('verification.success'));

        // Verifikasi event dispatched
        Event::assertDispatched(Verified::class);

        // Verifikasi email_verified_at terisi
        $user->refresh();
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNotNull($user->email_verified_at);

        // Verifikasi approval_status tetap pending
        $this->assertEquals('pending', $user->approval_status);

        // Verifikasi is_active tetap 0/false
        $this->assertFalse((bool) $user->is_active);
    }

    /**
     * Test 2: Link invalid hash → response 403, email_verified_at tetap null.
     */
    public function test_verification_fails_with_invalid_hash(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email@example.com')]
        );

        $response = $this->get($verificationUrl);

        $response->assertStatus(403);

        // email_verified_at tetap null
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    /**
     * Test 3: Link expired/signature invalid → response 403.
     */
    public function test_verification_fails_with_expired_link(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinutes(5), // sudah expired
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verificationUrl);

        $response->assertStatus(403);
    }

    /**
     * Test 3b: Link dengan signature yang dimanipulasi → response 403.
     */
    public function test_verification_fails_with_tampered_signature(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Tamper the signature
        $tamperedUrl = $verificationUrl . 'tampered';

        $response = $this->get($tamperedUrl);

        $response->assertStatus(403);
    }

    /**
     * Test 4: User sudah verified klik ulang → tidak error, redirect aman.
     */
    public function test_already_verified_user_clicking_link_again_redirects_safely(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verificationUrl);

        // Redirect aman, bukan error
        $response->assertRedirect(route('verification.success'));

        // Event Verified TIDAK di-dispatch ulang
        Event::assertNotDispatched(Verified::class);
    }

    /**
     * Test 5: Login setelah email verified tapi belum approved → tetap ditolak.
     */
    public function test_login_rejected_when_email_verified_but_not_approved(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'approval_status' => 'pending',
            'is_active' => false,
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // User harus ditolak login, diarahkan kembali ke login
        $response->assertSessionHasErrors('email');

        // Pastikan user TIDAK ter-authenticate
        $this->assertGuest();
    }

    /**
     * Test 6: Resend verification tetap memerlukan auth.
     */
    public function test_resend_verification_requires_auth(): void
    {
        $response = $this->post('/email/verification-notification');

        // Harus redirect ke login karena butuh auth
        $response->assertRedirect('/login');
    }

    /**
     * Test: Authenticated user juga bisa verifikasi email (backward compatibility).
     */
    public function test_authenticated_user_can_also_verify_email(): void
    {
        $user = User::factory()->unverified()->create([
            'approval_status' => 'pending',
            'is_active' => false,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Verifikasi sambil login juga harus tetap bisa
        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect(route('verification.success'));
        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /**
     * Test: User ID yang tidak ada → 404.
     */
    public function test_verification_fails_with_nonexistent_user_id(): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => 99999, 'hash' => sha1('test@example.com')]
        );

        $response = $this->get($verificationUrl);

        $response->assertStatus(404);
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     * Default: admin role, approved, active, email verified.
     * Cocok untuk sebagian besar test yang butuh user yang bisa login.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            // Kolom baru — default: approved dan aktif agar test lama tetap berjalan
            'role'              => User::ROLE_ADMIN,
            'is_active'         => true,
            'approval_status'   => User::APPROVAL_APPROVED,
        ];
    }

    /**
     * State: user sales yang sudah approved dan aktif.
     */
    public function asSales(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'            => User::ROLE_SALES,
            'is_active'       => true,
            'approval_status' => User::APPROVAL_APPROVED,
        ]);
    }

    /**
     * State: user sales yang pending approval (belum disetujui).
     */
    public function pendingApproval(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'              => User::ROLE_SALES,
            'is_active'         => false,
            'approval_status'   => User::APPROVAL_PENDING,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * State: user sales yang rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'            => User::ROLE_SALES,
            'is_active'       => false,
            'approval_status' => User::APPROVAL_REJECTED,
        ]);
    }

    /**
     * State: email belum diverifikasi.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * State: user non-aktif (dinonaktifkan admin).
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

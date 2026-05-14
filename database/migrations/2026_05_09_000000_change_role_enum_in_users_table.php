<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah enum role dari 'user' menjadi 'sales' dan migrasi data lama.
     */
    public function up(): void
    {
        // 1. Expand enum untuk sementara agar bisa menampung kedua nilai
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'sales') NOT NULL DEFAULT 'sales'");

        // 2. Update data lama: role 'user' → 'sales'
        DB::table('users')->where('role', 'user')->update(['role' => 'sales']);

        // 3. Hapus nilai 'user' dari enum (sekarang sudah aman)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'sales') NOT NULL DEFAULT 'sales'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan data 'sales' → 'user'
        DB::table('users')->where('role', 'sales')->update(['role' => 'user']);

        // Kembalikan definisi kolom enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }
};

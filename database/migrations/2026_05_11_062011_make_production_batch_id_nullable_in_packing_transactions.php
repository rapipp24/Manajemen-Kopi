<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jadikan production_batch_id nullable.
     * Alasan: Packing memakai Opsi B — stok curah diagregasi dari semua batch,
     * bukan dari satu batch tertentu. Packing tidak perlu terikat ke satu batch.
     */
    public function up(): void
    {
        Schema::table('packing_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('production_batch_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('packing_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('production_batch_id')->nullable(false)->change();
        });
    }
};

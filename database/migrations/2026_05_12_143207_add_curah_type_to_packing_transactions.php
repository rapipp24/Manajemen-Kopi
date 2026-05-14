<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom curah_type ke packing_transactions.
     * Kolom ini menyimpan jenis produksi (misal: "Kopi Robusta", "Kopi Arabika")
     * yang digunakan sebagai sumber curah untuk packing ini.
     */
    public function up(): void
    {
        Schema::table('packing_transactions', function (Blueprint $table) {
            // Ditempatkan setelah packing_date
            $table->string('curah_type')->nullable()->after('packing_date');
        });
    }

    public function down(): void
    {
        Schema::table('packing_transactions', function (Blueprint $table) {
            $table->dropColumn('curah_type');
        });
    }
};


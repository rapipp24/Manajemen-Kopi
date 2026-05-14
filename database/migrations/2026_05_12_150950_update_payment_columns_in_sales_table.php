<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Kita drop enum lama dan buat sebagai string agar lebih fleksibel
            $table->dropColumn('payment_status');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->string('payment_status')->default('belum_bayar')->after('sale_date');
            $table->string('payment_method')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_status');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->enum('payment_status', ['belum_bayar', 'lunas', 'cod'])->default('belum_bayar')->after('sale_date');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_reports', function (Blueprint $table) {
            // 1. Jadikan customer_id nullable agar data lama aman dan field ini opsional
            $table->foreignId('customer_id')->nullable()->change();

            // 2. Field input manual toko — diisi jika customer_id kosong
            $table->string('customer_name_manual')->nullable()->after('customer_id');
            $table->string('customer_address_manual')->nullable()->after('customer_name_manual');
            $table->string('customer_phone_manual', 20)->nullable()->after('customer_address_manual');

            // 3. Tempo pembayaran — 15 atau 30 hari (null = tidak ditentukan)
            $table->unsignedTinyInteger('payment_term_days')->nullable()->after('customer_phone_manual');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_reports', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name_manual',
                'customer_address_manual',
                'customer_phone_manual',
                'payment_term_days',
            ]);
            $table->foreignId('customer_id')->nullable(false)->change();
        });
    }
};

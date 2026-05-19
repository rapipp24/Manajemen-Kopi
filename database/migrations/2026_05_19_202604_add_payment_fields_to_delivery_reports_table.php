<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('delivery_reports', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->default(0)->after('status');
            $table->string('payment_status', 20)->default('belum_bayar')->after('total_amount'); // belum_bayar, dp, lunas
            $table->decimal('down_payment_amount', 15, 2)->default(0)->after('payment_status');
            $table->date('due_date')->nullable()->after('down_payment_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_reports', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'payment_status', 'down_payment_amount', 'due_date']);
        });
    }
};

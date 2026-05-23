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
            $table->timestamp('overpayment_resolved_at')->nullable()->after('due_date');
            $table->foreignId('overpayment_resolved_by')->nullable()->constrained('users')->after('overpayment_resolved_at');
            $table->text('overpayment_resolution_note')->nullable()->after('overpayment_resolved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_reports', function (Blueprint $table) {
            $table->dropForeign(['overpayment_resolved_by']);
            $table->dropColumn(['overpayment_resolved_at', 'overpayment_resolved_by', 'overpayment_resolution_note']);
        });
    }
};

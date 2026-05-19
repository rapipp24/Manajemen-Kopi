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
        Schema::create('delivery_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();              // nomor nota, e.g. DEL-20260516-001
            $table->foreignId('sales_id')->constrained('users');   // sales yang mengirim
            $table->foreignId('customer_id')->constrained('customers'); // toko tujuan
            $table->date('delivery_date');
            $table->text('note')->nullable();
            $table->enum('status', ['submitted'])->default('submitted'); // siap untuk future: draft
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_reports');
    }
};

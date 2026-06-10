<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_report_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_report_id')->constrained('delivery_reports')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('restrict');
            $table->unsignedInteger('qty'); // integer minimal 1
            $table->decimal('price', 15, 2); // harga jual paket snapshot
            $table->decimal('subtotal', 15, 2); // qty * price
            $table->string('package_name_snapshot');
            $table->string('package_code_snapshot');
            $table->decimal('package_hpp_snapshot', 15, 2)->default(0.00); // HPP paket snapshot
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_report_package_items');
    }
};

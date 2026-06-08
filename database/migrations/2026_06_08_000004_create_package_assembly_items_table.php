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
        Schema::create('package_assembly_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_assembly_id')->constrained('package_assemblies')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->decimal('qty_per_package', 12, 2);
            $table->decimal('total_qty_used', 12, 2);
            $table->decimal('cost_price_snapshot', 15, 2); // HPP produk saat perakitan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_assembly_items');
    }
};

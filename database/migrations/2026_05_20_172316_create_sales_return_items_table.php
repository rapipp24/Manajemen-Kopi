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
        Schema::create('sales_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_return_id')->constrained('sales_returns')->cascadeOnDelete();
            $table->foreignId('delivery_report_item_id')->constrained('delivery_report_items');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('qty_return');
            // price_snapshot diambil dari delivery_report_items.price saat return dibuat, bukan dari master produk
            $table->decimal('price_snapshot', 15, 2);
            $table->decimal('subtotal_return', 15, 2); // qty_return * price_snapshot
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_return_items');
    }
};

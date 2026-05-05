<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Raw Material Receipts
        Schema::create('raw_material_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->date('receipt_date');
            $table->text('note')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('raw_material_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained('raw_material_receipts')->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials');
            $table->decimal('qty', 12, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 2. Production Batches
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->date('production_date');
            $table->string('product_type');
            $table->decimal('total_material_used', 12, 2);
            $table->decimal('total_output', 12, 2);
            $table->decimal('shrinkage', 12, 2)->nullable(); // Penyusutan
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('production_batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials');
            $table->decimal('qty_used', 12, 2);
            $table->timestamps();
        });

        // 3. Packing Transactions
        Schema::create('packing_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('packing_number')->unique();
            $table->date('packing_date');
            $table->foreignId('production_batch_id')->constrained('production_batches');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('packing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('packing_transaction_id')->constrained('packing_transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('qty_pack');
            $table->integer('weight_per_pack'); // dlm gram
            $table->decimal('total_weight', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packing_items');
        Schema::dropIfExists('packing_transactions');
        Schema::dropIfExists('production_batch_items');
        Schema::dropIfExists('production_batches');
        Schema::dropIfExists('raw_material_receipt_items');
        Schema::dropIfExists('raw_material_receipts');
    }
};

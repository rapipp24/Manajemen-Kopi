<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. Sales
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->date('sale_date');
            $table->enum('payment_status', ['belum_bayar', 'lunas', 'cod'])->default('belum_bayar');
            $table->decimal('total_amount', 15, 2);
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('qty');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 5. Returns
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('sale_id')->nullable()->constrained('sales');
            $table->date('return_date');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('returns')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('qty');
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        // 6. Orders (User Pesan)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->date('order_date');
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('belum_bayar');
            $table->decimal('total_amount', 15, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('qty');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 7. Attendances
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->date('attendance_date');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa'])->default('hadir');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 8. Stock Movements (Kartu Stok)
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->enum('item_type', ['raw_material', 'product']);
            $table->unsignedBigInteger('item_id');
            $table->enum('movement_type', ['in', 'out', 'adjustment', 'return']);
            $table->string('reference_type')->nullable(); // Model class name
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('qty', 12, 2);
            $table->decimal('stock_before', 12, 2);
            $table->decimal('stock_after', 12, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};

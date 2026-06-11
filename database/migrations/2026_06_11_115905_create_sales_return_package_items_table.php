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
        Schema::create('sales_return_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_return_id')->constrained('sales_returns')->onDelete('cascade');
            $table->foreignId('delivery_report_package_item_id')
                ->constrained('delivery_report_package_items')
                ->onDelete('restrict')
                ->name('sr_pkg_items_dr_pkg_item_id_foreign');
            $table->foreignId('package_id')->constrained('packages')->onDelete('restrict');
            $table->unsignedInteger('qty');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->string('package_name_snapshot');
            $table->string('package_code_snapshot');
            $table->decimal('package_hpp_snapshot', 15, 2)->default(0.00);
            $table->enum('condition', ['layak_jual', 'tidak_layak_jual', 'perlu_proses_ulang']);
            $table->text('replacement_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_return_package_items');
    }
};

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
        Schema::create('sales_package_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('package_id')->constrained('packages')->onDelete('restrict');
            $table->decimal('qty', 12, 2)->default(0.00);
            $table->timestamps();

            // Memastikan tidak ada duplikasi kombinasi user dan paket
            $table->unique(['user_id', 'package_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_package_stocks');
    }
};

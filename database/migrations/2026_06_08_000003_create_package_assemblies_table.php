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
        Schema::create('package_assemblies', function (Blueprint $table) {
            $table->id();
            $table->string('assembly_number')->unique();
            $table->foreignId('package_id')->constrained('packages')->onDelete('restrict');
            $table->decimal('qty', 12, 2);
            $table->decimal('hpp_per_package_snapshot', 15, 2); // modal per 1 pack
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_assemblies');
    }
};

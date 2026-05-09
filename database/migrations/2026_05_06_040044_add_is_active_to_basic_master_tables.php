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
        Schema::table('units', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('code');
        });
        
        Schema::table('suppliers', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('contact_person');
        });
        
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};

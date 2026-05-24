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
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_employee_id');
            $table->date('attendance_date');
            $table->string('status'); // hadir, izin, sakit, alfa
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // RESTRICT: riwayat absensi tidak boleh ikut terhapus saat karyawan gudang dihapus
            $table->foreign('warehouse_employee_id')
                  ->references('id')
                  ->on('warehouse_employees')
                  ->onDelete('restrict');

            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->unique(
                ['warehouse_employee_id', 'attendance_date'],
                'employee_attendances_employee_date_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Additive migration — hanya menambahkan kolom, tidak mengubah data sensitif.
     * Semua user existing akan di-set approval_status = 'approved' agar tidak terkunci.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Status persetujuan pendaftaran sales publik
            // Default 'approved' di DB agar user lama tidak terkunci.
            // Register publik harus eksplisit set 'pending' di controller.
            $table->string('approval_status')->default('approved')->after('is_active');

            // Audit trail persetujuan
            $table->timestamp('approved_at')->nullable()->after('approval_status');
            $table->foreignId('approved_by')
                ->nullable()
                ->after('approved_at')
                ->constrained('users')
                ->nullOnDelete();

            // Audit trail penolakan
            $table->timestamp('rejected_at')->nullable()->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
        });

        // Pastikan semua user existing sudah approved
        // agar tidak ada user lama yang terkunci setelah migration ini
        DB::table('users')
            ->whereNull('approval_status')
            ->orWhere('approval_status', '')
            ->update(['approval_status' => 'approved']);

        // Tambahan keamanan: pastikan semua user existing yang tidak berstatus 'approved'
        // tidak sengaja jadi 'pending'. Semua user sebelum fitur ini = approved.
        // (kolom default sudah 'approved', tapi ini untuk jaga-jaga)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['approved_by']);

            $table->dropColumn([
                'approval_status',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejection_reason',
            ]);
        });
    }
};

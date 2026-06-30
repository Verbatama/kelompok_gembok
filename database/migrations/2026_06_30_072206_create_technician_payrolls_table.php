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
        Schema::create('technician_payrolls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('technician_id')
                ->constrained('technicians')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');

            // Snapshot gaji saat payroll diproses
            $table->unsignedInteger('gaji_pokok')->default(0);

            // Data absensi
            $table->unsignedInteger('jumlah_telat')->default(0);
            $table->unsignedInteger('jumlah_absen')->default(0);

            // Potongan
            $table->unsignedInteger('denda_telat')->default(0);
            $table->unsignedInteger('denda_absen')->default(0);

            // Tambahan
            $table->unsignedInteger('bonus')->default(0);

            // Ringkasan
            $table->unsignedInteger('total_potongan')->default(0);
            $table->unsignedInteger('total_diterima')->default(0);

            // Status payroll
            $table->enum('status', [
                'draft',
                'approved',
                'paid'
            ])->default('draft');

            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->unique([
                'technician_id',
                'bulan',
                'tahun'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technician_payrolls');
    }
};
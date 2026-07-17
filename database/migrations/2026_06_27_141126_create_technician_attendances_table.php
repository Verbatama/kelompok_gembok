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
        Schema::create('technician_attendances', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('technician_id')
                ->constrained('technicians')
                ->cascadeOnDelete();
            $table->date('attendance_date');
            $table->string('image_selfie')->nullable();
            $table->enum('status', ['check-in', 'check-out', 'absent']);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_late')->default(false);
            $table->time('check_in_limit')->nullable();

            // Rentang jam bonus check out
                $table->time('bonus_check_out_mulai')->nullable();
                $table->time('bonus_check_out_selesai')->nullable();

                
                $table->boolean('bonus_didapat')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technician_attendances');
    }
};

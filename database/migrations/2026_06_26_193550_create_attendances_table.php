<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->string('nama', 255);
        $table->string('image_selfie', 255)->nullable();
        
        // PERBAIKAN: Hapus ->after(...) di sini. Urutannya otomatis di bawah image_selfie
        $table->string('status')->default('check-in'); 
        
        $table->string('latitude', 255)->nullable();
        $table->string('longitude', 255)->nullable();
        $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // GANTI: Nama tabel diganti menjadi ticket_gangguans
        Schema::create('ticket_gangguans', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_id');
            
            // Menggunakan enum untuk pilihan jenis gangguan
            $table->enum('jenis_gangguan', [
                'Loss Merah', 
                'Redaman Tinggi', 
                'SSID Hilang', 
                'SSID Lemah', 
                'ONT Mati', 
                'PON Blinking', 
                'Internet Offline', 
                'Internet Lemot', 
                'Lainnya'
            ]);
            $table->enum('prioritas', ['Rendah', 'Normal', 'Tinggi'])->default('Normal');
            $table->text('keterangan')->nullable();
            
            // Info Koneksi Otomatis dari Sistem
            $table->string('connection_status')->default('ONLINE');
            $table->string('pppoe_username');
            $table->string('ip_address');
            $table->string('mac_address');
            $table->timestamp('last_update_connection')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // GANTI: Disesuaikan dengan nama tabel baru saat rollback
        Schema::dropIfExists('ticket_gangguans');
    }
};
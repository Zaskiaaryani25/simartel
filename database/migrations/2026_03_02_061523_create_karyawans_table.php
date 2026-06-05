<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            // Kolom ID Utama
            $table->id(); 
            
            // Nama diberikan Index agar pencarian saat Sync lebih cepat
            $table->string('nama', 100)->index();
            
            $table->string('jabatan', 50)->default('Petugas P2TL');
            $table->unsignedTinyInteger('umur')->default(0);
            
            // Menggunakan tipe Year untuk efisiensi tahun
            $table->year('tahun_masuk')->default(date('Y'));
            
            $table->string('foto')->nullable();
            $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
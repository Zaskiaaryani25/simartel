<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('p2tl_records', function (Blueprint $table) {
            $table->id();
            $table->string('no_p2tl')->nullable(); 
            $table->string('id_p2tl')->nullable();
            $table->string('idpel', 50)->nullable();
            $table->string('update_status')->nullable();
            $table->decimal('kwh_ts', 15, 2)->default(0); 
            $table->dateTime('waktu_periksa')->nullable(); 
            $table->string('unit_ulp')->nullable();
            $table->string('no_agenda')->nullable();
            $table->string('username')->nullable();
            $table->string('nama_petugas')->nullable();
            $table->string('tarif')->nullable();
            $table->string('tegangan_r_n')->nullable(); 
            $table->integer('tgl')->nullable();
            $table->string('bulan', 20)->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();

            // OPTIMIZATION INDEXES
            $table->index('idpel');
            $table->index('nama_petugas');
            $table->index('unit_ulp');
            $table->index('tahun');
            $table->index('bulan');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p2tl_records');
    }
};
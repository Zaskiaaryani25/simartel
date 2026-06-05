<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('karyawan_stats', function (Blueprint $table) {
        $table->id();
        $table->foreignId('karyawan_id')->unique()->constrained('karyawans')->onDelete('cascade');
        $table->integer('total_periksa')->default(0);
        $table->decimal('hit_rate', 5, 2)->default(0);
        $table->json('rincian_temuan')->nullable(); // Menyimpan data P1, P2, K2 dalam bentuk JSON
        $table->integer('total_akumulasi_bh')->default(0);
        $table->decimal('total_akumulasi_kwh', 15, 2)->default(0);
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_stats');
    }
};

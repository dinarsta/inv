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
     Schema::create('histori_transaksis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('barang_id')->constrained()->onDelete('cascade');
        $table->enum('jenis', ['in', 'out']);
        $table->integer('jumlah');
        $table->string('oleh');
        $table->string('divisi')->nullable();
        $table->string('keterangan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histori_transaksis');
    }
};

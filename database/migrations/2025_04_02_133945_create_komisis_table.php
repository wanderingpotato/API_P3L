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
        Schema::create('komisis', function (Blueprint $table) {
            $table->string('id_komisi')->primary();
            $table->string('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('penitipan__barangs')->onDelete('cascade');
            $table->bigInteger('id_pegawai')->unsigned()->nullable();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->bigInteger('id_penitip')->unsigned()->nullable();
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
            $table->double('bonus_penitip')->nullable();
            $table->double('komisi_penitip');
            $table->double('komisi_toko');
            $table->double('komisi_hunter')->nullable();
            $table->dateTime('tanggal_komisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komisis');
    }
};

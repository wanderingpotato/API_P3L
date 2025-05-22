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
        Schema::create('penitipan__barangs', function (Blueprint $table) {
            $table->string('id_barang')->primary();
            $table->string('id_kategori');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori__barangs')->onDelete('cascade');
            $table->bigInteger('id_penitip')->unsigned();
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
            $table->bigInteger('id_pegawai')->unsigned();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->string('nama_barang');
            $table->boolean('di_perpanjang');
            $table->boolean('diliver_here');
            $table->boolean('hunter');
            $table->enum('status',['DiJual','DiDonasikan','DiKembalikan','DiBeli','Kadaluarsa']);
            $table->double('harga_barang');
            $table->double('rating')->nullable();
            $table->dateTime('tanggal_penitipan');
            $table->dateTime('tanggal_kadaluarsa');
            $table->dateTime('batas_ambil');
            $table->dateTime('tanggal_laku')->nullable();
            $table->dateTime('tanggal_rating')->nullable();
            $table->dateTime('garansi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penitipan__barangs');
    }
};

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
            $table->string('Id_barang')->primary();
            $table->string('Id_kategori');
            $table->foreign('Id_kategori')->references('Id_kategori')->on('kategori__barangs')->onDelete('cascade');
            $table->bigInteger('Id_Penitip')->unsigned();
            $table->foreign('Id_Penitip')->references('Id_Penitip')->on('penitips')->onDelete('cascade');
            $table->bigInteger('Id_Pegawai')->unsigned();
            $table->foreign('Id_Pegawai')->references('Id_Pegawai')->on('pegawais')->onDelete('cascade');
            $table->string('Nama_Barang');
            $table->boolean('DiPerpanjang');
            $table->boolean('DiliverHere');
            $table->boolean('Hunter');
            $table->enum('Status',['DiJual','DiDonasikan','DiKembalikan','DiBeli']);
            $table->double('Harga_barang');
            $table->double('Rating')->nullable();
            $table->dateTime('Tanggal_penitipan');
            $table->dateTime('Tanggal_kadaluarsa');
            $table->dateTime('Batas_ambil');
            $table->dateTime('Tanggal_laku')->nullable();
            $table->dateTime('Tanggal_rating')->nullable();
            $table->dateTime('Garansi')->nullable();
            $table->text('Foto_Barang')->nullable();
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

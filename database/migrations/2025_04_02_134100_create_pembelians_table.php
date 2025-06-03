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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->string('id_pembelian')->primary();
            $table->string('id_alamat')->nullable();
            $table->foreign('id_alamat')->references('id_alamat')->on('alamats');
            $table->bigInteger('id_pembeli')->unsigned();
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
            $table->bigInteger('id_pegawai')->unsigned()->nullable();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->boolean('dilivery');
            $table->enum('status',['Proses','Batal','Selesai','Keranjang']);
            $table->enum('status_pengiriman',['DiProses','Pegiriman','Sampai','Keranjang'])->nullable();
            $table->double('point_yg_didapat')->nullable();
            $table->double('point_current');
            $table->double('point_digunakan')->nullable();
            $table->double('potongan_harga')->nullable();
            $table->double('harga_barang');
            $table->double('ongkir');
            $table->dateTime('batas_waktu');
            $table->dateTime('tanggal_pembelian');
            $table->dateTime('tanggal_lunas');
            $table->dateTime('tanggal_pengiriman-pengambilan')->nullable();
            $table->text('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};

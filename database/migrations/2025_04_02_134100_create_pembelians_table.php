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
            $table->string('Id_pembelian')->primary();
            $table->string('Id_alamat');
            $table->foreign('Id_alamat')->references('Id_alamat')->on('alamats')->onDelete('cascade');
            $table->bigInteger('Id_Pembeli')->unsigned();
            $table->foreign('Id_Pembeli')->references('Id_Pembeli')->on('pembelis')->onDelete('cascade');
            $table->bigInteger('Id_Pegawai')->unsigned()->nullable();
            $table->foreign('Id_Pegawai')->references('Id_Pegawai')->on('pegawais')->onDelete('cascade');
            $table->boolean('Dilivery');
            $table->enum('Status',['Proses','Batal','Selesai']);
            $table->enum('Status_Pengiriman',['DiProses','Pegiriman','Sampai'])->nullable();
            $table->double('PointYgDidapat')->nullable();
            $table->double('PointCurrent');
            $table->double('PointDigunakan')->nullable();
            $table->double('Potongan_Harga')->nullable();
            $table->double('Harga_Barang');
            $table->double('Ongkir');
            $table->dateTime('Batas_Waktu');
            $table->dateTime('Tanggal_Pembelian');
            $table->dateTime('Tanggal_Lunas');
            $table->dateTime('Tanggal_Pengiriman-Pengambilan')->nullable();
            $table->text('Bukti_Pembayaran')->nullable();
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

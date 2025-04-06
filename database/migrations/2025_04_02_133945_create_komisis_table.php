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
            $table->string('Id_komisi')->primary();
            $table->string('Id_barang');
            $table->foreign('Id_barang')->references('Id_barang')->on('penitipan__barangs')->onDelete('cascade');
            $table->bigInteger('Id_pegawai')->unsigned()->nullable();
            $table->foreign('Id_pegawai')->references('Id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->bigInteger('Id_penitip')->unsigned()->nullable();
            $table->foreign('Id_penitip')->references('Id_penitip')->on('penitips')->onDelete('cascade');
            $table->double('Bonus_Penitip')->nullable();
            $table->double('Komisi_Penitip');
            $table->double('Komisi_Toko');
            $table->double('Komisi_Hunter')->nullable();
            $table->dateTime('Tanggal_Komisi');
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

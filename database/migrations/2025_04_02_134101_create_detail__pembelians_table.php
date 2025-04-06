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
        Schema::create('detail__pembelians', function (Blueprint $table) {
            $table->string('Id_pembelian');
            $table->foreign('Id_pembelian')->references('Id_pembelian')->on('pembelians')->onDelete('cascade');
            $table->string('Id_barang');
            $table->foreign('Id_barang')->references('Id_barang')->on('penitipan__barangs')->onDelete('cascade');
            $table->primary(['Id_pembelian','Id_barang']);
            $table->bigInteger('Id_penitip')->unsigned();
            $table->foreign('Id_penitip')->references('Id_penitip')->on('penitips')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__pembelians');
    }
};

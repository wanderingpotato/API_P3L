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
        Schema::create('detail__donasis', function (Blueprint $table) {
            $table->string('id_donasi');
            $table->foreign('id_donasi')->references('id_donasi')->on('donasis')->onDelete('cascade');
            $table->string('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('penitipan__barangs')->onDelete('cascade');
            $table->primary(['id_donasi','id_barang']);
            $table->bigInteger('id_penitip')->unsigned();
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__donasis');
    }
};

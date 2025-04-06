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
        Schema::create('diskusis', function (Blueprint $table) {
            $table->string('Id_diskusi')->primary();
            $table->bigInteger('Id_Pembeli')->unsigned()->nullable();
            $table->foreign('Id_Pembeli')->references('Id_Pembeli')->on('pembelis')->onDelete('cascade');
            $table->bigInteger('Id_Penitip')->unsigned()->nullable();
            $table->foreign('Id_Penitip')->references('Id_Penitip')->on('penitips')->onDelete('cascade');
            $table->bigInteger('Id_Pegawai')->unsigned()->nullable();
            $table->foreign('Id_Pegawai')->references('Id_Pegawai')->on('pegawais')->onDelete('cascade');
            $table->string('Id_Barang')->nullable();
            $table->foreign('Id_Barang')->references('Id_Barang')->on('penitipan__barangs')->onDelete('cascade');
            $table->string('Title');
            $table->text('Deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diskusis');
    }
};

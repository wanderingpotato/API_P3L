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
        Schema::create('donasis', function (Blueprint $table) {
            $table->string('Id_donasi')->primary();
            $table->bigInteger('Id_organisasi')->unsigned();
            $table->foreign('Id_organisasi')->references('Id_organisasi')->on('organisasis')->onDelete('cascade');
            $table->string('Nama_Penerima');
            $table->boolean('Konfirmasi');
            $table->dateTime('Tanggal_diberikan');
            $table->dateTime('Tanggal_request');
            $table->text('Deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasis');
    }
};

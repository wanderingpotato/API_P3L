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
        Schema::create('klaim__merchandises', function (Blueprint $table) {
            $table->string('Id_klaim')->primary();
            $table->string('Id_merchandise');
            $table->foreign('Id_merchandise')->references('Id_merchandise')->on('merchandises')->onDelete('cascade');
            $table->bigInteger('Id_Pembeli')->unsigned()->nullable();
            $table->foreign('Id_Pembeli')->references('Id_Pembeli')->on('pembelis')->onDelete('cascade');
            $table->bigInteger('Id_penitip')->unsigned()->nullable();
            $table->foreign('Id_penitip')->references('Id_penitip')->on('penitips')->onDelete('cascade');
            $table->integer('Jumlah');
            $table->dateTime('Tanggal_ambil');
            $table->enum('Status',['On-Progress','Claimed','Canceled']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klaim__merchandises');
    }
};

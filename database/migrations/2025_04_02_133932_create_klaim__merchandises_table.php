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
            $table->string('id_klaim')->primary();
            $table->string('id_merchandise');
            $table->foreign('id_merchandise')->references('id_merchandise')->on('merchandises')->onDelete('cascade');
            $table->bigInteger('id_pembeli')->unsigned()->nullable();
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
            $table->bigInteger('id_penitip')->unsigned()->nullable();
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
            $table->integer('jumlah');
            $table->dateTime('tanggal_ambil');
            $table->enum('status',['On-Progress','Claimed','Canceled']);
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

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
        Schema::create('detail__pendapatans', function (Blueprint $table) {
            $table->string('Id_DetailPendapatan')->primary();
            $table->bigInteger('Id_penitip')->unsigned();
            $table->foreign('Id_penitip')->references('Id_penitip')->on('penitips')->onDelete('cascade');
            $table->double('total');
            $table->date('month');
            $table->double('Bonus_Pendapatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__pendapatans');
    }
};

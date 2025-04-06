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
        Schema::create('alamats', function (Blueprint $table) {
            $table->string('Id_alamat')->primary();
            $table->bigInteger('Id_Pembeli')->unsigned();
            $table->foreign('Id_Pembeli')->references('Id_Pembeli')->on('pembelis')->onDelete('cascade');
            $table->string('NoTelp');
            $table->string('Title');
            $table->boolean('Default');
            $table->text('Deskripsi');
            $table->text('Alamat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamats');
    }
};

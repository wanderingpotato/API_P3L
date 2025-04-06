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
        Schema::create('merchandises', function (Blueprint $table) {
            $table->string('Id_merchandise')->primary();
            $table->string('Nama');
            $table->string('Poin');
            $table->enum('Kategori',['Ballpoin','Stiker','Mug','Topi','Tumblr','T-shirt','Jam-Dinding','Tas-Travel','Payung']);
            $table->double('Stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchandises');
    }
};

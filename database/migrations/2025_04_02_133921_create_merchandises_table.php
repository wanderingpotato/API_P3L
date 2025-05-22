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
            $table->string('id_merchandise')->primary();
            $table->string('nama');
            $table->string('poin');
            $table->enum('kategori',['Ballpoin','Stiker','Mug','Topi','Tumblr','T-shirt','Jam-Dinding','Tas-Travel','Payung']);
            $table->double('stock');
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

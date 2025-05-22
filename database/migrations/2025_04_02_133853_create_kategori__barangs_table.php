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
        Schema::create('kategori__barangs', function (Blueprint $table) {
            $table->string('id_kategori')->primary();
            $table->enum('nama_kategori',['Elektronik-&-Gadget','Pakaian-&-Aksesori','Perabotan-Rumah-Tangga','Buku,-Alat-Tulis,-&-Peralatan Sekolah','Hobi,-Mainan,-&-Koleksi','Perlengkapan-Bayi-&-Anak','Otomotif-&-Aksesori','Perlengkapan-Taman-&-Outdoor','Peralatan-Kantor-&-Industri','Kosmetik-&-Perawatan Diri']);
            $table->string('kub_kategori');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori__barangs');
    }
};

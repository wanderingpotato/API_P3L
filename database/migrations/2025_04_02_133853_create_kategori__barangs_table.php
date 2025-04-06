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
            $table->string('Id_kategori')->primary();
            $table->enum('Nama_Kategori',['Elektronik-&-Gadget','Pakaian-&-Aksesori','Perabotan-Rumah-Tangga','Buku,-Alat-Tulis,-&-Peralatan Sekolah','Hobi,-Mainan,-&-Koleksi','Perlengkapan-Bayi-&-Anak','Otomotif-&-Aksesori','Perlengkapan-Taman-&-Outdoor','Peralatan-Kantor-&-Industri','Kosmetik-&-Perawatan Diri']);
            $table->string('Sub_Kategori');
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

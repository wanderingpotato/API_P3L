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
        Schema::create('organisasis', function (Blueprint $table) {
            // $table->string('Id_organisasi')->primary();
            $table->id('id_organisasi');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('no_telp');
            $table->string('password');
            $table->text('alamat');
            $table->text('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisasis');
    }
};

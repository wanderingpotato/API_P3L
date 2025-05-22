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
        Schema::create('penitips', function (Blueprint $table) {
            // $table->string('Id_penitip')->primary();
            $table->id('id_penitip');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('no_telp');
            $table->string('password');
            $table->string('nik');
            $table->double('saldo');
            $table->double('poin');
            $table->double('rata_rating');
            $table->boolean('badge');
            $table->text('alamat');
            $table->text('foto')->nullable();
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
        Schema::dropIfExists('penitips');
    }
};

<?php

namespace Database\Seeders;

use App\Models\Alamat;
use App\Models\Detail_Donasi;
use App\Models\Detail_Pembelian;
use App\Models\Detail_Pendapatan;
use App\Models\Diskusi;
use App\Models\Donasi;
use App\Models\gallery;
use App\Models\Jabatan;
use App\Models\Kategori_Barang;
use App\Models\Klaim_Merchandise;
use App\Models\Komisi;
use App\Models\Merchandise;
use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Pembelian;
use App\Models\Penitip;
use App\Models\Penitipan_Barang;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Jabatan::factory(10)->create();
        Pegawai::factory(10)->create();
        Penitip::factory(10)->create();
        Pembeli::factory(10)->create();
        Kategori_Barang::factory(10)->create();
        Penitipan_Barang::factory(10)->create();
        Diskusi::factory(10)->create();
        Alamat::factory(10)->create();
        Merchandise::factory(10)->create();
        Klaim_Merchandise::factory(10)->create();
        Komisi::factory(10)->create();
        Detail_Pendapatan::factory(10)->create();
        Pembelian::factory(10)->create();
        Detail_Pembelian::factory(10)->create();
        Organisasi::factory(10)->create();
        Donasi::factory(10)->create();
        Detail_Donasi::factory(10)->create();
        gallery::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

    }
}

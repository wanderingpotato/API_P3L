<?php

namespace Database\Factories;

use App\Models\Kategori_Barang;
use App\Models\Pegawai;
use App\Models\Penitip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Penitipan_BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $KategoriId = Kategori_Barang::pluck('Id_kategori')->toArray();
        $PegawaiId = Pegawai::pluck('Id_pegawai')->toArray();
        $PenitipId = Penitip::pluck('Id_penitip')->toArray();
        return [
            'Id_barang' => fake()->unique()->numerify('B-####'),
            'Id_kategori' => fake()->randomElement($KategoriId),
            'Id_Penitip' => fake()->randomElement($PenitipId),
            'Id_Pegawai' => fake()->randomElement($PegawaiId),
            'Nama_Barang'=>fake()->unique()->word(),
            'Status' => fake()->randomElement(['DiJual','DiDonasikan','DiKembalikan','DiBeli']),
            'Harga_barang'=>fake()->randomFloat(2),
            'Rating'=>fake()->randomFloat(2, 1, 5),
            'Tanggal_penitipan'=> fake()->dateTime(),
            'Tanggal_kadaluarsa'=> fake()->dateTime(),
            'Batas_ambil'=> fake()->dateTime(),
            'Tanggal_laku'=> fake()->dateTime(),
            'Tanggal_rating'=> fake()->dateTime(),
            'Garansi'=> fake()->dateTime(),
            'DiPerpanjang'=>fake()->numberBetween(0, 1),
            'DiliverHere'=>fake()->numberBetween(0, 1),
            'Hunter'=>fake()->numberBetween(0, 1),
            'Foto_Barang'=>fake()->word() . '.png',
            // 'Deskripsi'=>fake()->text(),
            //
        ];
    }
}

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
            'id_barang' => fake()->unique()->numerify('B-####'),
            'id_kategori' => fake()->randomElement($KategoriId),
            'id_penitip' => fake()->randomElement($PenitipId),
            'id_pegawai' => fake()->randomElement($PegawaiId),
            'nama_barang' => fake()->unique()->word(),
            'status' => fake()->randomElement(['DiJual', 'DiDonasikan', 'DiKembalikan', 'DiBeli']),
            'harga_barang' => fake()->randomFloat(2),
            'rating' => fake()->randomFloat(2, 1, 5),
            'tanggal_penitipan' => fake()->dateTime(),
            'tanggal_kadaluarsa' => fake()->dateTime(),
            'batas_ambil' => fake()->dateTime(),
            'tanggal_laku' => fake()->dateTime(),
            'tanggal_rating' => fake()->dateTime(),
            'garansi' => fake()->dateTime(),
            'di_perpanjang' => fake()->numberBetween(0, 1),
            'diliver_here' => fake()->numberBetween(0, 1),
            'hunter' => fake()->numberBetween(0, 1),
            'deskripsi' => fake()->text(),
            //
        ];
    }
}

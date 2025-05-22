<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\Penitip;
use App\Models\Penitipan_Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Komisi>
 */
class KomisiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $BarangId = Penitipan_Barang::pluck('id_barang')->toArray();
        $PenitipId = Penitip::pluck('id_penitip')->toArray();
        $PegawaiId = Pegawai::pluck('id_pegawai')->toArray();
        if (fake()->randomDigitNotNull() % 2 == 0) {
            return [
                'id_komisi' => fake()->unique()->numerify('K-####'),
                'id_penitip' => fake()->randomElement($PenitipId),
                'id_barang' => fake()->randomElement($BarangId),
                'bonus_penitip'=>fake()->randomFloat(2),
                'komisi_penitip'=>fake()->randomFloat(2),
                'komisi_toko'=>fake()->randomFloat(2),
                'komisi_hunter'=>null,
                'tanggal_komisi'=> fake()->dateTime(),
                'id_pegawai' => null,
            ];
        } else {
            return [
                'id_komisi' => fake()->unique()->numerify('K-####'),
                'id_pegawai' => fake()->randomElement($PegawaiId),
                'id_barang' => fake()->randomElement($BarangId),
                'bonus_penitip'=>null,
                'komisi_penitip'=>fake()->randomFloat(2),
                'komisi_toko'=>fake()->randomFloat(2),
                'komisi_hunter'=>fake()->randomFloat(2),
                'tanggal_komisi'=> fake()->dateTime(),
                'id_penitip' => null,
            ];
        }
    }
}

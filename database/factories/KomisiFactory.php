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
        $BarangId = Penitipan_Barang::pluck('Id_barang')->toArray();
        $PenitipId = Penitip::pluck('Id_penitip')->toArray();
        $PegawaiId = Pegawai::pluck('Id_pegawai')->toArray();
        if (fake()->randomDigitNotNull() % 2 == 0) {
            return [
                'Id_komisi' => fake()->unique()->numerify('K-####'),
                'Id_penitip' => fake()->randomElement($PenitipId),
                'Id_barang' => fake()->randomElement($BarangId),
                'Bonus_Penitip'=>fake()->randomFloat(2),
                'Komisi_Penitip'=>fake()->randomFloat(2),
                'Komisi_Toko'=>fake()->randomFloat(2),
                'Komisi_Hunter'=>null,
                'Tanggal_Komisi'=> fake()->dateTime(),
                'Id_pegawai' => null,
            ];
        } else {
            return [
                'Id_komisi' => fake()->unique()->numerify('K-####'),
                'Id_pegawai' => fake()->randomElement($PegawaiId),
                'Id_barang' => fake()->randomElement($BarangId),
                'Bonus_Penitip'=>null,
                'Komisi_Penitip'=>fake()->randomFloat(2),
                'Komisi_Toko'=>fake()->randomFloat(2),
                'Komisi_Hunter'=>fake()->randomFloat(2),
                'Tanggal_Komisi'=> fake()->dateTime(),
                'Id_penitip' => null,
            ];
        }
    }
}

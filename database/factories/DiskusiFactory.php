<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Penitipan_Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Diskusi>
 */
class DiskusiFactory extends Factory
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
        $PembeliId = Pembeli::pluck('id_Pembeli')->toArray();
        if (fake()->numberBetween(1, 3) == 1){
            return [
                'id_diskusi' => fake()->unique()->numerify('DS-####'),
                'id_barang' => fake()->randomElement($BarangId),
                'id_pembeli' => null,
                'id_penitip' => null,
                'id_pegawai' => fake()->randomElement($PegawaiId),
                'title'=>fake()->unique()->sentence(),
                'deskripsi'=>fake()->text(),
                //
            ];
        }
        else if(fake()->numberBetween(1, 3) == 2){
            return [
                'id_diskusi' => fake()->unique()->numerify('DS-####'),
                'id_barang' => fake()->randomElement($BarangId),
                'id_pembeli' => fake()->randomElement($PembeliId),
                'id_penitip' => null,
                'id_pegawai' => null,
                'title'=>fake()->unique()->sentence(),
                'deskripsi'=>fake()->text(),
                //
            ];
        }else{
            return [
                'id_diskusi' => fake()->unique()->numerify('DS-####'),
                'id_barang' => fake()->randomElement($BarangId),
                'id_pembeli' => null,
                'id_penitip' => fake()->randomElement($PenitipId),
                'id_pegawai' => null,
                'title'=>fake()->unique()->sentence(),
                'deskripsi'=>fake()->text(),
                //
            ];
        }
    }
}

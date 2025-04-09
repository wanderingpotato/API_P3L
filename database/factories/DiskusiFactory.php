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
        $BarangId = Penitipan_Barang::pluck('Id_barang')->toArray();
        $PenitipId = Penitip::pluck('Id_penitip')->toArray();
        $PegawaiId = Pegawai::pluck('Id_pegawai')->toArray();
        $PembeliId = Pembeli::pluck('Id_Pembeli')->toArray();
        if (fake()->numberBetween(1, 3) == 1){
            return [
                'Id_diskusi' => fake()->unique()->numerify('DS-####'),
                'Id_barang' => fake()->randomElement($BarangId),
                'Id_Pembeli' => null,
                'Id_Penitip' => null,
                'Id_Pegawai' => fake()->randomElement($PegawaiId),
                'Title'=>fake()->unique()->sentence(),
                'Deskripsi'=>fake()->text(),
                //
            ];
        }
        else if(fake()->numberBetween(1, 3) == 2){
            return [
                'Id_diskusi' => fake()->unique()->numerify('DS-####'),
                'Id_barang' => fake()->randomElement($BarangId),
                'Id_Pembeli' => fake()->randomElement($PembeliId),
                'Id_Penitip' => null,
                'Id_Pegawai' => null,
                'Title'=>fake()->unique()->sentence(),
                'Deskripsi'=>fake()->text(),
                //
            ];
        }else{
            return [
                'Id_diskusi' => fake()->unique()->numerify('DS-####'),
                'Id_barang' => fake()->randomElement($BarangId),
                'Id_Pembeli' => null,
                'Id_Penitip' => fake()->randomElement($PenitipId),
                'Id_Pegawai' => null,
                'Title'=>fake()->unique()->sentence(),
                'Deskripsi'=>fake()->text(),
                //
            ];
        }
    }
}

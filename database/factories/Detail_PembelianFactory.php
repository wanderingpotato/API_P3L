<?php

namespace Database\Factories;

use App\Models\Pembelian;
use App\Models\Penitip;
use App\Models\Penitipan_Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Detail_PembelianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $BarangId = Penitipan_Barang::pluck('Id_barang')->toArray();
        $PembelianId = Pembelian::pluck('Id_pembelian')->toArray();
        return [
            'Id_barang' => fake()->randomElement($BarangId),
            'Id_pembelian' => fake()->randomElement($PembelianId),
            'Id_penitip' => function (array $attributes) {
                return Penitipan_Barang::find($attributes['Id_barang'])->Id_Penitip;
            },
            //
        ];
    }
}

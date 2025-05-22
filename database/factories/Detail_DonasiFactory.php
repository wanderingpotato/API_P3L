<?php

namespace Database\Factories;

use App\Models\Donasi;
use App\Models\Penitip;
use App\Models\Penitipan_Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Detail_DonasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $BarangId = Penitipan_Barang::pluck('id_barang')->toArray();
        $DonasiId = Donasi::pluck('id_donasi')->toArray();
        return [
            'id_barang' => fake()->randomElement($BarangId),
            'id_donasi' => fake()->randomElement($DonasiId),
            'id_penitip' => function (array $attributes) {
                return Penitipan_Barang::find($attributes['id_barang'])->id_penitip;
            },
            //
        ];
    }
}

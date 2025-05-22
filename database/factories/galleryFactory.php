<?php

namespace Database\Factories;

use App\Models\Penitipan_Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class galleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $BarangId = Penitipan_Barang::pluck('id_barang')->toArray();
        return [
            //
            'id_gallery' => fake()->unique()->numerify('GL-####'),
            'title' => fake()->word(),
            'foto'=>fake()->word() . '.png',
            'id_barang' => fake()->randomElement($BarangId),
        ];
    }
}

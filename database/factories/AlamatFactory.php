<?php

namespace Database\Factories;

use App\Models\Pembeli;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alamat>
 */
class AlamatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $PembeliId = Pembeli::pluck('id_Pembeli')->toArray();
        return [
            'id_alamat' => fake()->unique()->numerify('A-####'),
            'id_pembeli' => fake()->randomElement($PembeliId),
            'title' => 'Rumah' . fake()->unique()->name(),
            'no_telp' => fake()->numerify('08##########'),
            'deskripsi' => fake()->text(),
            'default' => fake()->numberBetween(0, 1),
            'alamat' => fake()->address(),
            //
        ];
    }
}

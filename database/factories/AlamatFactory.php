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
        $PembeliId = Pembeli::pluck('Id_Pembeli')->toArray();
        return [
            'Id_alamat' => fake()->unique()->numerify('A-####'),
            'Id_Pembeli' => fake()->randomElement($PembeliId),
            'Title'=>'Rumah' . fake()->unique()->name(),
            'NoTelp'=> fake()->numerify('08##########'),
            'Deskripsi'=>fake()->text(),
            'Default'=>fake()->numberBetween(0, 1),
            'Alamat'=>fake()->address(),
            //
        ];
    }
}

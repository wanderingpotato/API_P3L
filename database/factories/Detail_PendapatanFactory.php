<?php

namespace Database\Factories;

use App\Models\Penitip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Detail_PendapatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $PenitipId = Penitip::pluck('id_penitip')->toArray();
        return [
            'id_detail_pendapatan' => fake()->unique()->numerify('DP-####'),
            'id_penitip' => fake()->randomElement($PenitipId),
            'month'=>fake()->unique()->date(),
            'total'=>fake()->randomFloat(2),
            'bonus_pendapatan'=>fake()->randomFloat(2),
        ];
    }
}

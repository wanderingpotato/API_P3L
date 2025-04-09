<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Merchandise>
 */
class MerchandiseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Id_merchandise' => fake()->unique()->numerify('M-####'),
            'Kategori' => fake()->randomElement(['Ballpoin','Stiker','Mug','Topi','Tumblr','T-shirt','Jam-Dinding','Tas-Travel','Payung']),
            'Nama'=>fake()->unique()->word(),
            'Poin'=>fake()->randomNumber(3, true),
            'Stock'=>fake()->randomNumber(2, true),
        ];
    }
}

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
            'id_merchandise' => fake()->unique()->numerify('M-####'),
            'kategori' => fake()->randomElement(['Ballpoin', 'Stiker', 'Mug', 'Topi', 'Tumblr', 'T-shirt', 'Jam-Dinding', 'Tas-Travel', 'Payung']),
            'nama' => fake()->unique()->word(),
            'poin' => fake()->randomNumber(3, true),
            'stock' => fake()->randomNumber(2, true),
        ];
    }
}

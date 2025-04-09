<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jabatan>
 */
class JabatanFactory extends Factory
{
    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'Id_jabatan' => 'J' . fake()->numberBetween(1, 100),
            'Id_jabatan' => fake()->unique()->numerify('J-####'),
            'nama_jabatan' => fake()->unique()->jobTitle(),
        ];
    }
    
}

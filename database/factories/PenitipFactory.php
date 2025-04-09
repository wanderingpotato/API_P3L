<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penitip>
 */
class PenitipFactory extends Factory
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
            // 'Id_penitip' => 'P' . fake()->numberBetween(1, 100),
            // 'Id_penitip' => fake()->numerify('P-####'),,
            'noTelp'=> fake()->numerify('08##########'),
            'username' => fake()->unique()->firstName(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'saldo'=>fake()->randomFloat(2),
            'poin'=>fake()->randomNumber(3, true),
            'RataRating'=>fake()->randomFloat(2, 1, 5),
            'Badge'=>fake()->numberBetween(0, 1),
            'Alamat'=>fake()->address(),
            'foto'=>fake()->word() . '.png',
            'remember_token' => Str::random(10),
        ];
    }
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

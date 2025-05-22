<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembeli>
 */
class PembeliFactory extends Factory
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
            // 'Id_Pembeli' => 'P' . fake()->numberBetween(1, 100),
            // 'Id_Pembeli' => fake()->numerify('P-####'),,
            'no_telp' => fake()->numerify('08##########'),
            'username' => fake()->unique()->firstName(),
            'poin' => fake()->randomNumber(3, true),
            'foto' => fake()->word() . '.png',
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

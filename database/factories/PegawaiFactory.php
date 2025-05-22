<?php

namespace Database\Factories;

use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jabatanId = Jabatan::pluck('Id_jabatan')->toArray();
        return [
            // 'Id_pegawai' => 'P' . fake()->numberBetween(1, 100),
            // 'Id_pegawai' =>  fake()->numerify('P-####'),
            'id_jabatan' => fake()->randomElement($jabatanId),
            'no_telp' => fake()->numerify('08##########'),
            'username' => fake()->unique()->firstName(),
            'tanggal_lahir' => fake()->date(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'foto' => fake()->word() . '.png',
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

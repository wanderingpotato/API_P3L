<?php

namespace Database\Factories;

use App\Models\Organisasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donasi>
 */
class DonasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $OrganisasiId = Organisasi::pluck('id_organisasi')->toArray();
        return [
            'id_donasi' => fake()->unique()->numerify('D-####'),
            'id_organisasi' => fake()->randomElement($OrganisasiId),
            'nama_penerima' => fake()->unique()->name(),
            'tanggal_diberikan' => fake()->dateTime(),
            'tanggal_request' => fake()->dateTime(),
            'deskripsi' => fake()->text(),
            'konfirmasi' => fake()->numberBetween(0, 1),
            //
        ];
    }
}

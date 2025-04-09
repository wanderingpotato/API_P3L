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
        $OrganisasiId = Organisasi::pluck('Id_organisasi')->toArray();
        return [
            'Id_donasi' => fake()->unique()->numerify('D-####'),
            'Id_organisasi' => fake()->randomElement($OrganisasiId),
            'Nama_Penerima'=>fake()->unique()->name(),
            'Tanggal_diberikan'=> fake()->dateTime(),
            'Tanggal_request'=> fake()->dateTime(),
            'Deskripsi'=>fake()->text(),
            'Konfirmasi'=>fake()->numberBetween(0, 1),
            //
        ];
    }
}

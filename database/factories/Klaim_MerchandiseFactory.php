<?php

namespace Database\Factories;

use App\Models\Merchandise;
use App\Models\Pembeli;
use App\Models\Penitip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Klaim_MerchandiseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $MerchandiseId = Merchandise::pluck('id_merchandise')->toArray();
        $PenitipId = Penitip::pluck('id_penitip')->toArray();
        $PembeliId = Pembeli::pluck('id_Pembeli')->toArray();
        if (fake()->randomDigitNotNull() % 2 == 0) {
            return [
                'id_klaim' => fake()->unique()->numerify('KM-####'),
                'id_penitip' => fake()->randomElement($PenitipId),
                'id_merchandise' => fake()->randomElement($MerchandiseId),
                'jumlah'=>fake()->randomNumber(3, false),
                'tanggal_ambil'=> fake()->dateTime(),
                'status' => fake()->randomElement(['On-Progress','Claimed','Canceled']),
                'id_pembeli' => null,
            ];
        } else {
            return [
                'id_klaim' => fake()->unique()->numerify('KM-####'),
                'id_pembeli' => fake()->randomElement($PembeliId),
                'id_merchandise' => fake()->randomElement($MerchandiseId),
                'jumlah'=>fake()->randomNumber(3, false),
                'tanggal_ambil'=> fake()->dateTime(),
                'status' => fake()->randomElement(['On-Progress','Claimed','Canceled']),
                'id_penitip' => null,
            ];
        }
    }
}

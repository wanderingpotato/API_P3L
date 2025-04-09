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
        $MerchandiseId = Merchandise::pluck('Id_merchandise')->toArray();
        $PenitipId = Penitip::pluck('Id_penitip')->toArray();
        $PembeliId = Pembeli::pluck('Id_Pembeli')->toArray();
        if (fake()->randomDigitNotNull() % 2 == 0) {
            return [
                'Id_klaim' => fake()->unique()->numerify('KM-####'),
                'Id_penitip' => fake()->randomElement($PenitipId),
                'Id_merchandise' => fake()->randomElement($MerchandiseId),
                'Jumlah'=>fake()->randomNumber(3, false),
                'Tanggal_ambil'=> fake()->dateTime(),
                'Status' => fake()->randomElement(['On-Progress','Claimed','Canceled']),
                'Id_Pembeli' => null,
            ];
        } else {
            return [
                'Id_klaim' => fake()->unique()->numerify('KM-####'),
                'Id_Pembeli' => fake()->randomElement($PembeliId),
                'Id_merchandise' => fake()->randomElement($MerchandiseId),
                'Jumlah'=>fake()->randomNumber(3, false),
                'Tanggal_ambil'=> fake()->dateTime(),
                'Status' => fake()->randomElement(['On-Progress','Claimed','Canceled']),
                'Id_penitip' => null,
            ];
        }
    }
}

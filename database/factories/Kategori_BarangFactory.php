<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Kategori_BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Id_kategori' => fake()->unique()->numerify('K-####'),
            'Nama_Kategori' => fake()->unique()->randomElement(['Elektronik-&-Gadget','Pakaian-&-Aksesori','Perabotan-Rumah-Tangga','Buku,-Alat-Tulis,-&-Peralatan Sekolah','Hobi,-Mainan,-&-Koleksi','Perlengkapan-Bayi-&-Anak','Otomotif-&-Aksesori','Perlengkapan-Taman-&-Outdoor','Peralatan-Kantor-&-Industri','Kosmetik-&-Perawatan Diri']),
            'Sub_Kategori'=>fake()->unique()->word(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Alamat;
use App\Models\Pegawai;
use App\Models\Pembeli;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembelian>
 */
class PembelianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $AlamatId = Alamat::pluck('id_alamat')->toArray();
        $PegawaiId = Pegawai::pluck('id_pegawai')->toArray();
        $PembeliId = Pembeli::pluck('id_pembeli')->toArray();
        if (fake()->randomDigitNotNull() % 2 == 0) {
            return [
                'id_pembelian' => fake()->unique()->numerify('PM-####'),
                'id_alamat' => fake()->randomElement($AlamatId),
                'id_pembeli' => fake()->randomElement($PembeliId),
                'id_pegawai' => fake()->randomElement($PegawaiId),
                'status' => fake()->randomElement(['Proses', 'Batal', 'Selesai']),
                'status_pengiriman' => fake()->randomElement(['DiProses', 'Pegiriman', 'Sampai']),
                'harga_barang' => fake()->randomFloat(2),
                'ongkir' => fake()->randomFloat(2),
                'potongan_harga' => fake()->randomFloat(2),
                'point_yg_didapat' => fake()->randomNumber(3, true),
                'point_current' => fake()->randomNumber(3, true),
                'point_digunakan' => fake()->randomNumber(3, true),
                'batas_waktu' => fake()->dateTime(),
                'tanggal_pembelian' => fake()->dateTime(),
                'tanggal_lunas' => fake()->dateTime(),
                'tanggal_pengiriman-pengambilan' => fake()->dateTime(),
                'dilivery' => fake()->numberBetween(0, 1),
                'bukti_pembayaran' => fake()->word() . '.png',
                //
            ];
        } else {
            return [
                'id_pembelian' => fake()->unique()->numerify('PM-####'),
                'id_alamat' => fake()->randomElement($AlamatId),
                'Id_Pembeli' => fake()->randomElement($PembeliId),
                'id_pegawai' => null,
                'status' => fake()->randomElement(['Proses', 'Batal', 'Selesai']),
                'status_pengiriman' => fake()->randomElement(['DiProses', 'Pegiriman', 'Sampai']),
                'harga_barang' => fake()->randomFloat(2),
                'ongkir' => fake()->randomFloat(2),
                'potongan_harga' => fake()->randomFloat(2),
                'point_yg_didapat' => fake()->randomNumber(3, true),
                'point_current' => fake()->randomNumber(3, true),
                'point_digunakan' => fake()->randomNumber(3, true),
                'batas_waktu' => fake()->dateTime(),
                'tanggal_pembelian' => fake()->dateTime(),
                'tanggal_lunas' => fake()->dateTime(),
                'tanggal_pengiriman-pengambilan' => fake()->dateTime(),
                'dilivery' => fake()->numberBetween(0, 1),
                'bukti_pembayaran' => fake()->word() . '.png',
                //
            ];
        }
    }
}

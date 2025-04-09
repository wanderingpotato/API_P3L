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
        $AlamatId = Alamat::pluck('Id_alamat')->toArray();
        $PegawaiId = Pegawai::pluck('Id_pegawai')->toArray();
        $PembeliId = Pembeli::pluck('Id_Pembeli')->toArray();
        if (fake()->randomDigitNotNull() % 2 == 0){
            return [
                'Id_pembelian' => fake()->unique()->numerify('PM-####'),
                'Id_alamat' => fake()->randomElement($AlamatId),
                'Id_Pembeli' => fake()->randomElement($PembeliId),
                'Id_Pegawai' => fake()->randomElement($PegawaiId),
                'Status' => fake()->randomElement(['Proses','Batal','Selesai']),
                'Status_Pengiriman' => fake()->randomElement(['DiProses','Pegiriman','Sampai']),
                'Harga_barang'=>fake()->randomFloat(2),
                'Ongkir'=>fake()->randomFloat(2),
                'Potongan_Harga'=>fake()->randomFloat(2),
                'PointYgDidapat'=>fake()->randomNumber(3, true),
                'PointCurrent'=>fake()->randomNumber(3, true),
                'PointDigunakan'=>fake()->randomNumber(3, true),
                'Batas_Waktu'=> fake()->dateTime(),
                'Tanggal_Pembelian'=> fake()->dateTime(),
                'Tanggal_Lunas'=> fake()->dateTime(),
                'Tanggal_Pengiriman-Pengambilan'=> fake()->dateTime(),
                'Dilivery'=>fake()->numberBetween(0, 1),
                'Bukti_Pembayaran'=>fake()->word() . '.png',
                //
            ];
        }else{
            return [
                'Id_pembelian' => fake()->unique()->numerify('PM-####'),
                'Id_alamat' => fake()->randomElement($AlamatId),
                'Id_Pembeli' => fake()->randomElement($PembeliId),
                'Id_Pegawai' => null,
                'Status' => fake()->randomElement(['Proses','Batal','Selesai']),
                'Status_Pengiriman' => fake()->randomElement(['DiProses','Pegiriman','Sampai']),
                'Harga_barang'=>fake()->randomFloat(2),
                'Ongkir'=>fake()->randomFloat(2),
                'Potongan_Harga'=>fake()->randomFloat(2),
                'PointYgDidapat'=>fake()->randomNumber(3, true),
                'PointCurrent'=>fake()->randomNumber(3, true),
                'PointDigunakan'=>fake()->randomNumber(3, true),
                'Batas_Waktu'=> fake()->dateTime(),
                'Tanggal_Pembelian'=> fake()->dateTime(),
                'Tanggal_Lunas'=> fake()->dateTime(),
                'Tanggal_Pengiriman-Pengambilan'=> fake()->dateTime(),
                'Dilivery'=>fake()->numberBetween(0, 1),
                'Bukti_Pembayaran'=>fake()->word() . '.png',
                //
            ];
        }
    }
}

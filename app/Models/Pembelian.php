<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    ////
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $timestamps = false;
    protected $primaryKey = 'Id_pembelian';
    protected $fillable = [
        'Id_pembelian',
        'Id_alamat',
        'Id_Pembeli',
        'Id_Pegawai',
        'Dilivery',
        'Status',
        'Status_Pengiriman',
        'PointYgDidapat',
        'PointCurrent',
        'PointDigunakan',
        'Potongan_Harga',
        'Harga_Barang',
        'Ongkir',
        'Batas_Waktu',
        'Tanggal_Pembelian',
        'Tanggal_Lunas',
        'Tanggal_Pengiriman-Pengambilan',
        'Bukti_Pembayaran',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    // Kurang relationship

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_pembelian' => 'string',
        ];
    }
}

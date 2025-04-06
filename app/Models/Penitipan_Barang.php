<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penitipan_Barang extends Model
{
    ////
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $timestamps = false;
    protected $primaryKey = 'Id_barang';
    protected $fillable = [
        'Id_barang',
        'Id_kategori',
        'Id_Penitip',
        'Id_Pegawai',
        'Nama_Barang',
        'DiPerpanjang',
        'DiliverHere',
        'Hunter',
        'Status',
        'Harga_barang',
        'Rating',
        'Tanggal_penitipan',
        'Tanggal_kadaluarsa',
        'Batas_ambil',
        'Tanggal_laku',
        'Tanggal_rating',
        'Garansi',
        'Foto_Barang',
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

    //kurang relationship

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_barang' => 'string',
        ];
    }
}

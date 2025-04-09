<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    ////
    use HasFactory;
    public $incrementing=false;
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

    // Kurang relationships
    public function Pembelian()
    {
        return $this->belongsToMany(
            Penitipan_Barang::class,
            'Detail_Pembelian',
            'Id_pembelian',
            'Id_barang' 
            
        );
    }
    public function Alamat()
    {
        return $this->belongsTo(Alamat::class, 'Id_alamat');
    }
    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'Id_Pegawai');
    }
    public function Pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'Id_Pembeli');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_pembelian' => 'string',
            'Dilivery' => 'boolean',
            
        ];
    }
}

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
    public $table = "pembelians";
    public $timestamps = false;
    protected $primaryKey = 'id_pembelian';
    protected $fillable = [
        'id_pembelian',
        'id_alamat',
        'id_pembeli',
        'id_pegawai',
        'dilivery',
        'status',
        'status_pengiriman',
        'point_yg_didapat',
        'point_current',
        'point_digunakan',
        'potongan_harga',
        'harga_barang',
        'ongkir',
        'batas_waktu',
        'tanggal_pembelian',
        'tanggal_lunas',
        'tanggal_pengiriman-pengambilan',
        'batas_pembeli_ambil_barang',
        'bukti_pembayaran',
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
    public function detail__pembelians()
    {
        return $this->belongsToMany(
            Penitipan_Barang::class,
            'detail__pembelians',
            'id_pembelian',
            'id_barang' 
            
        );
    }
    public function Alamat()
    {
        return $this->belongsTo(Alamat::class, 'id_alamat');
    }
    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
    public function Pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_pembelian' => 'string',
            'dilivery' => 'boolean',
            
        ];
    }
}

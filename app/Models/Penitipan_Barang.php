<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penitipan_Barang extends Model
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
    protected $primaryKey = 'id_barang';
    protected $fillable = [
        'id_barang',
        'id_kategori',
        'id_penitip',
        'id_pegawai',
        'nama_barang',
        'di_perpanjang',
        'diliver_here',
        'hunter',
        'status',
        'harga_barang',
        'rating',
        'tanggal_penitipan',
        'tanggal_kadaluarsa',
        'batas_ambil',
        'tanggal_laku',
        'tanggal_rating',
        'garansi',
        'deskripsi',
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

    // kurang relationship
    public function Pembelian()
    {
        return $this->belongsToMany(
            Pembelian::class,
            'Detail_Pembelian',
            'id_barang',
            'id_pembelian',
        );
    }
    public function Donasi()
    {
        return $this->belongsToMany(
            Donasi::class,
            'Detail_Donasi',
            'id_barang' ,
            'id_donasi'
        );
    }
    public function Kategori_Barang()
    {
        return $this->belongsTo(Kategori_Barang::class, 'id_kategori');
    }
    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
    public function Gallery()
    {
        return $this->hasMany(Penitip::class, 'id_barang');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_barang' => 'string',
            'di_perpanjang' => 'boolean',
            'diliver_here' => 'boolean',
            'hunter' => 'boolean',
        ];
    }
}

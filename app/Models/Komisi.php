<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
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
    protected $primaryKey = 'Id_komisi';
    protected $fillable = [
        'Id_komisi',
        'Id_barang',
        'Id_pegawai',
        'Id_penitip',
        'Bonus_Penitip',
        'Komisi_Penitip',
        'Komisi_Toko',
        'Komisi_Hunter',
        'Tanggal_Komisi',
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

    //relationships
    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'Id_penitip');
    }
    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'Id_pegawai');
    }
    public function Barang()
    {
        return $this->belongsTo(Penitipan_Barang::class, 'Id_barang');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_komisi' => 'string',
        ];
    }
}

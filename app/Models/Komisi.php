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
    public $table = "komisis";
    public $timestamps = false;
    protected $primaryKey = 'id_komisi';
    protected $fillable = [
        'id_komisi',
        'id_barang',
        'id_pegawai',
        'id_penitip',
        'bonus_penitip',
        'komisi_penitip',
        'komisi_toko',
        'komisi_hunter',
        'tanggal_komisi',
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
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
    public function Barang()
    {
        return $this->belongsTo(Penitipan_Barang::class, 'id_barang');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_komisi' => 'string',
        ];
    }
}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskusi extends Model
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
    protected $primaryKey = 'Id_diskusi';
    protected $fillable = [
        'Id_diskusi',
        'Id_Pembeli',
        'Id_Penitip',
        'Id_Pegawai',
        'Id_Barang',
        'Title',
        'Deskripsi',
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
    public function Pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'Id_Pembeli');
    }
    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'Id_Penitip');
    }
    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'Id_Pegawai');
    }
    public function Barang()
    {
        return $this->belongsTo(Penitipan_Barang::class, 'Id_Barang');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_diskusi' => 'string',
        ];
    }
}

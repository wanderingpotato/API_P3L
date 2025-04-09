<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klaim_Merchandise extends Model
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
    protected $primaryKey = 'Id_klaim';
    protected $fillable = [
        'Id_klaim',
        'Id_merchandise',
        'Id_Pembeli',
        'Id_penitip',
        'Jumlah',
        'Tanggal_ambil',
        'Status',
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

    //relationship
    public function Pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'Id_Pembeli');
    }
    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'Id_penitip');
    }
    public function Merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'Id_merchandise');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_klaim' => 'string',
        ];
    }
}

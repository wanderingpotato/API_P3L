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
    public $table = "klaim__merchandises";
    public $timestamps = false;
    protected $primaryKey = 'id_klaim';
    protected $fillable = [
        'id_klaim',
        'id_merchandise',
        'id_pembeli',
        'id_penitip',
        'jumlah',
        'tanggal_klaim', 
        'tanggal_ambil',
        'status',
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
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
    public function Merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'id_merchandise');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_klaim' => 'string',
        ];
    }
}

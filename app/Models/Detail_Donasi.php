<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Donasi extends Model
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
    protected $fillable = [
        'id_donasi',
        'id_barang',
        'id_penitip',
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
    public function Barang()
    {
        return $this->belongsTo(Penitipan_Barang::class, 'id_barang');
    }
    public function Donasi()
    {
        return $this->belongsTo(Donasi::class, 'id_donasi');
    }
    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }
}

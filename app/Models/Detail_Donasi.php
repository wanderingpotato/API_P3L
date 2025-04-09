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
        'Id_donasi',
        'Id_barang',
        'Id_penitip',
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
        return $this->belongsTo(Penitipan_Barang::class, 'Id_barang');
    }
    public function Donasi()
    {
        return $this->belongsTo(Donasi::class, 'Id_donasi');
    }
    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'Id_penitip');
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

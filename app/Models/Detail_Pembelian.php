<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Pembelian extends Model
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
        'Id_pembelian',
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

    //relationship
    public function Barang()
    {
        return $this->belongsTo(Penitipan_Barang::class, 'Id_barang');
    }
    public function Pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'Id_pembelian');
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

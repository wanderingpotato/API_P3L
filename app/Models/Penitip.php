<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Penitip extends Authenticatable
{
    ////
    use HasFactory,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'Penitips';
    public $timestamps = false;
    protected $primaryKey = 'Id_penitip';
    protected $fillable = [
        'Id_penitip',
        'name',
        'noTelp',
        'username',
        'email',
        'password',
        'Saldo',
        'Poin',
        'RataRating',
        'Badge',
        'Alamat',
        'foto',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //Relationship
    public function Diskusi()
    {
        return $this->hasMany(Diskusi::class,'Id_penitip');
    }
    public function Komisi()
    {
        return $this->hasMany(Komisi::class,'Id_penitip');
    }
    public function Penitipan()
    {
        return $this->hasMany(Penitipan_Barang::class,'Id_penitip');
    }
    public function Klaim()
    {
        return $this->hasMany(Klaim_Merchandise::class,'Id_penitip');
    }
    public function Detail_Pembelian()
    {
        return $this->hasMany(Detail_Pembelian::class,'Id_penitip');
    }
    public function Detail_Donasi()
    {
        return $this->hasMany(Detail_Donasi::class,'Id_penitip');
    }
    public function Detail_Pendapatan()
    {
        return $this->hasMany(Detail_Pendapatan::class,'Id_penitip');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_penitip' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'Badge' => 'boolean'
        ];
    }
}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pembeli extends Authenticatable
{
    ////
    use HasFactory,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'pembelis';
    public $timestamps = false;
    protected $primaryKey = 'id_pembeli';
    protected $fillable = [
        'id_pembeli',
        'name',
        'username',
        'email',
        'no_telp',
        'password',
        'poin',
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
        return $this->hasMany(Diskusi::class,'id_pembeli');
    }
    public function Alamat()
    {
        return $this->hasMany(Alamat::class,'id_pembeli');
    }
    public function Klaim()
    {
        return $this->hasMany(Klaim_Merchandise::class,'id_pembeli');
    }
    public function Pembelian()
    {
        return $this->hasMany(Pembelian::class,'id_pembeli');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_pembeli' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

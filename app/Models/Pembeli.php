<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    ////
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $timestamps = false;
    protected $primaryKey = 'Id_Pembeli';
    protected $fillable = [
        'Id_Pembeli',
        'name',
        'username',
        'email',
        'noTelp',
        'password',
        'Poin',
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
        return $this->hasMany(Diskusi::class,'Id_Pembeli');
    }
    public function Alamat()
    {
        return $this->hasMany(Alamat::class,'Id_Pembeli');
    }
    public function Klaim()
    {
        return $this->hasMany(Klaim_Merchandise::class,'Id_Pembeli');
    }
    public function Pembelian()
    {
        return $this->hasMany(Pembelian::class,'Id_Pembeli');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_Pembeli' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

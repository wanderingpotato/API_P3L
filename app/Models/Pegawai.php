<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pegawai extends Authenticatable
{
    ////
    use HasFactory,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'pegawais';
    public $timestamps = false;
    protected $primaryKey = 'id_pegawai';
    protected $fillable = [
        'id_pegawai',
        'id_jabatan',
        'name',
        'username',
        'email',
        'no_telp',
        'password',
        'foto',
        'tanggal_lahir',
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

    //relationship
    public function Diskusi()
    {
        return $this->hasMany(Diskusi::class,'id_pegawai');
    }
    public function Komisi()
    {
        return $this->hasMany(Komisi::class,'id_pegawai');
    }
    public function Penitipan()
    {
        return $this->hasMany(Penitipan_Barang::class,'id_pegawai');
    }
    public function Pembelian()
    {
        return $this->hasMany(Pembelian::class,'id_pegawai');
    }
    public function Jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_pegawai' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

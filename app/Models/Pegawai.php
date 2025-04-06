<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    ////
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $timestamps = false;
    protected $primaryKey = 'Id_pegawai';
    protected $fillable = [
        'Id_pegawai',
        'Id_jabatan',
        'name',
        'username',
        'email',
        'noTelp',
        'password',
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

    //relationship
    public function Diskusi()
    {
        return $this->hasMany(Diskusi::class,'Id_pegawai');
    }
    public function Komisi()
    {
        return $this->hasMany(Komisi::class,'Id_pegawai');
    }
    public function Penitipan()
    {
        return $this->hasMany(Penitipan_Barang::class,'Id_pegawai');
    }
    public function Pembelian()
    {
        return $this->hasMany(Pembelian::class,'Id_pegawai');
    }
    public function Jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'Id_jabatan');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_pegawai' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

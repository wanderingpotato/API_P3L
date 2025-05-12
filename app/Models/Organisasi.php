<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Organisasi extends Authenticatable
{
    ////
    use HasFactory,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'Organisasis';
    public $timestamps = false;
    protected $primaryKey = 'Id_organisasi';
    protected $fillable = [
        'Id_organisasi',
        'name',
        'username',
        'noTelp',
        'Alamat',
        'email',
        'password',
        'Deskripsi',
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
    public function Donasi()
    {
        return $this->hasMany(Donasi::class,'Id_organisasi');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_organisasi' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    ////
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $timestamps = false;
    protected $primaryKey = 'Id_alamat';
    protected $fillable = [
        'Id_alamat',
        'Id_Pembeli',
        'NoTelp',
        'Title',
        'Default',
        'Deskripsi',
        'Alamat',
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
        return $this->belongsTo(Pembeli::class, 'Id_Pembeli');
    }
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_alamat' => 'string',
        ];
    }
}

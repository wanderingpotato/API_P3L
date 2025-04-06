<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    ////
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $timestamps = false;
    protected $primaryKey = 'Id_merchandise';
    protected $fillable = [
        'Id_merchandise',
        'Nama',
        'Poin',
        'Kategori',
        'Stock',
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
    public function Klaim()
    {
        return $this->hasMany(Klaim_Merchandise::class,'Id_merchandise');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Id_merchandise' => 'string',
        ];
    }
}

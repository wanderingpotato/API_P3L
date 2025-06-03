<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    ////
    use HasFactory;
    public $incrementing=false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $table = "merchandises";
    public $timestamps = false;
    protected $primaryKey = 'id_merchandise';
    protected $fillable = [
        'id_merchandise',
        'nama',
        'poin',
        'kategori',
        'stock',
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
        return $this->hasMany(Klaim_Merchandise::class,'id_merchandise');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_merchandise' => 'string',
        ];
    }
}

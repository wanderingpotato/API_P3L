<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gallery extends Model
{
    //
    use HasFactory;
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $table = "galleries";
    public $timestamps = false;
    protected $primaryKey = 'id_gallery';
    protected $fillable = [
        'id_gallery',
        // 'title',
        'foto',
        'id_barang',
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
    public function Penitipan_Barang()
    {
        return $this->belongsTo(Penitipan_Barang::class, 'id_barang');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_gallery' => 'string',
        ];
    }
}

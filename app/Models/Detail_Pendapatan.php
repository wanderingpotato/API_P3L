<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Pendapatan extends Model
{
    ////
    use HasFactory;
    public $incrementing=false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $table = "detail__pendapatans";
    public $timestamps = false;
    protected $primaryKey = 'id_detail_pendapatan';
    protected $fillable = [
        'id_detail_pendapatan',
        'id_penitip',
        'total',
        'month',
        'bonus_pendapatan',
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

    //relationships
    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id_detail_pendapatan' => 'string',
        ];
    }
}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
   //
   use HasFactory;
   public $incrementing=false;
   /**
    * The attributes that are mass assignable.
    *
    * @var list<string>
    */
    public $timestamps = false;
    protected $primaryKey = 'id_donasi';
   protected $fillable = [
       'id_donasi',
       'id_organisasi',
       'nama_penerima',
       'konfirmasi',
       'tanggal_diberikan',
       'tanggal_request',
       'deskripsi',
   ];

   /**
    * The attributes that should be hidden for serialization.
    *
    * @var list<string>
    */
    //protected $hidden = [
    // 'password',
    // 'remember_token',
    //];

   //relationships
    public function Organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
    public function Detail_Donasi()
    {
        return $this->belongsToMany(
            Penitipan_Barang::class,
            'detail__donasis',
            'id_donasi',
            'id_barang' 
            
        );
    }
   /**
    * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
   protected function casts(): array
   {
       return [
           'id_donasi' => 'string',
           'konfirmasi' => 'boolean',
       ];
   }
}

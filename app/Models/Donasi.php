<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
   //
   use HasFactory;

   /**
    * The attributes that are mass assignable.
    *
    * @var list<string>
    */
    public $timestamps = false;
    protected $primaryKey = 'Id_donasi';
   protected $fillable = [
       'Id_donasi',
       'Id_organisasi',
       'Nama_Penerima',
       'Konfirmasi',
       'Tanggal_diberikan',
       'Tanggal_request',
       'Deskripsi',
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
        return $this->belongsTo(Organisasi::class, 'Id_organisasi');
    }
    public function Donasi()
    {
        return $this->belongsToMany(
            Penitipan_Barang::class,
            'Detail_Donasi',
            'Id_donasi',
            'Id_barang' 
            
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
           'Id_donasi' => 'string',
       ];
   }
}

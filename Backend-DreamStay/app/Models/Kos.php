<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
   use HasFactory;

   protected $fillable = [
      'id',
      'type',
      'biaya',
      'termasuk_listrik',
      'fasilitas_kamar',
      'fasilitas_kamar_mandi',
      'fasilitas_dapur',
      'lantai',
      'lebar',
      'panjang',
      'foto_kamar',
      'foto_kamar1',
      'foto_kamar_mandi',
      'foto_dapur',
      'whatsapp_owner',
      'user_id',
      'book_id',
   ];


   public function user()
   {
      return $this->hasOne(User::class);
   }

   public function rule()
   {
      return $this->belongsToMany(Rule::class, 'kos_rules');
   }

   public function detail()
   {
      return $this->belongsToMany(Detail::class, 'kos_details');
   }
}
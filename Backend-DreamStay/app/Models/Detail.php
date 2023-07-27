<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kos_detail;

class Detail extends Model
{
   use HasFactory;

   protected $fillable = [
      'id',
      'body',
      'created_at',
      'updated_at',
   ];


   public function kos_detail()
   {
      return $this->belongsToMany(Kos::class, 'kos_details');
   }
}
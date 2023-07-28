<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
   use HasFactory;


   protected $fillable = ['id', 'month', 'created_at', 'updated_at'];
   
   
   public function kos() 
   {
      return $this->belongsToMany(Kos::class, 'kos_id');
   }


   public function request()
   {
      return $this->belongsToMany(Request::class, 'request_id');
   }


   public function user()
   {
      return $this->belongsToMany(User::class, 'user_id');
   }
}
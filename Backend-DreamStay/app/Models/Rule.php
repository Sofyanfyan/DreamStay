<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'icon',
      'body',
    ];

   public function kos()
   {
      return $this->belongsToMany(Kos::class, 'kos_rules');
   }
}
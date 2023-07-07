<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos_rule extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'kos_id',
      'rule_id'
    ];

   public function kos()
   {
      $this->belongsTo(User::class, 'kos_id');
   }

   public function rule()
   {
      $this->belongsTo(Rule::class, 'rule_id');
   }
}
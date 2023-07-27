<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos_rule extends Model
{
   use HasFactory;

   protected $fillable = [
      "rule_id",
      "kos_id"
   ];
}
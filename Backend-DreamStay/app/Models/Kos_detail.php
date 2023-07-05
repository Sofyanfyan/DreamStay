<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Detail;
use App\Models\Kos;
use Symfony\Component\CssSelector\Node\FunctionNode;

class Kos_detail extends Model
{
    use HasFactory;

   public function detail()
   {
      return $this->belongsTo(Detail::class);
   } 

   public function kos()
   {
      return $this->belongsTo(Kos::class);
   }
}
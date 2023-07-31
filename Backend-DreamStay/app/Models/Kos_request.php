<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos_request extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'kos_id', 'request_id', 'user_id', 'created_at'];
}
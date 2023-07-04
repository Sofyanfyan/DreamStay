<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailController extends Controller
{
   public function create(Request $request)
   {

      return response()->json([
         'message' => "Success access routes"
      ]);
   }
}
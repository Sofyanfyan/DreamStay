<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorHandlerController extends Controller
{
   public function message($error = "Internal server error", $code = 500)
   {
      $message = $error;


      return response()->json(['error' => $message], $code);
   }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class UserController extends Controller
{
   public function register (Request $request)
   {
      try {
         
      $validator = Validator::make($request->all(), [
         'username' => 'required|min:2',
         'email' => 'required|unique:posts',
         'password' => 'required|min:8',
         'whatsapp' => 'required|min:11',
      ]);

      if($validator->fails())
      {
         return response()->json(
            [
               "Errors" => $validator
            ]
         );
      }

      } catch (\Throwable $th) {
         
         
      }
   }
}
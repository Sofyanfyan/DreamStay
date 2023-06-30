<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
   public function register (Request $request)
   {
      try {
         
      $validator = Validator::make($request->all(), [
         'username' => 'required|min:2',
         'email' => 'required|email|unique:users',
         'password' => 'required|min:8',
         'whatsapp' => 'required|min:11|max:13',
      ]);

      if($validator->fails())
      {
         return response()->json(
            [
               "errors" => $validator->messages()
            ]
         , 400);
      }

      $register = User::create([
         'username' => $request->username,
         'email' => $request->email,
         'password' => $request->password,
         'role' => 'member',
         'whatsapp' => $request->whatsapp,
      ]);

      return response()->json([
         "message" => "Register success",
         "data" => $register,
      ]);

      } catch (Exception $error) {
         
         echo $error;
         $errorHandler = new ErrorHandlerController;
         return $errorHandler->message();
      }
   }
}
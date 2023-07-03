<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

   public function __construct()
   {
         $this->middleware('auth:api', ['except' => ['login', 'register']]);//login, register methods won't go through the api guard
   }

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
         
         $errorHandler = new ErrorHandlerController;
         return $errorHandler->message();
      }
   }


   public function login (Request $request)
   {
      try {
         
         $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
         ]);
         if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
         }

         $credentials = $request->only('email', 'password');
         $checkCredentials = Auth::attempt($credentials);

         $token = auth()->guard('api')->attempt($credentials);

         if (!$checkCredentials) {
            return response()->json(['error' => 'Wrong email/password'], 401);
         }
         
         
         $user = Auth::user();
         return response()->json([
               'user' => $user,
               'authorization' => [
                  'token' => $token,
                  'type' => 'bearer',
               ]
         ]);

      } catch (Exception $e) {
         
         return response()->json([
            'error' => $e
         ]);
      }
   }

}
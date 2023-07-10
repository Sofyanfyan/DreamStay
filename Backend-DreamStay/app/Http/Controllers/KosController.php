<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ErrorHandlerController;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KosController extends Controller
{

   
   public function create(Request $request)
   {
      $errorHandler = new ErrorHandlerController;
      
      try {

         $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'biaya' => 'required|integer',
            'termasuk_listrik' => 'required|boolean',
            'foto_kamar' => 'required|image|mimes:jpeg,png,jpg,gif',
            'foto_kamar1' => 'image|mimes:jpeg,png,jpg,gif',
            'foto_kamar_mandi' => 'required|image|mimes:jpeg,png,jpg,gif',
            'foto_dapur' => 'image|mimes:jpeg,png,jpg,gif',
            'fasilitas_kamar' => 'required|string',
            'fasilitas_kamar_mandi' => 'required|string',
            'fasilitas_dapur' => 'required|string',
            'lantai' => 'required|integer',
            'lebar' => 'required|integer',
            'panjang' => 'required|integer',
            'whatsapp_owner' => 'required|min:11|max:13',
            'user_id' => 'integer',
            'book_id' => 'integer',
         ]);

         if($validator->fails())
         {
            return $errorHandler->message($validator->errors(), 400);
         }

         $arrCreate = [
            
         ];

         if($request->user_id)
         {
            if(!User::where('id', $request->user_id)->first())
            {
               
               return $errorHandler->message("User with id " . $request->user_id . " not found!", 404);
            }
         }

         if($request->book_id)
         {
            if(!DB::table('books')->where('id', $request->book_id)->first())
            {
               return $errorHandler->message("Book with id " . $request->book_id . " not found!", 404);
            }
         }
         
         

         return response()->json(['messages' => "Lolos boss"]);
         
      } catch (Exception $err) {
         //throw $th;

         return $errorHandler->message('Internal server error');
      }
   }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ErrorHandlerController;
use App\Models\Kos;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class KosController extends Controller
{

   
   public function create(Request $request)
   {
      $errorHandler = new ErrorHandlerController;
      
      try {

         $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'biaya' => 'required|integer|min:100000',
            'termasuk_listrik' => 'required|boolean',
            'foto_kamar' => 'required|image|mimes:jpeg,png,jpg,gif',
            'foto_kamar_mandi' => 'required|image|mimes:jpeg,png,jpg,gif',
            'fasilitas_kamar' => 'required|string',
            'fasilitas_kamar_mandi' => 'required|string',
            'fasilitas_dapur' => 'required|string',
            'lantai' => 'required|integer',
            'lebar' => 'required|integer',
            'panjang' => 'required|integer',
            'whatsapp_owner' => 'required|min:11|max:13',
            'foto_kamar1' => 'image|mimes:jpeg,png,jpg,gif',
            'foto_dapur' => 'image|mimes:jpeg,png,jpg,gif',
            'user_id' => 'integer',
            'book_id' => 'integer',
         ]);

         if($validator->fails())
         {
            return $errorHandler->message($validator->errors(), 400);
         }



         
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
         
         $image_foto_kamar = Image::make($request->file('foto_kamar'));
         $foto_kamar = $this->uploadImage($request, 'foto_kamar', $image_foto_kamar);
         
         $image_foto_kamar_mandi = Image::make($request->file('foto_kamar_mandi'));
         $foto_kamar_mandi = $this->uploadImage($request, 'foto_kamar_mandi', $image_foto_kamar_mandi);

         $foto_kamar1 = false;
         $foto_dapur = false;

         if($request->foto_kamar1)
         {
            $image_foto_kamar1 = Image::make($request->file('foto_kamar1'));
            $foto_kamar1 = $this->uploadImage($request, 'foto_kamar1', $image_foto_kamar1);
         }

         if($request->foto_dapur)
         {
            $image_foto_dapur = Image::make($request->file('foto_dapur'));
            $foto_dapur = $this->uploadImage($request, 'foto_dapur', $image_foto_dapur);
         }
         $arrCreate = [
            'type' => $request->type,
            'biaya' => $request->biaya,
            'termasuk_listrik' => $request->termasuk_listrik,
            'foto_kamar' => $foto_kamar,
            'foto_kamar_mandi' => $foto_kamar_mandi,
            'fasilitas_kamar' => $request->fasilitas_kamar,
            'fasilitas_kamar_mandi' => $request->fasilitas_kamar_mandi,
            'fasilitas_dapur' => $request->fasilitas_dapur,
            'lantai' => $request->lantai,
            'lebar' => $request->lebar,
            'panjang' => $request->panjang,
            'whatsapp_owner' => $request->whatsapp_owner,
            'foto_kamar1' => $foto_kamar1 ? $foto_kamar1 : null,
            'foto_dapur' => $foto_dapur ? $foto_dapur : null,
            'user_id' => $request->user_id ? $request->user_id : null,
            'book_id' => $request->book_id ? $request->book_id : null,
         ];

         
         $create = Kos::create($arrCreate);
         return response()->json(['messages' => "create success", "data" => $create], 201);
         
      } catch (Exception $err) {
         //throw $th;
         echo $err;
         return $errorHandler->message('Internal server error');
      }
   }


   private function uploadImage($request, $iconName, $image)
   {
      $fileName = time() . '_' . $iconName . '.' . $request->$iconName->extension();
      $image->resize(800, 800, function($constraint) {
         $constraint->aspectRatio();
      });
      $image->save(public_path('images/kos/' . $fileName));
      
      $local =  env("APP_URL"). ":8000" .'/images/kos/' . $fileName;
      $production = env("APP_URL").'/images/kos/' . $fileName;

      return env("APP_ENV") == "local" ? $local : $production;
   }
}
<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ErrorHandlerController;
use Exception;
use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Support\Facades\DB;

class DetailController extends Controller
{


   public function create(Request $request)
   {

      try {
         $rules = $request->only(['body']);
   
         $validator = Validator::make($rules, [
            'body' => 'required',
         ]);
   
         if($validator->fails())
         {
            // return response()->json(['error' => $validator->errors()], 400);
            $errorHandler = new ErrorHandlerController;
            return $errorHandler->message($validator->error(), 400);
         }
   
         $create = Detail::create([
            'body' => $request->body,
         ]);
   
         return response()->json(['message' => "Success create detail", 'detail' => $create], 201);
      } catch (Exception $err) {
         $errorHandler = new ErrorHandlerController;
         return $errorHandler->message($err);
      }

   }

   public function getAllDetail()
   {
      try {
         
         $data = DB::table('details')
         ->select('id','body')
         ->orderBy('created_at', 'desc')
         ->get();
         
         return response()->json($data, 200);
      } catch (Exception $err) {
         
         $errorHandler = new ErrorHandlerController;
         return $errorHandler->message($err);
      }
   }

   public function getById(Request $request)
   {
      try {
         $data = DB::table('details')
         ->where('id', $request->id)->first();

         if(!$data)
         {
            $error = new ErrorHandlerController;
            return $error->message("Id detail with $request->id not found!", 404);
         }

         return response()->json($data, 200);
         
      } catch (Exception $err) {
         $errorHandler = new ErrorHandlerController;
         return $errorHandler->message($err);
      }

   }

   public function update(Request $request)
   {
      $errorHandler = new ErrorHandlerController;
      
      try {
         
         $id = $request->id;
         $rules = $request->only(['body']);

         if(!Detail::where('id', $id)->first())
         {
            return $errorHandler->message("Id detail with $request->id not found!", 404 );
         }

         $validator = Validator::make($rules, [
            'body' => 'required',
         ]);

         if($validator->fails()){
            
            return $errorHandler->message($validator->errors(), 400);

         }

         Detail::where('id', $id)->update([
            'body' => $request->body,
         ]);

         return response()->json([
            "message" => "Success update detail with id $id"
         ], 201);
         

      } catch (Exception $err) {
         return $errorHandler->message($err);
      }
   }

   public function destroy(Request $request)
   {

      $errorHandler = new ErrorHandlerController;

      try {
         
         $id = $request->id;

         if(!Detail::where('id', $id)->first())
         {
            return $errorHandler->message("Id detail with $request->id not found!", 404 );
         }

         Detail::where('id', $id)->delete();

         return response()->json([
            "message" => "Success delete detail with id $id"
         ], 201);
      } catch (Exception $err) {
         return $errorHandler->message($err);
      }
   }
}
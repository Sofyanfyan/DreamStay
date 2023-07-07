<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ErrorHandlerController;
use App\Models\Rule;
use Exception;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
class RuleController extends Controller
{
   
   public function create(Request $request)
   {

      $errorHandler = new ErrorHandlerController;
   
      try {
         //code...
         $credential = $request->only('icon', 'body');
   
         $validator = Validator::make($credential, [
            'icon' => 'required|image|mimes:jpg,png,jpeg',
            'body' => 'required|max:20',
         ]);
   
         if($validator->fails())
         {
            return $errorHandler->message($validator->errors());
         }

         $iconName = str_replace(" ", "_", $request->body);
         $image = Image::make($request->file('icon'));

         $icon = $this->uploadImage($request, $iconName, $image);

         $create = Rule::create([
            "icon" => $icon,
            "body" => $request->body
         ]);

         return response()->json(["message" => "success create rules", "rules" => $create], 201);
      } catch (Exception $err) {
            return $errorHandler->message("Internal server error");
      }

   }

   public function allRules()
   {
      $errorHandler = new ErrorHandlerController;


      try {
         
         $data = DB::table('rules')->orderBy('created_at', 'desc')->get();

         return response()->json($data);

      } catch (Exception $err) {
         return $errorHandler->message("Internal server error");
      }
   }

   private function uploadImage($request, $iconName, $image)
   {
      $fileName = time() . '_' . 'icon' . '_' . $iconName . '.' . $request->icon->extension();
      $image->resize(800, 800, function($constraint) {
         $constraint->aspectRatio();
      });
      $image->save(public_path('images/rules/' . $fileName));
      
      return env("APP_URL") .'/images/rules/' . $fileName;
   }
}
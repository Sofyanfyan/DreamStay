<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ErrorHandlerController;
use App\Models\Rule;
use Exception;
use Illuminate\Support\Facades\DB;

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

         $icon = $this->uploadImage($request, $iconName);

         $create = Rule::create([
            "icon" => $icon,
            "body" => $iconName
         ]);

         return response()->json(["message" => "success create rules", "rules" => $create], 201);
      } catch (Exception $err) {
            echo $err;
            return $errorHandler->message("Internal server error");
      }

   }

   private function uploadImage($request, $iconName)
   {
      $fileName = time() . '_' . 'icon' . '_' . $iconName . '.' . $request->icon->extension();

      //public folder

      $request->icon->move(public_path('images/rules'), $fileName);

      return env("APP_URL") .'/images/rules/' . $fileName;
   }
}
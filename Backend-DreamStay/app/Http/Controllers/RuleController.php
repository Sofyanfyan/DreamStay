<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ErrorHandlerController;
use App\Models\Rule;
use Doctrine\Inflector\Rules\English\Rules;
use Exception;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;


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

   public function getByIdRules(Request $request)
   {
      $errorHandler = new ErrorHandlerController;

      try {
         $id = $request->id;

         $data = Rule::where('id', $id)->first();

         if(!Rule::where('id', $id)->first())
         {
            return $errorHandler->message("Rules with id $id not found", 404);
         }

         return response()->json($data);
         
      } catch (Exception $err) {
         return $errorHandler->message("Internal server error");
      }

      
   }

   public function destroy(Request $request)
   {

      $errorHandler = new ErrorHandlerController;

      try {
         //code...

         $id = $request->id;

         $data = Rule::where('id', $id)->first();

         if(!Rule::where('id', $id)->first())
         {
            return $errorHandler->message("Rules with id $id not found", 404);
         }

         $this->deleteImage($data);
         
         Rule::where('id', $id)->delete();
         
         return response()->json(['message' => $data]);
      } catch (Exception $err) {
         return $errorHandler->message('Internal server error');
      }
   }

   public function updateRules(Request $request)
   {

      $errorHandler = new ErrorHandlerController;

      try {
         
         $id = $request->id;

         if(!Rule::where('id', $id)->first())
         {
            return $errorHandler->message("Rules with id $id not found", 404);
         }


         $rules = $request->only(['icon', 'body']);
         
         $validator = Validator::make($rules, [
            'icon' => 'required|image|mimes:jpg,png,jpeg',
            'body' => 'required|max:20',
         ]);

         if($validator->fails())
         {
            return $errorHandler->message($validator->errors(), 400);
         }

         $del = Rule::where('id', $id)->first();
         $iconName = str_replace(" ", "_", $request->body);
         $image = Image::make($request->file('icon'));
         
         $this->deleteImage($del);
         $icon = $this->uploadImage($request, $iconName, $image);

         DB::table('rules')->where('id', $id)->update([
            'icon' => $icon,
            'body' => $request->body,
         ]);

         return response()->json(["message" => "Success update rules with id $id"]);

      } catch (Exception $err) {
         //throw $th;
         return $errorHandler->message($validator->errors(), 400);
      }
   }
   
   private function uploadImage($request, $iconName, $image)
   {
      $fileName = time() . '_' . 'icon' . '_' . $iconName . '.' . $request->icon->extension();
      $image->resize(800, 800, function($constraint) {
         $constraint->aspectRatio();
      });
      $image->save(public_path('images/rules/' . $fileName));
      
      $local =  env("APP_URL"). ":8000" .'/images/rules/' . $fileName;
      $production = env("APP_URL").'/images/rules/' . $fileName;

      return env("APP_ENV") == "local" ? $local : $production;
   }

   private function deleteImage($data)
   {

      $path = $data->icon;

      if($path)
      {
         $customPath = explode("/",$path);
         $imagePath = $customPath[sizeof($customPath) - 3] . "/" . $customPath[sizeof($customPath) - 2] . "/" . $customPath[sizeof($customPath) - 1];
         if(File::exists(public_path($imagePath))){
            File::delete(public_path($imagePath));
         }
      }
      
   }
}
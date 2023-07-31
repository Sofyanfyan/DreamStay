<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\{
   Request as Req,
   Kos_request
};
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ErrorHandlerController;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
   public function createReqKos(Request $request)
   {
      
      DB::beginTransaction();
      $errorHandler = new ErrorHandlerController;

      try {

         $user= Auth::guard('api')->user();
         
         $credential =  $request->only(['kos_id', 'book_month']);

         $validator = Validator::make($credential, [
            'kos_id' => 'required|integer', 
            'book_month' => 'required|in:next,now'
         ]);

         if($validator->fails())
         {
            return $errorHandler->message($validator->errors(), 400);
         }
         
         if(!DB::table('kos')->where('id', $request->kos_id)->first())
         {
            return $errorHandler->message("Kos with id $request->kos_id not found!", 404);
         }

         $month = $request->book_month === 'next' ? (int)date('m') + 1 : date('m');
         $month = (string) $month;
         $monthName = date('F', mktime(0, 0, 0, $month, 10));

         $searchData = DB::table('kos_requests')->where('kos_id', $request->kos_id)->where('user_id', $user->id)->get();
         
         if(sizeof($searchData) > 0)
         {
            foreach ($searchData as $el) {

               $dataReq = DB::table('requests')->where('id', $el->request_id)->first();   
               
               if($dataReq->month === $monthName && DB::table('kos_requests')->where('user_id', $user->id)->where('request_id', $dataReq->id)->first()->user_id == $user->id)
               {
                  return response()->json("Your request has been create before", 400);
               }
            }
         }

         $req = Req::create([
            'month' => $monthName,
         ]);

         $kos_req = Kos_request::create([
            'kos_id' => $request->kos_id,
            'user_id' => $user->id,
            'request_id' => $req->id,
         ]);
         
         $req->request = $kos_req;

         DB::commit();

         return response()->json(["request" => "success", "data" => $req], 201);
         
      } catch (Exception $err) {
         DB::rollBack();
         echo $err;
         return response()->json('Internal server error', 500);
      }
   }



   public function getAllRequest(Request $request)
   {
      
      try {
         //code...

         $req = Req::with(['kos', 'user'])->get();
         $orderQuery = $request->query('order');
         $kosQuery = $request->query('kos');

         if($orderQuery && $kosQuery)
         {
            
            $desc = Req::with(['kos', 'user'])->where('kos.id', $kosQuery)->orderByDesc('created_at')->get();

         } else if($request->query('order'))
         {
            $desc = Req::with(['kos', 'user'])->orderByDesc('created_at')->get();
            $asc = Req::with(['kos', 'user'])->orderBy('created_at', 'ASC')->get();
            
            $req = $request->query('order') == 'desc' ? $desc  : $asc;
         }
         
         return response()->json($req, 200);

      } catch (Exception $err) {
         echo $err;
         return response()->json("Internal server error", 500);
      }
   }
}
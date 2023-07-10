<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
   UserController,
   DetailController,
   RuleController,
};
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware(['api'])->group(function () {
   
   Route::post('/register', [UserController::class, 'register']);
   Route::post('/login', [UserController::class, 'login']);
   Route::post('/logout', [UserController::class, 'logout']);
   
   Route::middleware('admin.auth')->group(function() {
      Route::prefix('details')->group(function () {
         Route::post('/', [DetailController::class, 'create']);
         Route::get('/', [DetailController::class, 'getAllDetail']);
         Route::get('/{id}', [DetailController::class, 'getById']);
         Route::put('/{id}', [DetailController::class, 'update']);
         Route::delete('/{id}', [DetailController::class, 'destroy']);

      });
      Route::prefix('rules')->group(function () {
         Route::post('/', [RuleController::class, 'create']);
         Route::get('/', [RuleController::class, 'allRules']);
         Route::get('/{id}', [RuleController::class, 'getByIdRules']);
         Route::delete('/{id}', [RuleController::class, 'destroy']);
         Route::put('/{id}', [RuleController::class, 'updateRules']);
      });
   });

});
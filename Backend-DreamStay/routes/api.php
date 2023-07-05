<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
   UserController,
   DetailController,
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
   
   Route::prefix('details')->group(function () {
      Route::post('/', [DetailController::class, 'create']);
      Route::get('/', [DetailController::class, 'getAllDetail']);
      Route::get('/{id}', [DetailController::class, 'getById']);
   });
});
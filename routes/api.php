<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[UsersController::class,'register']);
Route::post('/login',[UsersController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/all-image',[ImageController::class,'index']);
    Route::post('/add-image',[ImageController::class,'create']);
    Route::get('/edit-image/{id}',[ImageController::class,'edit']);
    Route::post('/update-image/{id}',[ImageController::class,'update']);
    Route::delete('/delete-image/{id}',[ImageController::class,'destroy']);
});


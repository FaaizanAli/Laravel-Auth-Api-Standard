<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
//Register Api
Route::post('register', [UserController::class, 'Register']);
Route::post('login', [UserController::class, 'Login']);
//get profile
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('profile', [UserController::class, 'GetProfile']);
    Route::post('update_profile', [UserController::class, 'UpdateProfile']);
    Route::post('update_password', [UserController::class, 'UpdatePassword']);
    Route::post('logout', [UserController::class, 'Logout']);
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function(){
    return response(['message' => 'Api de prueba backend CRONAPIS']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware'=> 'auth:sanctum'], function(){

    Route::get('/store/paginate', [StoreController::class, 'paginate'])->name('store.paginate');
    
    Route::apiResource('store', StoreController::class)->middleware('auth:sanctum');

});

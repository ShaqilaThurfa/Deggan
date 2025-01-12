<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SuperAdmin;

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

Route::middleware(['cors'])->group(function(){
    
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('me', 'me');
});

Route::get('news', [NewsController::class, 'index']);




Route::middleware(['auth:api'])->group(function () {
   
    Route::get('myposts', [NewsController::class, 'myposts']);
    Route::post('news', [NewsController::class, 'store']);
    Route::get('news/{slug}', [NewsController::class, 'show']);
    Route::put('news/{id}', [NewsController::class, 'update']);
    Route::delete('news/{id}', [NewsController::class, 'destroy']);
});

Route::middleware(['auth:api', 'isSuperAdmin'])->group(function () {
    Route::get('all-user', [SuperAdmin::class, 'index']);
});

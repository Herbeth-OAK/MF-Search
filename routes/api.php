<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Upload\UploadUserImageController;

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


Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/links', [App\Http\Controllers\MediafireLinkController::class, 'index']);
    Route::get('/links/{id}', [App\Http\Controllers\MediafireLinkController::class, 'show']);
    Route::post('/links', [App\Http\Controllers\MediafireLinkController::class, 'store']);
    Route::post('links/{id}/like', [App\Http\Controllers\MediafireLinkController::class, 'like']);
    Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile']);
    Route::get('/profile/{id}', [App\Http\Controllers\UserController::class, 'show']);
    Route::post('/links/search', [App\Http\Controllers\LinkSearchController::class, 'search']);
    
});

Route::group(['prefix' => 'uploads'], static function () {

    Route::post('/userimage', App\Http\Controllers\Upload\UploadUserImageController::class);

});



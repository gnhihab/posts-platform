<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiPostController;
use App\Http\Controllers\ApiUserController;
use App\Http\Controllers\ApiCommentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [ApiUserController::class, 'register']);
Route::post('login', [ApiUserController::class, 'login']);
Route::post('logout', [ApiUserController::class, 'logout'])->middleware('auth:sanctum');


Route::get('posts', [ApiPostController::class, 'index']);
Route::get('post/{id}', [ApiPostController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {

Route::post('post/create', [ApiPostController::class, 'store']);
Route::put('post/update/{id}', [ApiPostController::class, 'update']);
Route::delete('post/delete/{id}', [ApiPostController::class, 'delete']);

Route::post('comment/create/{post}', [ApiCommentController::class, 'store']);
Route::delete('comment/delete/{id}', [ApiCommentController::class, 'delete']);


});



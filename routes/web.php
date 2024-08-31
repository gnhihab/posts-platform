<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::controller(PostController::class)->group(function () {


    Route::get('home' , 'index')->name('posts.home');

    Route::get('show/{id}' , 'show')->name('post.show');

    Route::get('create' , 'create');
    Route::post('store' , 'store')->name('post.store');

    Route::get('edit/{id}' , 'edit');
    Route::put('update/{id}' , 'update')->name('post.update');

    Route::delete('{id}' , 'delete')->name('post.delete');
});

Route::post('posts/{post}/comment',[CommentController::class,'store'])->name('comment.store');

Route::delete('comments/{comment}', [CommentController::class, 'delete'])->name('comment.delete');


<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
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

// public routes
// auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// book
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book_id}', [BookController::class, 'show']);
Route::get('/books/search/{book_name}', [BookController::class, 'search']);

// protect routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // auth
    Route::post('/logout', [AuthController::class, 'logout']);
    // profile
    Route::get('/profile/{user_id}', [ProfileController::class, 'show']);
    Route::put('/profile/{user_id}/photo', [ProfileController::class, 'imageUpload']);
    // book
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{book_id}', [BookController::class, 'update']);
    Route::delete('/books/{book_id}', [BookController::class, 'destroy']);
    Route::post('/books/{book_id}/comment', [CommentController::class, 'store']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

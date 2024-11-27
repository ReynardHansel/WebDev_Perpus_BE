<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/logout', [RegisterController::class, 'logout']);
    // Route::get('/buku', [BukuController::class, 'index']); 

    Route::apiResource('users', UserController::class);
    Route::controller(BukuController::class)->group(function (){
        Route::get('/book/buku', 'index');
        Route::post('/book/store', 'store');
        Route::get('/book/show/{id}', 'show');
        Route::put('/book/update', 'update');
        Route::delete('/book/delete/{id}', 'destroy');
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function (Request $request) {
    return response()->json("Test OK", 200);
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);

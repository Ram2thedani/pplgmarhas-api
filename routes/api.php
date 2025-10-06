<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::apiResource('posts', PostController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('siswa', SiswaController::class);
Route::apiResource('buku', BukuController::class);

Route::get('/', function () {
    return 'welcome';
});

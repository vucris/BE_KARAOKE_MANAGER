<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use  App\Http\Controllers\RoomCartController;


Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']); // Đăng ký
    Route::post('/login', [AuthController::class, 'login']); // Đăng nhập
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']); // Đăng xuất
    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'userProfile']); // Lấy thông tin user
    Route::get('/rooms-list', [RoomController::class, 'index']);
    Route::post('/rooms/{id}/start', [RoomController::class, 'startRoom']);
    Route::post('/rooms/{id}/stop', [RoomController::class, 'stopRoom']);
    Route::get('/rooms-show/{id}', [RoomController::class, 'show']);
    Route::get('/menu-list',[ServiceController::class, 'index']);

    /// giỏ hàng
    Route::get('/room-cart/{roomId}', [RoomCartController::class, 'getRoomCart']);
    Route::post('/room-cart/add', [RoomCartController::class, 'addToCart']);
    Route::put('/room-cart/update/{id}', [RoomCartController::class, 'updateCart']);
    Route::delete('/room-cart/remove/{id}', [RoomCartController::class, 'removeFromCart']);
});





Route::get('/hello', function () {
    return response()->json(['message' => 'Hello laravel!']);
});

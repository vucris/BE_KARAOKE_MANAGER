<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use  App\Http\Controllers\RoomCartController;
use App\Http\Controllers\OrderController;


// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'userProfile']);
    });
});


// Protected API Routes (Chỉ khi có token)
Route::middleware('auth:sanctum')->group(function () {
    // Rooms
    Route::get('/rooms-list', [RoomController::class, 'index']);
    Route::post('/rooms/{id}/start', [RoomController::class, 'startRoom']);
    Route::get('/room-current-price/{id}', [RoomController::class, 'getRoomCurrentPrice']);
    Route::get('/rooms-show/{id}', [RoomController::class, 'show']);
    Route::post('/rooms/{roomId}/checkout', [RoomController::class, 'checkoutRoom']);

    // Menu
    Route::get('/menu-list', [ServiceController::class, 'index']);

    // Room Cart
    Route::get('/room-cart/{roomId}', [RoomCartController::class, 'getRoomCart']);
    Route::post('/room-cart/add', [RoomCartController::class, 'addToCart']);
    Route::put('/room-cart/update/{id}', [RoomCartController::class, 'updateCart']);
    Route::delete('/room-cart/remove/{id}', [RoomCartController::class, 'removeFromCart']);

    // service
    //add dich vu
    Route::post("/add-services", [ServiceController::class, 'store']);
    // delete dich vu
    Route::delete("/delete-services/{id}", [ServiceController::class, 'destroy']);
});



Route::get('/hello', function () {
    return response()->json(['message' => 'Hello laravel!']);
});

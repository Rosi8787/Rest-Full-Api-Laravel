<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShowroomController;
use App\Http\Controllers\TransactionController;

use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('products', ProductController::class);
Route::apiResource('showroom', ShowroomController::class);
Route::post('/transactions', [TransactionController::class, 'store']);
Route::get('/users/{id}/cars', function ($id) {
    $user = \App\Models\User::with('ownedCars')->findOrFail($id);
    return response()->json(['data' => $user]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//protected Route
Route::middleware('auth:api')->group(function(){

//authentication
  Route::get('/me', [AuthController::class, 'me']);
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::post('/refresh',[AuthController::class,'refresh']);

//showroom
  Route::apiResource('showrooms',ShowroomController::class);

//product
  Route::apiResource('product',ProductController::class);

//transaction
  Route::apiResource('transactions',TransactionController::class);

});




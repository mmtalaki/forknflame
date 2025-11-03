<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Public Routes
Route::get('/getRoles', [RoleController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


//Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/saveUser', [UserController::class, 'store']);
    Route::get('/getUser', [UserController::class, 'index']);
    Route::post('/getUser/{id}', [UserController::class, 'show']);
    Route::post('/updateUser/{id}', [UserController::class, 'update']);
    Route::post('/deleteUser/{id}', [UserController::class, 'delete']);

    Route::post('/saveRole', [RoleController::class, 'store']);
    Route::get('/getRole/{id}', [RoleController::class, 'show']);
    Route::post('/updateRole/{id}', [RoleController::class, 'update']);
    Route::delete('/deleteRole/{id}', [RoleController::class, 'delete']);

    Route::post('/saveRestaurant', [RestaurantController::class, 'store']);
    Route::get('/getRestaurant', [RestaurantController::class, 'index']);
    Route::post('/getRestaurant/{id}', [RestaurantController::class, 'show']);
    Route::post('/updateRestaurant/{id}', [RestaurantController::class, 'update']);
    Route::post('/deleteRestaurant/{id}', [RestaurantController::class, 'delete']);

    Route::post('/saveCategory', [CategoryController::class, 'store']);
    Route::get('/getCategory', [CategoryController::class, 'index']);
    Route::post('/getCategory/{id}', [CategoryController::class, 'show']);
    Route::post('/updateCategory/{id}', [CategoryController::class, 'update']);
    Route::post('/deleteCategory/{id}', [CategoryController::class, 'delete']);

    Route::post('/saveFood', [FoodController::class, 'store']);
    Route::get('/getFood', [FoodController::class, 'index']);
    Route::post('/getFood/{id}', [FoodController::class, 'show']);
    Route::post('/updateFood/{id}', [FoodController::class, 'update']);
    Route::post('/deleteFood/{id}', [FoodController::class, 'delete']);

    Route::post('/saveOrder', [OrderController::class, 'store']);
    Route::get('/getOrder', [OrderController::class, 'index']);
    Route::post('/getOrder/{id}', [OrderController::class, 'show']);
    Route::post('/updateOrder/{id}', [OrderController::class, 'update']);
    Route::post('/deleteOrder/{id}', [OrderController::class, 'delete']);
    Route::post('/calculateOrder', [OrderController::class, 'calculateOrder']);
    Route::get('/getUserBalance/{id}', [OrderController::class, 'getUserBalance']);

    Route::post('/savePayment', [PaymentController::class, 'store']);
    Route::get('/getPayment', [PaymentController::class, 'index']);
    Route::post('/getPayment/{id}', [PaymentController::class, 'show']);
    Route::post('/updatePayment/{id}', [PaymentController::class, 'update']);
    Route::post('/deletePayment/{id}', [PaymentController::class, 'delete']);
    Route::post('/calculate-user-balance', [PaymentController::class, 'calculateUserBalance']);
});

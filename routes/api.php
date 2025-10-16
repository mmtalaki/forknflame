<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Public Routes
Route::get('/getRoles', [RoleController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


//Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

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
});

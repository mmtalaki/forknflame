<?php

use App\Http\Controllers\AuthController;
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

});

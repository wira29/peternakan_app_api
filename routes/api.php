<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('permission:manage-users')->apiresource('users', UserController::class);



// Route::middleware('permission:manage-locations')->apiresource('locations', App\Http\Controllers\Api\LocationController::class);
// Route::middleware('permission:manage-materials')->apiresource('materials', App\Http\Controllers\Api\MaterialController::class);

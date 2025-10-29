<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\MaterialController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('permission:manage-users')->group(function () {
    Route::apiresource('users', UserController::class);
    Route::post('users/{id}/restore', [UserController::class, 'restore']);
});

Route::middleware('permission:manage-locations')->group(function () {
    Route::apiresource('locations', LocationController::class);
    Route::post('locations/{id}/restore', [LocationController::class, 'restore']);
});

Route::middleware('permission:manage-materials')->group(function () {
    Route::apiresource('materials', MaterialController::class);
    Route::post('materials/{id}/restore', [MaterialController::class, 'restore']);
});

Route::middleware('permission:manage-feeds')->group(function () {
    Route::apiresource('feeds', \App\Http\Controllers\Api\FeedController::class);
    Route::post('feeds/{id}/restore', [\App\Http\Controllers\Api\FeedController::class, 'restore']);
});

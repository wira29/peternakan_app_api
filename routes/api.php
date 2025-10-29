<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\CageController;
use App\Http\Controllers\Api\BreedController;
use App\Http\Controllers\Api\GoatController;

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
    Route::apiresource('feeds', FeedController::class);
    Route::post('feeds/{id}/restore', [FeedController::class, 'restore']);
});

Route::middleware('permission:manage-cages')->group(function () {
    Route::apiresource('cages', CageController::class);
    Route::post('cages/{id}/restore', [CageController::class, 'restore']);
});

Route::middleware('permission:manage-breeds')->group(function () {
    Route::apiresource('breeds', BreedController::class);
    Route::post('breeds/{id}/restore', [BreedController::class, 'restore']);
});

Route::middleware('permission:manage-goats')->group(function () {
    Route::apiresource('goats', GoatController::class);
    Route::post('goats/{id}/restore', [GoatController::class, 'restore']);
});



<?php

use App\Http\Controllers\Api\FeedSaleController;
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
use App\Http\Controllers\Api\BlendTransactionController;
use App\Http\Controllers\Api\BlendTransactionDetailController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/profile', [UserController::class, 'getCurrentUser']);
});

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
    Route::apiresource('goats', GoatController::class)->parameters(['goats' => 'goat:code']);
    Route::post('goats/{goat:code}/restore', [GoatController::class, 'restore']);
});

Route::middleware('permission:manage-blend-materials')->group(function () {
    Route::apiresource('blend-transactions', BlendTransactionController::class)->except('update');
    Route::post('blend-transactions/{id}/restore', [BlendTransactionController::class, 'restore']);
    // Route::apiResource('blend-transaction-details', BlendTransactionDetailController::class)->only(['show', 'update']);
});

Route::middleware('permission:sale-feeds')->group(function () {
    Route::apiresource('feed-sales', FeedSaleController::class);
    Route::post('feed-sales/{id}/restore', [FeedSaleController::class, 'restore']);
});


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

// Auxiliary endpoints
Route::get('/installation-types', [\App\Http\Controllers\Api\InstallationTypeController::class, 'index'])->middleware('auth:sanctum');
Route::get('/equipment-types', [\App\Http\Controllers\Api\EquipmentTypeController::class, 'index'])->middleware('auth:sanctum');

// Resource routes
Route::apiResource('projects', \App\Http\Controllers\Api\ProjectController::class)->middleware('auth:sanctum');
Route::apiResource('clients', \App\Http\Controllers\Api\ClientController::class)->middleware('auth:sanctum');

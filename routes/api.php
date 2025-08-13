<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CorporationController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenancyPeriodController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'store']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('corporations', CorporationController::class);
    Route::apiResource('buildings', BuildingController::class);
    Route::apiResource('properties', PropertyController::class);
    Route::apiResource('tenancy-periods', TenancyPeriodController::class);
    Route::apiResource('tenants', TenantController::class);
});

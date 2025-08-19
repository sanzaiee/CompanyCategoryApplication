<?php

use App\Http\Controllers\Api\CompanyCategoryController;
use App\Http\Controllers\Api\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api.key')->group(function(){
    Route::prefix('category')->group(function(){
        Route::get('/',[CompanyCategoryController::class,'index']);
        Route::post('/',[CompanyCategoryController::class,'store']);
        Route::get('/search',[CompanyCategoryController::class,'search']);
        Route::get('/{id}',[CompanyCategoryController::class,'show']);
        Route::put('/{id}',[CompanyCategoryController::class,'update']);
        Route::delete('/{id}',[CompanyCategoryController::class,'destroy']);
    });

    Route::prefix('company')->group(function(){
        Route::get('/',[CompanyController::class,'index']);
        Route::get('/{id}',[CompanyController::class,'show']);
        Route::post('/',[CompanyController::class,'store']);
        Route::put('/{id}',[CompanyController::class,'update']);
        Route::delete('/{id}',[CompanyController::class,'destroy']);
    });

});

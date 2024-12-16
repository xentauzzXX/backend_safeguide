<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/contents/search', [SearchController::class, 'index'])->name('contents.search');

Route::get('/category/{category:slug}', [CategoryController::class, 'show']);
Route::apiResource('/categories', CategoryController::class);


Route::get('/content/{content:slug}', [ContentController::class, 'show']);
Route::get('/categories/{category}/contents', [ContentController::class, 'getContentsByCategory'])->name('categories.contents');
Route::apiResource('/contents', ContentController::class);

Route::get('/banner/{banner:slug}', [BannerController::class, 'show']);
Route::apiResource('/banners', BannerController::class);
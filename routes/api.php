<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
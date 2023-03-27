<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FilterProductController;
use App\Http\Controllers\ResetPasswordController;



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('forgotPassword', 'forgotPassword');
    Route::post('resetPassword', 'resetPassword');
});

Route::group(['controller' => UserController::class, 'prefix' => 'users'], function () {
    Route::get('', 'index');
    Route::get('/{user}', 'show');
    Route::put('/{user}', 'updateNameEmail'); 
    Route::delete('/{user}', 'destroy')->middleware(['permission:delete all profils | delete my profil']);
});

Route::group(['controller' => ResetPasswordController::class], function (){
    Route::post('forgot-password', 'sendResetLinkEmail')->middleware('guest')->name('password.email');
    Route::post('reset-password', 'resetPassword')->middleware('guest')->name('password.update');
    Route::get('reset-password/{token}', 
    function (string $token)
    {
         return $token;
    })->middleware('guest')->name('password.reset');
});

Route::group(['controller' => ProductController::class, 'prefix' => 'products'], function () {
    Route::get('', 'index')->middleware(['permission:read products']);
    Route::post('', 'store')->middleware(['permission:create product']);
    Route::get('/{product}', 'show')->middleware(['permission:read products']);
    Route::put('/{product}', 'update')->middleware(['permission:edit my product|edit all products']);
    Route::delete('/{product}', 'destroy')->middleware(['permission:delete all products|delete my product']);
});

Route::group(['controller' => CategoryController::class, 'prefix'=>'category' ], function () {
    Route::get('', 'index')->middleware(['permission:read categories']);
    Route::post('', 'store')->middleware(['permission:create category']);
    Route::get('/{category}', 'show')->middleware(['permission:read categories']);
    Route::put('/{category}', 'update')->middleware(['permission:edit category']);
    Route::delete('/{category}', 'destroy')->middleware(['permission:delete category']);
});

Route::get('/product/filter', [FilterProductController::class, 'filter']);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/', 'namespace'=>'App\Http\Controllers\API\Frontend' ], function() {

    // Auth
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('forgot-password', 'AuthController@forgotPassword');

    // Category
    Route::get('categories', 'CategoryController@index');

    // Book 
    Route::get('books', 'BookController@index');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('borrow-books', 'BorrowBookController@borrowBook');
        Route::post('return-books', 'BorrowBookController@returnBook');
    });
});


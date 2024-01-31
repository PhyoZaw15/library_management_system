<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/v1/', 'namespace'=>'App\Http\Controllers\API\Admin' ], function() {

    Route::post('login', 'AuthController@login')->name('login');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::middleware(['admin'])->group(function () {
            // Users (admin)
            Route::get('users', 'UserController@index');
            Route::post('users', 'UserController@store');
            Route::get('users/{id}', 'UserController@show');
            Route::put('users/{id}', 'UserController@update');
            Route::delete('users/{id}', 'UserController@destroy');

            // Role
            Route::get('roles', 'RoleController@index');
            Route::post('roles', 'RoleController@store');
            Route::get('roles/{id}', 'RoleController@show');
            Route::put('roles/{id}', 'RoleController@update');
            Route::delete('roles/{id}', 'RoleController@destroy');

            // Permissions * In Seeder *
            Route::get('permissions', 'PermissionController@index');

            // Category
            Route::get('categories', 'CategoryController@index');
            Route::post('categories', 'CategoryController@store');
            Route::get('categories/{id}', 'CategoryController@show');
            Route::put('categories/{id}', 'CategoryController@update');
            Route::delete('categories/{id}', 'CategoryController@destroy');

            // Author
            Route::get('authors', 'AuthorController@index');
            Route::post('authors', 'AuthorController@store');
            Route::get('authors/{id}', 'AuthorController@show');
            Route::put('authors/{id}', 'AuthorController@update');
            Route::delete('authors/{id}', 'AuthorController@destroy');

            // Book
            Route::get('books', 'BookController@index');
            Route::post('books', 'BookController@store');
            Route::get('books/{id}', 'BookController@show');
            Route::put('books/{id}', 'BookController@update');
            Route::delete('books/{id}', 'BookController@destroy');

            // Approve to Borrow & Return
            Route::post('approve', 'BorrowBookController@approveByAdmin');

            // Get All Transactions
            Route::get('transactions', 'TransactionController@index');

        });
    });

});


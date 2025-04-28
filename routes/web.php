<?php

use App\Http\Controllers\EquipController;
use App\Http\Controllers\NewUserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/userCreate', function () {
    return view('userCreate');
});

Route::get('/create-product', function () {
    return view('createProduct');
});


Route::get('/product-list', function () {
    return view('productListView');
});

Route::controller(NewUserController::class)->group(function () {
    Route::get('user-list', 'userList');
    Route::get("edit-user", "editUser");
    Route::post('submit-login', 'submitLogin');
    Route::post('create-user', 'createUser');
    Route::post('update-user', 'updateUser');
    Route::get('delete-user', 'deleteUser');
    Route::post('user-data', 'userData');
});

Route::controller(ProductController::class)->group(function () {
    // Route::get('product-list', 'productListView');
    Route::post('save-product', "createProduct");
    Route::post('all-products', "getAllProducts");

    // Routing Example
    // Route::get('edit-product', 'viewEditProducts')->name('testRoute');
    Route::get('edit-products', 'viewEditProducts');
    Route::get('delete-products', 'deleteProducts');
    Route::get('get-product-by-id', 'getProductById');
    Route::post('update-products', 'updateProducts');
});


Route::controller(EquipController::class)->group(function () {
    Route::get('equipment-list', 'viewEquipmentList');
    Route::POST('insert-equips', 'insertEquips');
    Route::POST('load-equips', 'loadEquipsList');
    // Route::get('getPR-list', 'viewEquipmentList');
});

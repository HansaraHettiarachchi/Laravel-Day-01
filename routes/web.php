<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EquipController;
use App\Http\Controllers\NewUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SessionController;
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

Route::controller(NewUserController::class)->group(function () {
    // Route::get('user-list', 'userList');
    Route::get("edit-user", "editUser");
    Route::post('submit-login', 'submitLogin');
    Route::post('create-user', 'createUser');
    Route::post('update-user', 'updateUser');
    Route::get('delete-user', 'deleteUser');
    Route::post('user-data', 'userData');
});

Route::middleware('checkSession.token')->group(function () {

    Route::get('user-list', function () {
        return view('userList');
    });

    Route::get('/create-product', function () {
        return view('createProduct');
    });

    Route::get('/product-list', function () {
        return view('productListView');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::post('/insert-categories', 'insertCategory');

        Route::get('/create-categroy', function () {
            return view('createCategory');
        });
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
        Route::post('import-products', 'importProducts');
    });


    Route::controller(EquipController::class)->group(function () {
        Route::GET('equipments-create', 'createEquipments');
        Route::POST('insert-equips', 'insertEquips');
        Route::POST('load-equips', 'loadEquipsList');
        Route::GET('equipment-list', 'viewEquipments');
        Route::GET('delete-equip', 'deleteEquipments');
        Route::GET('edit-equip', 'getEquipDetails');
        Route::POST('edit-equip-submit', 'editEquip');
        Route::POST('load-equips-items', 'loadEquipItems');

        // Route::get('getPR-list', 'viewEquipmentList');
    });

    Route::controller(SessionController::class)->group(function () {
        Route::GET('distory-session', 'distorySession');
    });
});
